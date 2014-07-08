<?php

namespace WorldDirect\Abbreviations\Tests\Unit\Domain\Model;

/***************************************************************
 *  Copyright notice
 *
 *  (c) 2014 Ben Walch <ben.walch@world-direct.at>, World Direct eBusiness Solutions GmbH
 *
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
 * Test case for class \WorldDirect\Abbreviations\Domain\Model\Abbreviation.
 *
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Ben Walch <ben.walch@world-direct.at>
 */
class AbbreviationTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {
	/**
	 * @var \WorldDirect\Abbreviations\Domain\Model\Abbreviation
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = new \WorldDirect\Abbreviations\Domain\Model\Abbreviation();
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function getTypeReturnsInitialValueForInteger() {
		$this->assertSame(
			0,
			$this->subject->getType()
		);
	}

	/**
	 * @test
	 */
	public function setTypeForIntegerSetsType() {
		$this->subject->setType(12);

		$this->assertAttributeEquals(
			12,
			'type',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getTermReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getTerm()
		);
	}

	/**
	 * @test
	 */
	public function setTermForStringSetsTerm() {
		$this->subject->setTerm('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'term',
			$this->subject
		);
	}

	/**
	 * @test
	 */
	public function getAbbreviationReturnsInitialValueForString() {
		$this->assertSame(
			'',
			$this->subject->getAbbreviation()
		);
	}

	/**
	 * @test
	 */
	public function setAbbreviationForStringSetsAbbreviation() {
		$this->subject->setAbbreviation('Conceived at T3CON10');

		$this->assertAttributeEquals(
			'Conceived at T3CON10',
			'abbreviation',
			$this->subject
		);
	}
}
