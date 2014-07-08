<?php
namespace WorldDirect\Abbreviations\Utility;

/**
 * Parse Utility for Abbreviations
 * @package abbreviations
 * @author Ben Walch <ben.walch@world-direct.at>
 */
class ParseUtility implements \TYPO3\CMS\Core\SingletonInterface {

	/**
     * @var \TYPO3\CMS\Extbase\Object\ObjectManagerInterface
     * @inject
     */
    protected $objectManager;
	
	/**
     * @var \TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface
     * @inject
     */
    protected $configurationManager;
	
	/**
	 * persistenceManager
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager
	 * @inject
	 */
	protected $persistenceManager;
	
	/**
	 * abbreviationRepository
	 *
	 * @var \WorldDirect\Abbreviations\Domain\Repository\AbbreviationRepository
	 * @inject
	 */
	protected $abbreviationRepository;
	
	/**
	 * config
	 *
	 * @var array
	 */
	protected $config;
	
	/**
	 * abbreviationsEntries
	 *
	 * @var array
	 *
	 */
	protected $abbreviationEntries = array();
	
	/**
	 * defaultReplacePattern
	 *
	 * @var string
	 */
	protected $defaultReplacePattern = '<abbr title="%2$s">%1$s</abbr>';
	
	/**
	 * construct
	 *
	 * @return void
	 */
	public function initialize() {
		$this->getConfig();
		$this->workOnLanguage(0);
	}
	
	/**
	 * workOnLanguage
	 *
	 * specify a sys language uid where the parse utility should work on.
	 * this only takes effect when config['ignoreLanguage'] evaluates to false.
	 *
	 * @param int $languageUid
	 *
	 * @return void
	 */
	public function workOnLanguage($languageUid) {
		// this is needed to clear in-memory cache where records are stored with other language
		$defaultQuerySettings = $this->getQuerySettingsForRepository();
		$defaultQuerySettings->setLanguageUid($languageUid);
		$this->abbreviationRepository->setDefaultQuerySettings($defaultQuerySettings);
		
		$this->getAbbreviationEntries();
	}
	
	/**
	 * getConfig
	 *
	 * @return void
	 */
	private function getConfig() {
		$config = $this->configurationManager->getConfiguration(\TYPO3\CMS\Extbase\Configuration\ConfigurationManagerInterface::CONFIGURATION_TYPE_SETTINGS, 'abbreviations');
		$config = $config['parser'];
		if($config['excludeTags']) {
			$config['excludeTags'] = explode(',', strtolower($config['excludeTags']));
			// if a tag is in list for exclude tags, the link tag created by rte should also be excluded
			if(in_array('a', $config['excludeTags']))
			$config['excludeTags'][] = 'link';
		}			
		$this->config = $config;
	}
	
	/**
	 * getQuerySettingsForRepository
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\Generic\Typo3QuerySettings $querySettings
	 */
	private function getQuerySettingsForRepository() {
		$querySettings = $this->objectManager->get('\\TYPO3\\CMS\\Extbase\\Persistence\\Generic\\Typo3QuerySettings');
		$querySettings->setRespectStoragePage(FALSE);
		if ((int)$this->config['ignoreLanguage'] == 1) {
			$querySettings->setRespectSysLanguage(FALSE);
			$querySettings->setLanguageMode('ignore')->setLanguageOverlayMode(FALSE);
		} else {
			$querySettings->setLanguageMode('strict');
		}
		return $querySettings;
	}
	
	/**
	 * getAbbreviationEntries
	 *
	 * @return void
	 */
	private function getAbbreviationEntries() {
		
		$this->persistenceManager->clearState();
		$this->abbreviationEntries = array();
		
		foreach ($this->abbreviationRepository->findAll()->toArray() as $abbreviationEntry) {
			$key = ((int)$this->config['ignoreCase'] == 1) ? strtolower($abbreviationEntry->getAbbreviation()) : $abbreviationEntry->getAbbreviation();
			$this->abbreviationEntries[$key] = $abbreviationEntry;
		}
	}
	
	/**
	 * processContent
	 *
	 * @param string $content string to process
	 *
	 * @return string processed string
	 */
	public function processContent($content) {
		// extracts all Tags from the given string
		// The first group contains the text before the first tag
		// The second group contains the tags
		// The third group contains any text between the tags
		if(preg_match_all('/([^<]*)(<[^>]*>)([^<]*)/', $content, $matches)) {
			$newContent = $this->markWords($matches[1][0]);
			for($i = 0; $i < count($matches[0]); $i++) {
				$tag = $matches[2][$i];
				// if the tag is already an <abbr> tag, it will be updated by markWords - nothing to do here
				if (strpos($tag, '<abbr') === FALSE && $tag != '</abbr>') {
					$newContent .= $tag;
				}
				// go deeper and compare tag name to list of tagnames to exclude
				if(preg_match('/(?<=<)\w+(?::\w+)?/', $tag, $match)) {
					$tagName = strtolower($match[0]);
					if(in_array($tagName, $this->config['excludeTags'])) {
						$newContent .= $matches[3][$i];
					} else {
						// add the tag part and replace all abbreviations in it.
						$newContent .= $this->markWords($matches[3][$i]);
					}
				} else {
					$newContent .= $this->markWords($matches[3][$i]);
				}
			}
			return $newContent;
		} else {
			return $this->markWords($content);
		}
	}

	/**
	 * markWords
	 *
	 * @return string
	 */
	private function markWords($textContent) {
		// build pattern for regex to match all abbreviations.
		// \b means "word boundary"
		// word must not start with dot or @
		// word must not end with @
		$pattern = '/(?<![@.])\b(';
		$pattern .= implode('|', array_keys($this->abbreviationEntries));
		$pattern .= ')\b(?!@)/';
		$pattern .= ((int)$this->config['ignoreCase'] == 1) ? 'i' : '';
		$newContent = preg_replace_callback(
			$pattern,
			function($match) {
				$key = ((int)$this->config['ignoreCase'] == 1) ? strtolower($match[0]) : $match[0];
				// a word which is an abbreviation is found - replace it with proper abbr tag
				if($this->abbreviationEntries[$key]) {
					$replacePattern = $this->config['replacePattern'] ? $this->config['replacePattern'] : $this->defaultReplacePattern;
					return sprintf($replacePattern, $this->abbreviationEntries[$key]->getAbbreviation(), $this->abbreviationEntries[$key]->getTerm());
				} else {
					return $match[0];
				}
			},
			$textContent
		);
		return $newContent;
	}
	
}

?>
