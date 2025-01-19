<?php

namespace tapmeppe\automaton\blueprint;

final class Bash extends Director
{
	function __construct(string $directory)
	{
		parent::__construct($directory, 'bash');
	}
}
