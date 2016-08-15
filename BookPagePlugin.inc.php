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
				$imagePath = 'C:/xampp/htdocs/langsci-dev/public/stats/';
				$templateMgr->assign('statImageExists', file_exists(realpath($imagePath.$publishedMonographId.'.svg')));

				// replace the template book.tpl wich includes the template monograph_full.tpl
				$templateMgr->display($this->getTemplatePath() . 'langsci_book.tpl', 'text/html', 'TemplateManager::display');
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
