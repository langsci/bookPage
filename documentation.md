Key data
============

- name of the plugin: Book Page Plugin
- author: Svantje Lilienthal
- current version: 1.2
- tested on OMP version: 1.2.0
- github link: https://github.com/langsci/bookPage
- community plugin: yes
- date: 2016/07/25

Description
============

This plugin replaces templates for the catalog and the book page in OMP (frontend/pages/catalog.tpl, frontend/pages/book.tpl and frontend/objects/monograph_full.tpl). The book page is added with a statistic image uploaded to public/downloads/stats. The cover is replaced by a bigger one. The vg wort pixel is included to the download buttons (pixel may be added with the vg wort plugin). 
Open review .... Cite as .... Display Chapters with download option ....
 
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
- templates that replace other templates: 3

		langsci_catalog.tpl replaces frontend/pages/catalog.tpl
		langsci_book.tpl replaces frontend/pages/book.tpl 
		langsci_monograph_full.tpl replaces frontend/objects/monograph_full.tpl

- templates that are modified with template hooks: 0
- new/additional templates: 0

Database access, server access
-----------------------------
- reading access to OMP tables: 0
- writing access to OMP tables: 0
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
		
		statistics images has to be uploaded to public/downloads/stats as svg files
 
Metrics
--------
- number of files: [10] (without external software)
- number of lines: [428] (without external software)

Settings
--------
- settings: no

Plugin category
----------
- plugin category: generic

Other
=============
- does using the plugin require special (background)-knowledge?: yes, template for the book page has to be adapted
- access restrictions: no
- adds css: no
