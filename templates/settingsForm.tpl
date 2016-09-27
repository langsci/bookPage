{**
 * @file plugins/generic/bookPage/templates/settingsForm.tpl
 *
 * Copyright (c) 2016 Language Science Press
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 *}

<script>
	$(function() {ldelim}
		// Attach the form handler.
		$('#bookPageSettingsForm').pkpHandler('$.pkp.controllers.form.AjaxFormHandler');
	{rdelim});
</script>

<form class="pkp_form" id="bookPageSettingsForm" method="post" action="{url router=$smarty.const.ROUTE_COMPONENT op="manage" category="generic" plugin=$pluginName verb="settings" save=true}">

	{fbvFormArea id="bookPageSettingsForm" class="border" title="plugins.generic.bookPage.settings.title"}

		{** image path **}

		{fbvFormSection}

			<p class="pkp_help">{translate key="plugins.generic.bookPage.settings.imagePath"}</p>
			{fbvElement type="text" label="plugins.generic.bookPage.settings.imagePath.label" id="langsci_bookPage_imagePath" value=$langsci_bookPage_imagePath maxlength="100" size=$fbvStyles.size.MAXIMUM}

		{/fbvFormSection}

		{fbvFormButtons submitText="common.save"}

	{/fbvFormArea}
</form>

<p><span class="formRequired">{translate key="common.requiredField"}</span></p>

