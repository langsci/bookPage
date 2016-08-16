<?php

/**
 * @file plugins/generic/bookPage/BookPagePlugin.inc.php
 *
 * Copyright (c) 2016 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class BookPagePlugin
 */

import('lib.pkp.classes.plugins.GenericPlugin');


//include('LangsciCommonFunctions.inc.php');

class BookPagePlugin extends GenericPlugin {
	/**
	 * Register the plugin.
	 * @param $category string
	 * @param $path string
	 */
	function register($category, $path) {

		if (parent::register($category, $path)) {
			if ($this->getEnabled()) {
				HookRegistry::register ('TemplateManager::display', array(&$this, 'handleDisplayTemplate'));
			}
			return true;
		}
		return false;
	}

	function handleDisplayTemplate($hookName, $args) {

		$request = $this->getRequest();
		$templateMgr =& $args[0];
		$template =& $args[1];
		
		
		
		// Hardcover softcover links from catalog entry tag plugin
		import('plugins.generic.catalogEntryTab.CatalogEntryTabDAO');
		$catalogEntryTabDao = new CatalogEntryTabDAO();
		DAORegistry::registerDAO('CatalogEntryTabDAO', $catalogEntryTabDao);
		
	
		switch ($template) {
			
			case 'frontend/pages/book.tpl':
			
				// variables
				$publishedMonograph = $templateMgr->get_template_vars('publishedMonograph'); // get variable publishedMonograph from template 
				$contextId = $publishedMonograph->getContextId(); 
				$publishedMonographId = $publishedMonograph->getId();
				$request = $this->getRequest();
				$context = $request->getContext();
				
			//	$baseUrl = $request->getBaseUrl();
			//	$pluginPath = $this->getPluginPath();
				
				// testing
			//	$file = fopen("testfile.txt", "a");
			//	fwrite($file, $imagePath);
				
				/*** statistics ***/

				// get folder path
				$baseDir = realpath(__DIR__ . '/../../..');
				
				// get imagePath from plugin settings
				$imagePath = $this->getSetting($context->getId(),'langsci_bookPage_imagePath');
				
				// assing the imagePath to the template if there is a statistic image of this book
				$templateMgr->assign('statImageExists', file_exists(realpath($baseDir.$imagePath.$publishedMonographId.'.svg')));
				$templateMgr->assign('imagePath', $imagePath);
			
			
				/*** reviews ***/
			
				// get review links from the catalog entry tab plugin 
				if(null!==($catalogEntryTabDao->getLink($publishedMonographId,"reviewdescription"))){
					$templateMgr->assign('reviewdescription', $catalogEntryTabDao->getLink($publishedMonographId,"reviewdescription"));
				}
				if(null!==($catalogEntryTabDao->getLink($publishedMonographId,"reviewlink"))){
					$templateMgr->assign('reviewlink', $catalogEntryTabDao->getLink($publishedMonographId,"reviewlink"));
				}
				if(null!==($catalogEntryTabDao->getLink($publishedMonographId,"reviewauthor"))){
					$templateMgr->assign('reviewauthor', $catalogEntryTabDao->getLink($publishedMonographId,"reviewauthor"));
				}
				if(null!==($catalogEntryTabDao->getLink($publishedMonographId,"reviewdate"))){
					$templateMgr->assign('reviewdate', $catalogEntryTabDao->getLink($publishedMonographId,"reviewdate"));
				}
				
				
				/*** vg wort ***/
				
				// generate imageUrl for VG Wort and save it as template variable
				$templateMgr->assign('imageUrl', $this->createVgWortUrl($contextId, $publishedMonographId));
				
				// replace the template book.tpl wich includes the template monograph_full.tpl
				$templateMgr->display($this->getTemplatePath() . 'langsci_book.tpl', 'text/html', 'TemplateManager::display');
				return true;
				
			case 'frontend/pages/catalog.tpl':
			
				// replace the template book.tpl wich includes the template monograph_full.tpl
				$templateMgr->display($this->getTemplatePath() . 'langsci_catalog.tpl', 'text/html', 'TemplateManager::display');
				return true;

		}
		return false;
	}
	
	
	/**
	 * Create the url for the vg wort pixel image with the domain and the public code
	 * @param $contextId int The id of the press
	 * @param $publishedMonographId int The id of the book
	 * @return $imageUrl string The url of the vg wort pixel image 
	 */
	function createVgWortUrl($contextId, $publishedMonographId){
		
		// get the assigned pixel tag of the book
		$pixelTagDao = DAORegistry::getDAO('PixelTagDAO');
		$pixelTagObject = $pixelTagDao->getPixelTagBySubmissionId($contextId, $publishedMonographId);
		
		if($pixelTagObject){
			
			$pixelTag = $pixelTagDao->getPixelTag($pixelTagObject->getId());
			
			// create url
			$imageUrl = 'http://' . $pixelTag->getDomain() . '/na/' . $pixelTag->getPublicCode();
			
			return $imageUrl;
			
		}else return '';
		
	}
	
	

	/**
	 * @copydoc PKPPlugin::getDisplayName()
	 */
	function getDisplayName() {
		return __('plugins.generic.bookPage.displayName');
	}

	/**
	 * @copydoc PKPPlugin::getDescription()
	 */
	function getDescription() {
		return __('plugins.generic.bookPage.description');
	}

	/**
	 * @copydoc PKPPlugin::getTemplatePath
	 */
	function getTemplatePath() {
		return parent::getTemplatePath() . 'templates/';
	}
	
	
	/*
	* settings
	*/
	
	// PKPPlugin::getManagementVerbs()
	function getManagementVerbs() {
		$verbs = parent::getManagementVerbs();
		if ($this->getEnabled()) {
			$verbs[] = array('settings', __('plugins.generic.hallOfFame.settings'));
		}
		return $verbs;
	}

	/**
	 * @see Plugin::getActions()
	 */
	function getActions($request, $verb) {
		$router = $request->getRouter();
		import('lib.pkp.classes.linkAction.request.AjaxModal');
		return array_merge(
			$this->getEnabled()?array(
				new LinkAction(
					'settings',
					new AjaxModal(
						$router->url($request, null, null, 'manage', null, array('verb' => 'settings', 'plugin' => $this->getName(), 'category' => 'generic')),
						$this->getDisplayName()
					),
					__('manager.plugins.settings'),
					null
				),
			):array(),
			parent::getActions($request, $verb)
		);
	}

 	/**
	 * @see Plugin::manage()
	 */
	function manage($args, $request) {
		switch ($request->getUserVar('verb')) {
			case 'settings':
				$context = $request->getContext();
				$this->import('BookPageSettingsForm');
				$form = new BookPageSettingsForm($this, $context->getId());
				if ($request->getUserVar('save')) {
					$form->readInputData();
					if ($form->validate()) {
						$form->execute();
						return new JSONMessage(true);
					}
				} else {
					$form->initData();
				}
				return new JSONMessage(true, $form->fetch($request));
		}
		return parent::manage($args, $request);
	}
	
	
}

?>
