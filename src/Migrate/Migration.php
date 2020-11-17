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

use Illuminate\Database\Connection;
use Illuminate\Database\Migrations\Migration as MigrationAbstract;
use Illuminate\Database\Schema\MySqlBuilder;

abstract class Migration extends MigrationAbstract {
	/**
	 * The name of the database connection to use.
	 *
	 * @var string|null
	 */
	protected $connection = '';
	/**
	 * @var Connection
	 */
	protected $connector;
	/**
	 * @var MySqlBuilder
	 */
	protected $schema;

	public function setConnector($connector) {
		$this->connector = $connector;
		$this->initSchema();
	}

	private function initSchema() {
		$this->schema = $this->connector->getSchemaBuilder();
	}
}
