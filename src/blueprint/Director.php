<?php

namespace tapmeppe\automaton\blueprint;

/**
 * This class represents a directory manager.
 */
abstract class Director
{
	protected array $goto = [];

	/**
	 * @param string $directory The framework or module directory.
	 */
	function __construct(protected string $directory, protected string $manager)
	{
		$pieces = explode(':', $directory);
		if (count($pieces) > 1) $this->goto = ["$pieces[0]:", '&&']; //TRUE if absolute path on Windows OS
		$this->goto[] = "cd \"$directory\"";
		$this->goto[] = '&&';
	}

	/**
	 * This function is used to execute a command in the shell.
	 * @return array [success: bool, output: string[], result: number]
	 */
	protected final function execute(array $options)
	{
		return Utils::execute(array_merge($this->goto, [$this->manager], $options));
	}
}
