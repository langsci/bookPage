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
				HookRegistry::register('TemplateManager::include', array(&$this, 'handleIncludeTemplate'));
				HookRegistry::register('Templates::Catalog::Book::Main', array($this, 'addLangsciContent'));
			}
			return true;
		}
		return false;
	}
	
	/**
	 * Handle templates that are called with the display hook.
	 * @param $hookName string
	 * @param $args array
	 */
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
				
				// statistics 
				
				// get imagePath from plugin settings
				$imagePath = $this->getSetting($context->getId(),'langsci_bookPage_imagePath');
				$statImageExists = false;
				
				// check if the image path is an url or a local folder path
				if(filter_var($imagePath, FILTER_VALIDATE_URL)!==false){
					
					// remote check if image exists
					if(@fopen($imagePath.$publishedMonographId.'.png', 'r')) $statImageExists = true;
				
				}else{
					
					// add basedir to image path
					$baseDir = realpath(__DIR__ . '/../../..');
					
					// local check if image exists
					if(file_exists(realpath($baseDir.$imagePath.$publishedMonographId.'.png'))) $statImageExists = true;
				}
				
				// assing variables imagePath and statImageExists to the template
				$templateMgr->assign('statImageExists', $statImageExists);
				$templateMgr->assign('imagePath', $imagePath);
			
				// reviews
			
				// get review links from the catalog entry tab plugin 
				// TODO: update correct DAO from catalog entry tab plugin
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
				
				// vg wort
				
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
	 * Handle templates that are called with an include hook.
	 * @param $hookName string
	 * @param $args array
	 */
	function handleIncludeTemplate($hookName, $args) {
		
		$templateMgr =& $args[0];
		$params =& $args[1];
		if (!isset($params['smarty_include_tpl_file'])) {
			return false;
		}
		
		switch ($params['smarty_include_tpl_file']) {
			// FIX ME: include does not work within monographList.tpl that is included in catalog.tpl. Variable $monograph does not seem to be passed on.
			// replaces the monograph summary view at the series page
			case 'frontend/objects/monograph_summary.tpl':
				$templateMgr->display($this->getTemplatePath() . 
				'langsci_monograph_summary.tpl', 'text/html', 'TemplateManager::include');
				return true;
			case 'frontend/objects/monograph_full.tpl':
				$templateMgr->display($this->getTemplatePath() . 
				'langsci_monograph_full.tpl', 'text/html', 'TemplateManager::include');
				return true; 
			// FIX ME: same problem as above
			case 'frontend/components/downloadLink.tpl':
				$templateMgr->display($this->getTemplatePath() . 
				'langsci_downloadLink.tpl', 'text/html', 'TemplateManager::include');
				return true; 
		}
		return false;
	}

	/**
	 * Add langsci specific content to the book page
	 * @param $hookName string
	 * @param $args array
	 */
	function addLangsciContent($hookName, $args){
		
		$output =& $args[2];
		$request = $this->getRequest();
		$templateMgr = TemplateManager::getManager($request);
		$output .=  $templateMgr->fetch($this->getTemplatePath() . 'additionalContent.tpl');
		   
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
