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
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class MigrationCreator extends MigrationCreatorAbstract {
	protected $customStubPath = '';

	public function __construct(Filesystem $files) {
		parent::__construct($files, '');
	}

	protected function getStub($table, $create) {
		if (is_null($table)) {
			$stub = $this->files->exists($customPath = $this->customStubPath.'/blank.stub')
				? $customPath
				: $this->stubPath().'/blank.stub';
		} elseif ($create) {
			$stub = $this->files->exists($customPath = $this->customStubPath.'/create.stub')
				? $customPath
				: $this->stubPath().'/create.stub';
		} else {
			$stub = $this->files->exists($customPath = $this->customStubPath.'/update.stub')
				? $customPath
				: $this->stubPath().'/update.stub';
		}

		return $this->files->get($stub);
	}

	/**
	 * Get the path to the stubs.
	 *
	 * @return string
	 */
	public function stubPath() {
		return __DIR__ . '/../Command/Migrate/stubs';
	}

	/**
	 * Get the class name of a migration name.
	 *
	 * @param  string  $name
	 * @return string
	 */
	protected function getClassName($name) {
		return Str::studly($name) . $this->getDatePrefix();
	}
}
