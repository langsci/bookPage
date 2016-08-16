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
						
				// testing
			//	$file = fopen("testfile.txt", "a");
			//	fwrite($file, $publishedMonographId);
				
				// variables
				$publishedMonograph = $templateMgr->get_template_vars('publishedMonograph'); // get variable publishedMonograph from template 
				$contextId = $publishedMonograph->getContextId(); 
				$publishedMonographId = $publishedMonograph->getId();
				
			//	$request = $this->getRequest();
			//	$baseUrl = $request->getBaseUrl();
			//	$pluginPath = $this->getPluginPath();
				
				// statistics: is there a statistic image of this book? statImageExists as variable given to the template 
				// TODO: add imagePath to plugin settings
				$imagePath = 'C:/xampp/htdocs/langsci-dev/public/stats/';
				$templateMgr->assign('statImageExists', file_exists(realpath($imagePath.$publishedMonographId.'.svg')));
				
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
}

?>
