<?php

namespace tapmeppe\automaton\blueprint;

/**
 * This class is used to handle exceptions that occur during the execution of a service.
 */
final class ServiceException extends \Exception
{
	/**
	 * @param array $steps The steps of the service that have already been started.
	 * @param string $service The name of the service that has been aborted.
	 * @param string $message The additional message to display.
	 */
	public function __construct(private array $steps, string $service, string $message = '')
	{
		parent::__construct(rtrim(sprintf(
			"The service '%s' had to be aborted.\n%s",
			Logger::declass($service),
			$message
		)));
	}

	/**
	 * This function is used to get all steps.
	 */
	final function steps(): array
	{
		return $this->steps;
	}
}