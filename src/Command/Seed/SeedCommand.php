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

namespace W7\DatabaseTool\Command\Seed;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\Console\Input\InputOption;
use W7\Console\Command\CommandAbstract;
use W7\Console\Command\ConfirmTrait;
use W7\Core\Exception\CommandException;
use W7\Core\Facades\DB;

class SeedCommand extends CommandAbstract {
	use ConfirmTrait;

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'seed the database with records';

	protected function configure() {
		$this->addOption('--class', null, InputOption::VALUE_OPTIONAL, 'The class name of the root seeder', 'DatabaseSeeder');
		$this->addOption('--database', null, InputOption::VALUE_OPTIONAL, 'The database connection to seed', 'default');
		$this->addOption('--force', null, InputOption::VALUE_NONE, 'Force the operation to run when in production');
	}

	/**
	 * @param $options
	 * @throws CommandException
	 */
	public function handle($options) {
		if (empty($options['class'])) {
			throw new CommandException('option class error');
		}
		$class = ucfirst($options['class']);
		if (!class_exists($class)) {
			throw new CommandException('option class ' . $class . ' not found');
		}

		if (! $this->confirmToProceed()) {
			return;
		}

		DB::setDefaultConnection($this->option('database'));

		Model::unguarded(function () use ($class) {
			(new $class)->run();
		});

		$this->output->success('Database seeding completed successfully.');
	}
}
