<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'WorldDirect.' . $_EXTKEY,
	'Pi1',
	array(
		'Abbreviation' => 'list',
		
	),
	// non-cacheable actions
	array(
		'Abbreviation' => '',
		
	)
);

?>