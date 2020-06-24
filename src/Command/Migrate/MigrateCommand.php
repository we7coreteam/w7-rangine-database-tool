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

use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Console\Input\InputOption;
use W7\Console\Command\ConfirmTrait;
use W7\Core\Facades\DB;
use W7\Core\Facades\Event;
use W7\DatabaseTool\Migrate\Migrator;

class MigrateCommand extends MigrateCommandAbstract {
	use ConfirmTrait;

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'run the database migrations';

	protected function configure() {
		$this->addOption('--database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use', 'default');
		$this->addOption('--pretend', null, InputOption::VALUE_NONE, 'Dump the SQL queries that would be run');
		$this->addOption('--seed', null, InputOption::VALUE_NONE, 'Indicates if the seed task should be re-run');
		$this->addOption('--step', null, InputOption::VALUE_OPTIONAL, 'Force the migrations to be run so they can be rolled back individually');
		$this->addOption('--force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production');
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

		$this->prepareDatabase();

		// Next, we will check to see if a path option has been defined. If it has
		// we will use the path relative to the root of this installation folder
		// so that migrations may be run for any path within the applications.
		$this->migrator->setOutput($this->output)
			->run($this->getMigrationPaths(), [
				'pretend' => $this->option('pretend'),
				'step' => $this->option('step'),
			]);

		// Finally, if the "seed" option has been given, we will re-run the database
		// seed task to re-populate the database, which is convenient when adding
		// a migration and a seed at the same time, as it is only this command.
		if ($this->option('seed') && ! $this->option('pretend')) {
			$this->call('seed:seed', ['--force' => true]);
		}
	}

	/**
	 * Prepare the migration database for running.
	 *
	 * @return void
	 */
	protected function prepareDatabase() {
		$this->migrator->setConnection($this->option('database'));

		if (! $this->migrator->repositoryExists()) {
			$this->call('migrate:install', array_filter([
				'--database' => $this->option('database'),
			]));
		}
	}
}
