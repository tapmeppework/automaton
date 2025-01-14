<?php

namespace tapmeppe\automaton\blueprint;

/**
 * This class is used to create a pipeline of services.
 */
class Pipeline
{
	/**
	 * @var Service[]
	 */
	protected array $services = [];

	protected array $classes = [];


	protected function __constructor() {
		// TODO - set pipeline locks
	}

	/**
	 * This function is used to add a service to the pipeline.
	 * @param Service[] $services
	 */
	final function add(Service $service): self
	{
		$this->services[] = $service;
		return $this;
	}

	/**
	 * This function is used to get the classes that have already been processed.
	 * @param string $service The class name of the service to check.
	 * @return bool True if the service has already been processed, false otherwise.
	 */
	final function processed (string $service): bool {
		return in_array($service, $this->classes);
		// return array_keys($this->classes, $service) > 0;
	}
}
