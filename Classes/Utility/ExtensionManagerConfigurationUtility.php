<?php
namespace WorldDirect\Abbreviations\Utility;

/***************************************************************
 *  Copyright notice
 *
 *  (c) Steffen Ritter (info@rs-websystems.de)
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
 *  A copy is found in the text file GPL.txt and important notices to the license
 *  from the author is found in LICENSE.txt distributed with these scripts.
 *
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * class providing configuration options for ext abbreviations.
 *
 * @author Ben Walch <ben.walch@world-direct.at>
 */
class ExtensionManagerConfigurationUtility {

	/**
	 * @var array
	 */
	protected $extConf = array();

	/**
	 * Initializes this object.
	 *
	 * @return void
	 */
	private function init() {
		// $requestSetup = $this->processPostData((array) $_REQUEST['data']);
		$this->extConf = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['abbreviations']);
		if(!is_array($this->extConf['tables']))
			$this->extConf['tables'] = array();
		// $GLOBALS['LANG']->includeLLFile('EXT:saltedpasswords/locallang.xlf');
	}

	/**
	 * Renders a selector element that allows to select the tables to be used.
	 *
	 * @param array $params Field information to be rendered
	 * @param \TYPO3\CMS\Core\TypoScript\ConfigurationForm $pObj The calling parent object.
	 * @return string The HTML selector
	 */
	public function buildTableSelector(array $params, $pObj) {
		$this->init();
		\TYPO3\CMS\Extbase\Utility\DebuggerUtility::var_dump($params);
		$p_field = '';
		$tables = array_keys($GLOBALS['TCA']);
		foreach ($tables as $table) {
			$sel = (in_array($table, $this->extConf['tables'])) ? ' selected="selected" ' : '';
			$p_field .= '<option value="' . $table . '"' . $sel . '>' . $table . '</option>';
		}
		$p_field = '<select name="' . $params['fieldName'] . '" size="10" multiple="true">' . $p_field . '</select>';
		return $p_field;
	}

}
