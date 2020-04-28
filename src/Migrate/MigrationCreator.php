<?php

/**
 * Rangine database Tool
 *
 * (c) We7Team 2019 <https://www.rangine.com>
 *
 * document http://s.w7.cc/index.php?c=wiki&do=view&id=317&list=2284
 *
 * visited https://www.rangine.com for more details
 */

namespace W7\DatabaseTool\Migrate;

use Illuminate\Database\Migrations\MigrationCreator as MigrationCreatorAbstract;

class MigrationCreator extends MigrationCreatorAbstract {
	/**
	 * Get the path to the stubs.
	 *
	 * @return string
	 */
	public function stubPath() {
		return __DIR__ . '/../Command/Migrate/stubs';
	}

	/**
	 * Populate the place-holders in the migration stub.
	 *
	 * @param  string  $name
	 * @param  string  $stub
	 * @param  string|null  $table
	 * @return string
	 */
	protected function populateStub($name, $stub, $table) {
		$stub = str_replace('DummyClass', $this->getClassName($name) . $this->getDatePrefix(), $stub);

		// Here we will replace the table place-holders with the table specified by
		// the developer, which is useful for quickly creating a tables creation
		// or update migration from the console instead of typing it manually.
		if (! is_null($table)) {
			$stub = str_replace('DummyTable', $table, $stub);
		}

		return $stub;
	}
}
