<?php

/**
 * @file plugins/generic/addThis/AddThisSettingsForm.inc.php
 *
 * Copyright (c) 2014-2016 Simon Fraser University Library
 * Copyright (c) 2003-2016 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class AddThisSettingsForm
 * @ingroup plugins_generic_AddThis
 *
 * @brief Form for adding/editing the settings for the AddThis plugin
 */

import('lib.pkp.classes.form.Form');

class BookPageSettingsForm extends Form {

	/** @var AddThisBlockPlugin The plugin being edited */
	var $_plugin;

	/** @var int Associated context ID */
	private $_contextId;

	/**
	 * Constructor.
	 * @param $plugin AddThisBlockPlugin
	 * @param $press Press
	 */
	function BookPageSettingsForm($plugin, $contextId) {

		$this->_contextId = $contextId;
		$this->_plugin = $plugin;

		parent::Form($plugin->getTemplatePath() . 'settingsForm.tpl');
		$this->addCheck(new FormValidatorPost($this));

	}

	//
	// Overridden template methods
	//
	/**
	 * Initialize form data from the plugin.
	 */
	function initData() {
	
		$contextId = $this->_contextId;
		$plugin = $this->_plugin;

		$this->setData('langsci_bookPage_imagePath', $plugin->getSetting($contextId, 'langsci_bookPage_imagePath'));
		
	}

	/**
	 * Fetch the form.
	 * @see Form::fetch()
	 * @param $request PKPRequest
	 */
	function fetch($request) {

		$templateMgr = TemplateManager::getManager($request);
		$templateMgr->assign('pluginName', $this->_plugin->getName());
		$templateMgr->assign('pluginBaseUrl', $request->getBaseUrl() . '/' . $this->_plugin->getPluginPath());

		return parent::fetch($request);
	}

	/**
	 * Assign form data to user-submitted data.
	 * @see Form::readInputData()
	 */
	function readInputData() {

		$this->readUserVars(array(
			
			'langsci_bookPage_imagePath',
			
		));
	}

	/**
	 * Save the plugin's data.
	 * @see Form::execute()
	 */
	function execute() {

		$plugin = $this->_plugin;
		$contextId = $this->_contextId;

		$plugin->updateSetting($contextId, 'langsci_bookPage_imagePath', trim($this->getData('langsci_bookPage_imagePath')));
		

	}
}
?>
