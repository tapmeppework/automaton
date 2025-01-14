<?php

namespace tapmeppe\automaton\blueprint;

final class Npm extends Director
{
	function __construct(string $directory) {
		parent::__construct($directory, 'npm');
	}

	/**
	 * @see https://docs.npmjs.com/cli/commands/npm-install
	 */
	final function install(...$spec)
	{
		return $this->execute(['install'] + $spec);
	}

	/**
	 * @see https://docs.npmjs.com/cli/commands/npm-run-script
	 */
	final function run(string $command, ...$args)
	{
		return $this->execute(["run $command --"] + $args);
	}

	/**
	 * @see https://docs.npmjs.com/cli/commands/npm-start
	 */
	final function start(...$args)
	{
		return $this->execute(['start --'] + $args);
	}
}
