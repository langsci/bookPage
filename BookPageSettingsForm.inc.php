<?php

/**
 * @file plugins/generic/usageStats/UsageStatsSettingsForm.inc.php
 *
 * Copyright (c) 2013-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class UsageStatsSettingsForm
 * @ingroup plugins_generic_usageStats
 *
 * @brief Form for journal managers to modify usage statistics plugin settings.
 */

import('lib.pkp.classes.form.Form');

class BookPageSettingsForm extends Form {

	/** @var $plugin BookPagePlugin */
	var $plugin;

	/**
	 * Constructor
	 * @param $plugin BookPagePlugin
	 */
	function __construct($plugin) {
		$this->plugin = $plugin;

		parent::__construct($plugin->getTemplateResource('settingsForm.tpl'));
	}

	/**
	 * @copydoc Form::initData()
	 */
	function initData() {
		$plugin = $this->plugin;
		$request = Application::get()->getRequest();
		$context = $request->getContext();
		$this->setData('imagePath', $plugin->_getPluginSetting($context, 'imagePath'));
	}

	/**
	 * @copydoc Form::readInputData()
	 */
	function readInputData() {		
		$this->readUserVars(array('imagePath'));
	}

	/**
	 * @copydoc Form::fetch()
	 */
	function fetch($request, $template = null, $display = false) {
		$templateMgr = TemplateManager::getManager($request);
		$context = $request->getContext();
		$application = Application::get();
		$templateMgr->assign(array(
			'imagePath' => $this->plugin->_getPluginSetting($context, 'imagePath') ,
			'pluginName' => $this->plugin->getName(),			
			'applicationName' => $application->getName(),
		));
		return parent::fetch($request, $template, $display);
	}

	/**
	 * @copydoc Form::execute()
	 */
	function execute(...$functionArgs) {
		$plugin = $this->plugin;
		$request = Application::get()->getRequest();
		$context = $request->getContext();
		$contextId = $context ? $context->getId() : CONTEXT_ID_NONE;
		$plugin->updateSetting($contextId, 'imagePath', $this->getData('imagePath'));
		parent::execute(...$functionArgs);
	}
}

