# Abbreviations #

## What does it do? ##

This extension provides a global, simple abbreviation management based on extbase.

* **Simple Abbreviation Management**
  * Abbreviaton Records are created at a central storage. They are localizable, and can also be created only for a specific language.
* **Auto Detecting Feature**
  * Abbreviations entered in text content are detected automatically, and will be replaced with an <abbr> tag and the according term.
* **Consistent and up to date**
  * A scheduled task can be created to keep all abbreviations consistent and up to date.

## Users Manual ##

1. Install Extension
2. Include the static Typoscript Template
  * Backend (required)
  * Frontend (optional, if you wish to use the frontend plugin to generate a list of all abbreviations with its expressions)
2. Create some abbreviations in a sysfolder
3. Create text content including some abbreviations on a page
4. Save and view the page

## Configuration Reference ##

The Extension provides a parser which parses text/html content for entered abbreviations. A saveHook is registered which calls the parser, additionally a scheduled task can be created to crawl the database and update abbreviations. The fields where the crawler should look for abbreviations can be configured for any table via TypoScript.

### TypoScript Reference ###

There are two main parts. parser and crawler. Both are configurated out of the box if the static Typoscript (Backend) is included (required!). Settings for the parser can be configured via TypoScript Constant Editor, the crawler must be configured via Typoscript Setup.

#### Parser (module.tx_abbreviations.parser) ####

| Property       | Data Type        | Default                    |
| :------------- | :--------------: | --------------------------:|
| excludeTags    | list of strings  |    `h1,h2,h3,h4,h5,h6,a`   |
| ignoreCase     | boolean          |    `1`                     |
| ignoreLanguage | boolean          |    `0`                     |

Property details

**excludeTags**
```
  module.tx_abbreviations.parser.excludeTags = string,string
```
Comma-separated list of tags. Content of this tags will be excluded from replacement.

**ignoreCase**
```
module.tx_abbreviations.parser.ignoreCase = boolean
```
If set, words are searched case-insensitive.

**ignoreLanguage**
```
module.tx_abbreviations.parser.ignoreLanguage = boolean
```
If set, language of abbreviation records or the current text is ignored.

#### Crawler (module.tx_abbreviations.settings.crawler) ####

| Property               |  Data type       | Default              |
| :----------------------| :--------------: | -------------------: |
| tables                 | array            | `tt_content { ... }` |
| tables.[table].fields  | list of strings  | `bodytext`           |

Property details

**tables** 
```
module.tx_abbreviations.settings.crawler.tables = tablename
module.tx_abbreviations.settings.crawler.tables.tablename {
  ...
}
```
Tables where the crawler should search for the fields. (e.g 'tt_content')

**tables.[table].fields**
```
module.tx_abbreviations.settings.crawler.tables.[tablename].fields = fieldname1,fieldname2
```
Comma-separated list of fields for the table where abbreviations should be searched. (e.g 'bodytext,imagecaption')
