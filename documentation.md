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

 * hidden series postion of the book at the catalog view
 * changed cover display method to show a bigger image
 * display of statistic svg images (public/stats) at book page - path to image folder in the plugin setting (e.g. /public/stats/)
 * vg wort pixel added to download links
 * changes from Nate https://github.com/pkp/pkp-lib/issues/1428 to connect chapter and downloads at book page n.
 * link to gitHub repo at book page 
 * display reviews from the catalogEntryTab (links)
 * Open review files (PDF-OR) are hidden
 * TODO: Cite as 
 
Implementation
================

Hooks
-----
- used hooks: 1

		TemplateManager::display

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
		langsci_monographList.tpl replaces frontend/components/monographList.tpl

- templates that are modified with template hooks: 0
- new/additional templates: 0

Database access, server access
-----------------------------
- reading access to OMP tables: yes (setting)
- writing access to OMP tables: yes (setting)
- new tables: 0
- nonrecurring server access: no
- recurring server access: no
 
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
- file upload: yes
		
		statistics images (svg) has to be uploaded to a folder
		enter path in plugin settings like public/stats/
 
Metrics
--------
- number of files: [10] (without external software)
- number of lines: [428] (without external software)

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
