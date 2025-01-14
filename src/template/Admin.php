<?php

namespace tapmeppe\automaton\extension;

/**
 * This is a sample script that demonstrates the usage of the pipeline.
 * To extend the framework use the directory 'src/extension'.
 */

use tapmeppe\automaton\blueprint\Pipeline;
use tapmeppe\automaton\template\services\{Service1, Service2};

/**
 * This class is used to administrate the execution of the process.
 */
final class Admin
{
	/**
	 * This function is used to start the process.
	 * @param Pipeline $pipeline The pipeline to run.
	 * @return array The initial data to process.
	 */
	static function start(Pipeline $pipeline): array
	{
		$pipeline
			->add(new Service1())
			->add(new Service2());

		return ['initial' => 'data'];
	}
}
