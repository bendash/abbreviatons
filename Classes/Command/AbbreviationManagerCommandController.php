<?php
namespace WorldDirect\Abbreviations\Command;

/**
 * Glossary Command Controller
 * @package theme_bmeia
 * @author Ben Walch <ben.walch@world-direct.at>
 */
class AbbreviationManagerCommandController extends \TYPO3\CMS\Extbase\Mvc\Controller\CommandController {
	
	/**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     * @inject
     */
    protected $configurationManager;
	
	/**
	 * dataHandler
	 *
	 * @var \TYPO3\CMS\Core\DataHandling\DataHandler
	 * @inject
	 */
	protected $dataHandler;
	
	/**
	 * parseUtility
	 *
	 * @var \WorldDirect\Abbreviations\Utility\ParseUtility
	 * @inject
	 */
	protected $parseUtility;
	
	/**
	 * defaultReplacePattern
	 *
	 * @var array
	 */
	protected $languages;
	
	/**
	 * config
	 *
	 * @var array
	 * 
	 */
	protected $config;
	
	
	/**
	 * initialize
	 *
	 * @return void
	 */
	public function initialize() {
		$this->parseUtility->initialize();
		$this->getConfig();
		$this->getLanguages();
	}
	
	/**
	 * Crawls Database and replaces words which match any entered abbreviation 
	 * Existing abbreviations are updated
	 * 
	 * @return void
	 */
	public function crawlDatabaseCommand() {
		
		$this->initialize();
				
		foreach ($this->languages as $language) {
			$this->parseUtility->workOnLanguage((int)$language['uid']);
			foreach ($this->config['tables'] as $table => $tableConfig) {
				if ($GLOBALS['TCA'][$table]) {
					$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery(
						'uid,pid,' . $GLOBALS['TCA'][$table]['ctrl']['languageField'] . ',' . implode(',', $tableConfig['fields']),
						$table,
						$GLOBALS['TCA'][$table]['ctrl']['delete'] . '=0 AND ' . $GLOBALS['TCA'][$table]['ctrl']['languageField'] . '=' . $language['uid'] . ' ' . $tableConfig['additionalWhere'],
						'',
						''
					);
					
					if($result) {
						while ($row = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)) {
							foreach($tableConfig['fields'] as $field) {
								$content = $this->parseUtility->processContent($row[$field]);
								if ($content != $row[$field]) {
									if($GLOBALS['TYPO3_DB']->exec_UPDATEquery($table, 'uid='.$row['uid'], array($field => $content))) {
										$this->dataHandler->clear_cacheCmd($row['pid']);
										//echo "Updated " . $table.":".$row['uid'] . " on pid " . $row['pid'] . " with language uid " . $language['uid'] . "\n";
									} else {
										$GLOBALS['BE_USER']->simpleLog('AbbreviationsManager->crawlDatabase: ' . $GLOBALS['TYPO3_DB']->sql_error(), 'abbreviations', 1);
									}
								}
							}
							// Wait a little to not exhaust the DB
							usleep(10);
						}
					} else {
						$GLOBALS['BE_USER']->simpleLog('AbbreviationsManager->crawlDatabase: ' . $GLOBALS['TYPO3_DB']->sql_error(), 'abbreviations', 1);
					}
				} else {
					$GLOBALS['BE_USER']->simpleLog('AbbreviationsManager->crawlDatabase: Unkown Table "' . $table . '"!', 'abbreviations', 1);
				}
			}
		}
	}
	
	/**
	 * getConfig
	 *
	 * @return void
	 */
	private function getConfig() {
		$config = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'abbreviations');
		$this->config = $config['crawler'];
		foreach ($this->config['tables'] as $table => $tableConfig) {
			if ($tableConfig['fields']) {
				$this->config['tables'][$table]['fields'] = explode(',', $tableConfig['fields']);
			}
		}
	}
	
	/**
	 * getLanguages
	 *
	 * @return void
	 */
	private function getLanguages() {
		$this->languages = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid', 'sys_language', '');
		// add default language uid
		array_unshift($this->languages, array('uid' => 0));
	}
	
}

?>
