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

namespace W7\DatabaseTool\Command\Migrate;

use Illuminate\Filesystem\Filesystem;
use W7\Command\Support\Composer;
use W7\Console\Command\CommandAbstract;
use W7\DatabaseTool\Migrate\Migrator;

abstract class MigrateCommandAbstract extends CommandAbstract {
	const MIGRATE_TABLE_NAME = 'migration';

	/**
	 * @var Migrator
	 */
	protected $migrator;

	/**
	 * @var Composer
	 */
	protected $composer;

	public function __construct(string $name = null) {
		parent::__construct($name);
		$this->composer = new Composer(new Filesystem(), BASE_PATH);
	}

	/**
	 * Get all of the migration paths.
	 *
	 * @return array
	 */
	protected function getMigrationPaths() {
		// Here, we will check to see if a path option has been defined. If it has we will
		// use the path relative to the root of the installation folder so our database
		// migrations may be run for any customized path from within the application.
		if ($this->input->hasOption('path') && $this->input->getOption('path')) {
			return collect($this->input->getOption('path'))->map(function ($path) {
				return ! $this->usingRealPath()
								? BASE_PATH.DIRECTORY_SEPARATOR.$path
								: $path;
			})->all();
		}

		return array_merge(
			$this->migrator->paths(),
			[$this->getMigrationPath()]
		);
	}

	/**
	 * Determine if the given path(s) are pre-resolved "real" paths.
	 *
	 * @return bool
	 */
	protected function usingRealPath() {
		return $this->input->hasOption('realpath') && $this->input->getOption('realpath');
	}

	/**
	 * Get the path to the migration directory.
	 *
	 * @return string
	 */
	protected function getMigrationPath() {
		return BASE_PATH.DIRECTORY_SEPARATOR.'database/migrations';
	}
}
