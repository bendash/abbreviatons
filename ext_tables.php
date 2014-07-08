<?php
if (!defined('TYPO3_MODE')) {
	die('Access denied.');
}

// Register Plugin
\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
	$_EXTKEY,
	'Pi1',
	'LLL:EXT:abbreviations/Resources/Private/Language/locallang_db.xlf:tx_abbreviations.plugin.pi1'
);

// Include Static Typoscript
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Backend/', 'Backend Configuration');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile($_EXTKEY, 'Configuration/TypoScript/Frontend/', 'Frontend Configuraiton');

// Register Abbreviations Scheduler Task
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['extbase']['commandControllers'][] = 'WorldDirect\\Abbreviations\\Command\\AbbreviationManagerCommandController';


// Register Hook
$GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['processDatamapClass'][] = 'EXT:abbreviations/Classes/Hooks/DataHandler.php:WorldDirect\\Abbreviations\\Hooks\\DataHandler';

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_abbreviations_domain_model_abbreviation', 'EXT:abbreviations/Resources/Private/Language/locallang_csh_tx_abbreviations_domain_model_abbreviation.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_abbreviations_domain_model_abbreviation');
$GLOBALS['TCA']['tx_abbreviations_domain_model_abbreviation'] = array(
	'ctrl' => array(
		'title'	=> 'LLL:EXT:abbreviations/Resources/Private/Language/locallang_db.xlf:tx_abbreviations_domain_model_abbreviation',
		'label' => 'abbreviation',
		'tstamp' => 'tstamp',
		'crdate' => 'crdate',
		'cruser_id' => 'cruser_id',
		'dividers2tabs' => TRUE,

		'versioningWS' => 2,
		'versioning_followPages' => TRUE,

		'languageField' => 'sys_language_uid',
		'transOrigPointerField' => 'l10n_parent',
		'transOrigDiffSourceField' => 'l10n_diffsource',
		'delete' => 'deleted',
		'enablecolumns' => array(
			'disabled' => 'hidden',
			'starttime' => 'starttime',
			'endtime' => 'endtime',
		),
		'searchFields' => 'type,term,abbreviation,',
		'dynamicConfigFile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extPath($_EXTKEY) . 'Configuration/TCA/Abbreviation.php',
		'iconfile' => \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::extRelPath($_EXTKEY) . 'Resources/Public/Icons/tx_abbreviations_domain_model_abbreviation.gif'
	),
);
