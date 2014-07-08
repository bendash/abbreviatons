.. ==================================================
.. FOR YOUR INFORMATION
.. --------------------------------------------------
.. -*- coding: utf-8 -*- with BOM.

.. include:: ../Includes.txt


.. _configuration:

Configuration Reference
=======================

The Extension provides a parser which parses text/html content for entered abbreviations.
A saveHook is registered which calls the parser. Additionally, a scheduled task can be created to crawl the database for updating abbreviations.
The fields where the crawler should look for abbreviations can be configured for any table via TypoScript.

Target group: **Developers**

.. _configuration-typoscript:

TypoScript Reference
--------------------

There are two main parts which can be configured, parser and crawler. Both are configurated out of the box if the static Typoscript (Backend Configuration) is included (required!).
Settings for the parser can be configured via TypoScript Constant Editor, the crawler must be configured via Typoscript Setup.

Properties
^^^^^^^^^^

.. container:: ts-properties for parser

**Parser** (*module.tx_abbreviations.parser*)

==============  ================================  ======================  ===========================
Property        Data type                         :ref:`t3tsref:stdwrap`  Default
==============  ================================  ======================  ===========================
excludeTags     :ref:`t3tsref:data-type-string`   no                      :code:`h1,h2,h3,h4,h5,h6,a`
ignoreCase      :ref:`t3tsref:data-type-boolean`  no                      1
ignoreLanguage  :ref:`t3tsref:data-type-boolean`  no                      0
==============  ================================  ======================  ===========================


Property details
^^^^^^^^^^^^^^^^

.. only:: html

	.. contents::
		:local:
		:depth: 1


.. _ts-module-tx-abbreviations-settings-parser-excludeTags:

excludeTags
"""""""""""

:typoscript:`module.tx_abbreviations.parser.excludeTags =` :ref:`t3tsref:data-type-string`

Comma-separated list of tags. Content of this tags will be excluded from replacement.


.. _ts-module-tx-abbreviations-settings-parser-ignoreCase:

ignoreCase
""""""""""

:typoscript:`module.tx_abbreviations.parser.ignoreCase =` :ref:`t3tsref:data-type-boolean`

If set, words are searched case-insensitive.


.. _ts-plugin-tx-abbreviations-parser-ignoreLanguage:

ignoreLanguage
""""""""""""""

:typoscript:`module.tx_abbreviations.parser.ignoreLanguage =` :ref:`t3tsref:data-type-boolean`

If set, language of abbreviation records or the current text is ignored.

**Crawler** (*module.tx_abbreviations.settings.crawler*)

=====================  ===============================  ======================  ==========================
Property               Data type                        :ref:`t3tsref:stdwrap`  Default
=====================  ===============================  ======================  ==========================
tables                 :ref:`t3tsref:data-type-array`   no                      :code:`tt_content { ... }`
tables.[table].fields  :ref:`t3tsref:data-type-string`  no                      :code:`bodytext`
=====================  ===============================  ======================  ==========================


Property details
^^^^^^^^^^^^^^^^

.. only:: html

	.. contents::
		:local:
		:depth: 1


.. _ts-module-tx-abbreviations-settings-crawler-excludeTags:

tables
"""""""

:typoscript:`module.tx_abbreviations.settings.crawler.tables =` :ref:`t3tsref:data-type-array`

Tables where the crawler should search for the fields. (e.g 'tt_content')


.. _ts-module-tx-abbreviations-settings-crawler-[table]-fields:

tables.[table].fields
"""""""""""""""""""""

:typoscript:`module.tx_abbreviations.settings.crawler.tables.[table].fields =` :ref:`t3tsref:data-type-string`

Comma-separated list of fields for the table where abbreviations should be searched. (e.g 'bodytext,imagecaption')