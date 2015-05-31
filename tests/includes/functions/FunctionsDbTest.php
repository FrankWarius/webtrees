<?php

/**
 * webtrees: online genealogy
 * Copyright (C) 2015 webtrees development team
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Unit tests for the global functions in the file includes/functions/functions_db.php
 */
class FunctionsDbTest extends PHPUnit_Framework_TestCase {
	/**
	 * Prepare the environment for these tests
	 */
	public function setUp() {
	}

	/**
	 * Test that function fetch_all_links() exists in the correct namespace.
	 */
	public function testFunctionFetchAllLinksExists() {
		$this->assertEquals(function_exists('fetch_all_links'), true);
	}

	/**
	 * Test that function get_source_list() exists in the correct namespace.
	 */
	public function testFunctionGetSourceListExists() {
		$this->assertEquals(function_exists('get_source_list'), true);
	}

	/**
	 * Test that function get_repo_list() exists in the correct namespace.
	 */
	public function testFunctionGetRepoListExists() {
		$this->assertEquals(function_exists('get_repo_list'), true);
	}

	/**
	 * Test that function get_note_list() exists in the correct namespace.
	 */
	public function testFunctionGetNoteListExists() {
		$this->assertEquals(function_exists('get_note_list'), true);
	}

	/**
	 * Test that function search_indis() exists in the correct namespace.
	 */
	public function testFunctionSearchIndisExists() {
		$this->assertEquals(function_exists('search_indis'), true);
	}

	/**
	 * Test that function search_indis_names() exists in the correct namespace.
	 */
	public function testFunctionSearchIndisNamesExists() {
		$this->assertEquals(function_exists('search_indis_names'), true);
	}

	/**
	 * Test that function search_indis_soundex() exists in the correct namespace.
	 */
	public function testFunctionSearchIndisSoundexExists() {
		$this->assertEquals(function_exists('search_indis_soundex'), true);
	}

	/**
	 * Test that function get_recent_changes() exists in the correct namespace.
	 */
	public function testFunctionGetRecentChangesExists() {
		$this->assertEquals(function_exists('get_recent_changes'), true);
	}

	/**
	 * Test that function search_fams() exists in the correct namespace.
	 */
	public function testFunctionSearchFamsExists() {
		$this->assertEquals(function_exists('search_fams'), true);
	}

	/**
	 * Test that function search_fams_names() exists in the correct namespace.
	 */
	public function testFunctionSearchFamsNamesExists() {
		$this->assertEquals(function_exists('search_fams_names'), true);
	}

	/**
	 * Test that function search_sources() exists in the correct namespace.
	 */
	public function testFunctionSearchSourcesExists() {
		$this->assertEquals(function_exists('search_sources'), true);
	}

	/**
	 * Test that function search_notes() exists in the correct namespace.
	 */
	public function testFunctionSearchNotesExists() {
		$this->assertEquals(function_exists('search_notes'), true);
	}

	/**
	 * Test that function search_repos() exists in the correct namespace.
	 */
	public function testFunctionSearchReposExists() {
		$this->assertEquals(function_exists('search_repos'), true);
	}

	/**
	 * Test that function find_rin_id() exists in the correct namespace.
	 */
	public function testFunctionFindRinIdExists() {
		$this->assertEquals(function_exists('find_rin_id'), true);
	}

	/**
	 * Test that function get_common_surnames() exists in the correct namespace.
	 */
	public function testFunctionGetCommonSurnamesExists() {
		$this->assertEquals(function_exists('get_common_surnames'), true);
	}

	/**
	 * Test that function get_top_surnames() exists in the correct namespace.
	 */
	public function testFunctionGetTopSurnamesExists() {
		$this->assertEquals(function_exists('get_top_surnames'), true);
	}

	/**
	 * Test that function get_anniversary_events() exists in the correct namespace.
	 */
	public function testFunctionGetAnniversaryEventsExists() {
		$this->assertEquals(function_exists('get_anniversary_events'), true);
	}

	/**
	 * Test that function get_calendar_events() exists in the correct namespace.
	 */
	public function testFunctionGetCalendarEventsExists() {
		$this->assertEquals(function_exists('get_calendar_events'), true);
	}

	/**
	 * Test that function is_media_used_in_other_gedcom() exists in the correct namespace.
	 */
	public function testFunctionIsMediaUsedInOtherGedcomExists() {
		$this->assertEquals(function_exists('is_media_used_in_other_gedcom'), true);
	}

	/**
	 * Test that function get_user_blocks() exists in the correct namespace.
	 */
	public function testFunctionGetUserBlocksExists() {
		$this->assertEquals(function_exists('get_user_blocks'), true);
	}

	/**
	 * Test that function get_gedcom_blocks() exists in the correct namespace.
	 */
	public function testFunctionGetGedcomBlocksExists() {
		$this->assertEquals(function_exists('get_gedcom_blocks'), true);
	}

	/**
	 * Test that function update_favorites() exists in the correct namespace.
	 */
	public function testFunctionUpdateFavoritesExists() {
		$this->assertEquals(function_exists('update_favorites'), true);
	}

	/**
	 * Test that function get_events_list() exists in the correct namespace.
	 */
	public function testFunctionGetEventsListExists() {
		$this->assertEquals(function_exists('get_events_list'), true);
	}
}
