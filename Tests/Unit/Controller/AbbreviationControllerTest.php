<?php
namespace WorldDirect\Abbreviations\Tests\Unit\Controller;
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
 * Test case for class WorldDirect\Abbreviations\Controller\AbbreviationController.
 *
 * @author Ben Walch <ben.walch@world-direct.at>
 */
class AbbreviationControllerTest extends \TYPO3\CMS\Core\Tests\UnitTestCase {

	/**
	 * @var \WorldDirect\Abbreviations\Controller\AbbreviationController
	 */
	protected $subject = NULL;

	protected function setUp() {
		$this->subject = $this->getMock('WorldDirect\\Abbreviations\\Controller\\AbbreviationController', array('redirect', 'forward', 'addFlashMessage'), array(), '', FALSE);
	}

	protected function tearDown() {
		unset($this->subject);
	}

	/**
	 * @test
	 */
	public function listActionFetchesAllAbbreviationsFromRepositoryAndAssignsThemToView() {

		$allAbbreviations = $this->getMock('TYPO3\\CMS\\Extbase\\Persistence\\ObjectStorage', array(), array(), '', FALSE);

		$abbreviationRepository = $this->getMock('WorldDirect\\Abbreviations\\Domain\\Repository\\AbbreviationRepository', array('findAll'), array(), '', FALSE);
		$abbreviationRepository->expects($this->once())->method('findAll')->will($this->returnValue($allAbbreviations));
		$this->inject($this->subject, 'abbreviationRepository', $abbreviationRepository);

		$view = $this->getMock('TYPO3\\CMS\\Extbase\\Mvc\\View\\ViewInterface');
		$view->expects($this->once())->method('assign')->with('abbreviations', $allAbbreviations);
		$this->inject($this->subject, 'view', $view);

		$this->subject->listAction();
	}
}
