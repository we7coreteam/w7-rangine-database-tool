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
use Symfony\Component\Console\Input\InputOption;
use W7\Core\Facades\DB;

class InstallCommand extends MigrateCommandAbstract {
	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'create the migration repository';

	protected function configure() {
		$this->addOption('--database', null, InputOption::VALUE_OPTIONAL, 'The database connection to use', 'default');
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	protected function handle($options) {
		DB::setDefaultConnection($options['database']);
		$repository = new DatabaseMigrationRepository(DB::getFacadeRoot(), MigrateCommandAbstract::MIGRATE_TABLE_NAME);
		$repository->setSource($this->input->getOption('database'));

		$repository->createRepository();

		$this->output->info('Migration table created successfully.');
	}
}
