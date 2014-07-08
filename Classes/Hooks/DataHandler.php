<?php
namespace WorldDirect\Abbreviations\Hooks;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Ben Walch <ben.walch@world-direct.at>
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Class/Function which offers TCE main hook functions.
 *
 * @author		Ben Walch <ben.walch@world-direct.at>
 * @package		TYPO3
 * @subpackage	tx_abbreviations
 */
class DataHandler {
	
	/**
	 * objectManager
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
	 */
	protected $objectManager;
	
	/**
	 * configurationManager
	 *
	 * @var \TYPO3\CMS\Extbase\Object\ObjectManager
	 */
	protected $configurationManager;
	
	/**
	 * parseUtility
	 *
	 * @var \WorldDirect\Abbreviations\Utility\ParseUtility
	 */
	protected $parseUtility;
	
	
	public function __construct() {
		$this->objectManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('\\TYPO3\CMS\Extbase\Object\ObjectManager');
		$this->configurationManager = $this->objectManager->get('\\TYPO3\\CMS\\Extbase\\Configuration\\ConfigurationManager');
		$this->parseUtility = $this->objectManager->get('\\WorldDirect\\Abbreviations\\Utility\\ParseUtility');
		$this->parseUtility->initialize();
	}

	/**
	 * Function to set the proper image width and image orient on a tt_content element
	 *
	 * @param $status
	 * @param    str             $table: The name of the table the data should be saved to
	 * @param    int             $id: The uid of the page we are currently working on
	 * @param    array           $fieldArray: The array of fields and values that have been saved to the datamap
	 * @param    \TYPO3\CMS\Core\DataHandling\DataHandler   $parentObj: The parent object that triggered this hook
	 * @return   void
	 */
	public function processDatamap_postProcessFieldArray($status, $table, $id, array &$fieldArray, \TYPO3\CMS\Core\DataHandling\DataHandler $parentObj) {
				
		$config = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS,'abbreviations');
		
		if (!is_array($config['crawler']['tables']))
			return;
		
		foreach ($config['crawler']['tables'] as $currentTable => $tableConf) {
			if ($currentTable == $table) {
				if ($status == 'update') {
					$record = \TYPO3\CMS\Backend\Utility\BackendUtility::getRecord($table, $id);
				} else {
					$record = $fieldArray;
				}
				$record = array_merge($record, $fieldArray);
				
				$this->parseUtility->workOnLanguage($record['sys_language_uid']);
							
				$fields = explode(',', $tableConf['fields']);
				foreach($fields as $field) {
					if ($fieldArray[$field])
						$fieldArray[$field] = $this->parseUtility->processContent($fieldArray[$field]);
				}
				break;
			}
		}
	}
}

if (defined('TYPO3_MODE') && isset($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/abbreviations/Classes/Hooks/DataHandler.php'])) {
	include_once($GLOBALS['TYPO3_CONF_VARS'][TYPO3_MODE]['XCLASS']['ext/abbreviations/Classes/Hooks/DataHandler.php']);
}
