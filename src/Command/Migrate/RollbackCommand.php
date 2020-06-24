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
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Symfony\Component\Console\Input\InputOption;
use W7\Console\Command\ConfirmTrait;
use W7\Core\Facades\DB;
use W7\Core\Facades\Event;
use W7\DatabaseTool\Migrate\Migrator;

class RollbackCommand extends MigrateCommandAbstract {
	use ConfirmTrait;

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'rollback the last database migration';

	protected function configure() {
		$this->addOption('--database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use', 'default');
		$this->addOption('--force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production');
		$this->addOption('--path', null, InputOption::VALUE_OPTIONAL | InputOption::VALUE_IS_ARRAY, 'The path to the migrations files to be executed');
		$this->addOption('--realpath', null, InputOption::VALUE_NONE, 'Indicate any provided migration file paths are pre-resolved absolute paths');
		$this->addOption('--pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run');
		$this->addOption('--step', null, InputOption::VALUE_OPTIONAL, 'Force the migrations to be run so they can be rolled back individually');
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	protected function handle($options) {
		if (! $this->confirmToProceed()) {
			return;
		}

		DB::setDefaultConnection($options['database']);
		$this->migrator = new Migrator(new DatabaseMigrationRepository(DB::getFacadeRoot(), MigrateCommandAbstract::MIGRATE_TABLE_NAME), DB::getFacadeRoot(), new Filesystem(), Event::getFacadeRoot());
		$this->migrator->setConnection($this->option('database'));

		$this->migrator->setOutput($this->output)->rollback(
			$this->getMigrationPaths(),
			[
				'pretend' => $this->option('pretend'),
				'step' => (int)$this->option('step'),
			]
		);
	}
}
