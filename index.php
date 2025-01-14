<?php

namespace tapmeppe\automaton;

require __DIR__ . '/vendor/autoload.php';

use tapmeppe\automaton\blueprint\{Logger, PipelineRunner, Resources, Utils};
use tapmeppe\automaton\marketplace\Admin;

/**
 * This block is used to declare the global variables.
 */
define('TAPMEPPE_UMBRELLA', 'tapmeppe');
// ...
define('TAPMEPPE_NAMESPACE', __NAMESPACE__ . '\\');
define('TAPMEPPE_ROOT', Resources::path(__DIR__));
// Load the configuration & arguments
$_REQUEST = (array) Resources::readJSON(TAPMEPPE_ROOT . '/config.json');
array_shift($argv); // Remove the script name from the arguments.
// unset($argv[0]); // Not good enough because the indexes are not reset.
// parse_str(implode('&', $argv), $_REQUEST); // not versatile enough
foreach ($argv as $i => $argument) { // Parse the arguments and overwrite the configuration if necessary
	$argument = explode('=', $argument, 2);
	if (isset($argument[1])) $_REQUEST[$argument[0]] = $argument[1]; //$key1=$value1 $key2=$value2
	else $_REQUEST[$i] = $argument[0]; //$value1 $value2
}
// ...
define('TAPMEPPE_PROFILE', Utils::config('profile', 'extension')); //profile =~ extension folder
define('TAPMEPPE_STORIES', Resources::dir(TAPMEPPE_ROOT . '/stories/' . TAPMEPPE_PROFILE));
define('TAPMEPPE_LOCK', TAPMEPPE_STORIES . '/lock.md');
define('TAPMEPPE_LOGS', Resources::dir(TAPMEPPE_STORIES . '/logs'));


try {
	Logger::start();

	// Check the access to the script.
	if (PHP_SAPI !== 'cli') Utils::shutdown('The script needs to be run from a Command Line Interface (cli).');

	// Load the version
	if (Utils::config(0) == 'version') {
		if ($composer = Resources::readJSON(TAPMEPPE_ROOT . '/composer.json')) Utils::shutdown("v $composer->version", 0);
		else Utils::shutdown('The version could not be retrieved.');
	}

	// Manage the lock - check up
	$lock = Utils::config('lock');
	if ((bool)$lock !== false) {
		if (file_exists(TAPMEPPE_LOCK) && ($time = Resources::read(TAPMEPPE_LOCK))) { // redundant check
			$threshold = time() - 1 * 30 * 60; // threshold is 30 min
			if ($time < $threshold || filemtime(TAPMEPPE_LOCK) < $threshold) { // redundant check - TRUE if running for more than 30 min already
				// TODO - manually check if the lock is still valid by informing the admin that the script is running since 30 min now.
			}
			Utils::shutdown('The script is already running.');
		} else Resources::write(TAPMEPPE_LOCK, time()); //set the lock
	}

	// Load the admin script & run the pipeline
	$admin = 'Admin.php';
	try {
		// '@' is used to suppress the warning message, if the file is not found.
		@require_once(TAPMEPPE_ROOT . '/src/' . TAPMEPPE_PROFILE . "/$admin");
	} catch (\Throwable $_) {
		try {
			require_once TAPMEPPE_ROOT . "/src/template/$admin";
		} catch (\Throwable $_) {
			Utils::shutdown("The '$admin' script could not be loaded.");
		}
	}
	$pipeline = PipelineRunner::instance();
	$pipeline->run(Admin::start($pipeline));
} catch (\Throwable $throwable) {
	Logger::failure($throwable);
} finally {
	Logger::write(str_repeat('-', 10));
	if (file_exists(TAPMEPPE_LOCK)) { // Manage the lock - (redundant) release
		Resources::write(TAPMEPPE_LOCK, '');
		unlink(TAPMEPPE_LOCK);
	}
}
