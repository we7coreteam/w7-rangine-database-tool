<?php

/**
 * This file is part of Rangine
 *
 * (c) We7Team 2019 <https://www.rangine.com/>
 *
 * document http://s.w7.cc/index.php?c=wiki&do=view&id=317&list=2284
 *
 * visited https://www.rangine.com/ for more details
 */

namespace W7\DatabaseTool\Seed;

abstract class Seeder {
	abstract public function run();

	public function call($classes) {
		$classes = (array)$classes;

		foreach ($classes as $class) {
			$class = new $class();
			$class->run();
		}
	}
}
