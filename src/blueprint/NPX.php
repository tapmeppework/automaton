<?php

namespace tapmeppe\automaton\blueprint;

final class NPX extends Director
{
	function __construct(string $directory)
	{
		parent::__construct($directory, 'npx');
	}
}
