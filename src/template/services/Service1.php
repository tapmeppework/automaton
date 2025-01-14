<?php

namespace tapmeppe\automaton\template\services;

use tapmeppe\automaton\blueprint\Service;

class Service1 extends Service
{
	/**
	 * #override
	 */
	function process(array $data): array
	{
		// Example processing: Add a key to the data
		$data['step1'] = 'Processed by Step 1';
		return $data;
	}
}
