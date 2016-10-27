Key data
============

- name of the plugin: Book Page Plugin
- author: Svantje Lilienthal
- current version: 1.2
- tested on OMP version: 1.2.0
- github link: https://github.com/langsci/bookPage
- community plugin: no
- date: 2016/08/16

Description
============

This plugin replaces templates for the catalog and the book page in OMP.

 * hidden series postion of the book at the catalog view and title added
 * changed cover display method to show a bigger image
 * display of statistic png images at book page - path to image folder in the plugin setting
 * vg wort pixel added to download links
 * link to gitHub repo at book page 
 * display reviews from the catalogEntryTab (links)
 * Open review files (PDF-OR) are hidden
 
Implementation
================

Hooks
-----
- used hooks: 3

		TemplateManager::display
		TemplateManager::include
		Templates::Catalog::Book::Main

New pages
------
- new pages: 0

Templates
---------
- templates that replace other templates: 5
		langsci_catalog.tpl replaces frontend/pages/catalog.tpl
		langsci_book.tpl replaces frontend/pages/book.tpl 
		langsci_monograph_full.tpl replaces frontend/objects/monograph_full.tpl
		langsci_monograph_summary.tpl replaces frontend/objects/monograph_summary.tpl
		langsci_downloadLink.tpl replaces frontend/components/downloadLink.tpl
		monographList.tpl replaces frontend/components/monographList.tpl

- templates that are modified with template hooks: 1
		frontend/objects/monograph_full.tpl: Templates::Catalog::Book::Main
		
- new/additional templates: 1
		additionalContent.tpl

Database access, server access
-----------------------------
- reading access to OMP tables: yes (setting)
- writing access to OMP tables: yes (setting)
- new tables: 0
- nonrecurring server access: no
- recurring server access: optional
 
Classes, plugins, external software
-----------------------
- OMP classes used (php): 2

		GenericPlugin
		TemplateManager

- OMP classes used (js, jqeury, ajax): 0
- necessary plugins: 0
- optional plugins: 2
		
		catalog entry tab plugin
		vg wort plugin

- use of external software: no
- file upload: optional
		
		statistics images (png) can be uploaded to a folder
		or external storage of images 
		both ways enter path in plugin settings 
 
Metrics
--------
- number of files: 14 (without external software)
- number of lines: ca. 2000 (without external software)

Settings
--------
- settings: yes

Plugin category
----------
- plugin category: generic

Other
=============
- does using the plugin require special (background)-knowledge?: yes, template for the book page has to be adapted
- access restrictions: no
- adds css: no
