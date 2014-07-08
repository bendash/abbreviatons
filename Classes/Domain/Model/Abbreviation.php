<?php
namespace WorldDirect\Abbreviations\Domain\Model;


/***************************************************************
 *
 *  Copyright notice
 *
 *  (c) 2014 Ben Walch <ben.walch@world-direct.at>, World Direct eBusiness Solutions GmbH
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
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
 * Abbreviation
 */
class Abbreviation extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * type
	 *
	 * @var integer
	 */
	protected $type = 0;

	/**
	 * term
	 *
	 * @var string
	 */
	protected $term = '';

	/**
	 * abbreviation
	 *
	 * @var string
	 */
	protected $abbreviation = '';

	/**
	 * Returns the type
	 *
	 * @return integer $type
	 */
	public function getType() {
		return $this->type;
	}

	/**
	 * Sets the type
	 *
	 * @param integer $type
	 * @return void
	 */
	public function setType($type) {
		$this->type = $type;
	}

	/**
	 * Returns the term
	 *
	 * @return string $term
	 */
	public function getTerm() {
		return $this->term;
	}

	/**
	 * Sets the term
	 *
	 * @param string $term
	 * @return void
	 */
	public function setTerm($term) {
		$this->term = $term;
	}

	/**
	 * Returns the abbreviation
	 *
	 * @return string $abbreviation
	 */
	public function getAbbreviation() {
		return $this->abbreviation;
	}

	/**
	 * Sets the abbreviation
	 *
	 * @param string $abbreviation
	 * @return void
	 */
	public function setAbbreviation($abbreviation) {
		$this->abbreviation = $abbreviation;
	}

}