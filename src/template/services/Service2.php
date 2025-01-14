<?php

namespace tapmeppe\automaton\template\services;

use tapmeppe\automaton\blueprint\Service;

class Service2 extends Service
{
	/**
	 * #override
	 */
	public function process(array $data): array
	{
		// Example processing: Add another key
		$data['step2'] = 'Processed by Step 2';
		return $data;
	}
}
