<?php

namespace tapmeppe\automaton\blueprint;

final class NPM extends Director
{
	function __construct(string $directory)
	{
		parent::__construct($directory, 'npm');
	}

	/**
	 * @see https://docs.npmjs.com/cli/commands/npm-install
	 */
	final function install(array $spec = [])
	{
		return $this->process(array_merge(['install'], $spec));
	}

	/**
	 * @see https://docs.npmjs.com/cli/commands/npm-run-script
	 */
	final function run(string $command, array $args = [])
	{
		return $this->process(array_merge(["run $command --"], $args));
	}

	/**
	 * @see https://docs.npmjs.com/cli/commands/npm-start
	 */
	final function start(array $args = [])
	{
		return $this->process(array_merge(['start --'], $args));
	}
}
