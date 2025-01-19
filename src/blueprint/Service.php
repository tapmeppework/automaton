<?php

namespace tapmeppe\automaton\blueprint;

/**
 * This class is used to create and define a service.
 */
abstract class Service
{
	// #abstract
	abstract function process(array $data): array;


	private array $logs = [];
	private array $output = [];

	/**
	 * This function is used to get all steps.
	 */
	final function steps(): array
	{
		return [$this->logs, $this->output];
	}

	// !protected

	/**
	 * This function is used to abort the process & pipeline prematurely, if necessary.
	 */
	protected function abort(string $reason = ''): array
	{
		$this->step('abort', compact('reason'));
		throw new ServiceException($this->steps(), get_class($this), $reason); // __CLASS__ != get_class($this)
		return []; // This is just to avoid the warnings.
	}

	/**
	 * This function is used to log a service step. This log will be useful to debug the process.
	 * @param string $name The name of the step.
	 * @param mixed $info The additional information to log.
	 * @param bool $detailed If TRUE, the step will be outputted in a detailed way.
	 */
	protected final function step(string $name, mixed $info = [], bool $detailed = false): void
	{
		Logger::warning($temp = Logger::declass(get_class($this)) . " - $name");
		Logger::write($temp);
		// ...
		$this->logs[] = $temp = compact('name', 'info');
		$this->output[] = $detailed ? $temp : $name;
		//stored this way to avoid losing information in case of an unintended overwriting of the key
	}
}
