<?php
namespace tapmeppe\automaton\blueprint;

class Zip extends Director {
	function __construct(string $directory) {
		parent::__construct($directory, 'zip');
	}
}