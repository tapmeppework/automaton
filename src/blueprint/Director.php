<?php

namespace tapmeppe\automaton\blueprint;

/**
 * This class represents a directory executable.
 */
class Director
{
	protected array $goto = [];

	/**
	 * @param string $directory The framework or module directory.
	 */
	function __construct(public string $directory, public string $executable)
	{
		$pieces = explode(':', $directory);
		if (count($pieces) > 1) $this->goto = ["$pieces[0]: &&"]; //TRUE if absolute path on Windows OS
		$this->goto[] = "cd \"$directory\" &&";
	}

	/**
	 * This function is used to process a command in the shell.
	 * @return array [success: bool, output: string[], result: number]
	 */
	final function process(array $options)
	{
		return $this->execute(array_merge([$this->executable], $options));
	}

	/**
	 * This function is used to execute a command in the shell.
	 * @return array [success: bool, output: string[], result: number]
	 */
	final function execute(array $options)
	{
		return Utils::execute(array_merge($this->goto, $options));
	}
}
