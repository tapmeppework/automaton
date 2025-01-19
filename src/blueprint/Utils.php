<?php

namespace tapmeppe\automaton\blueprint;

use Exception;

/**
 * This class is used to administrate the execution of the process.
 */
class Utils
{
	/**
	 * This property is used for debugging purposes. It contains the latest command.
	 */
	private static string $command;

	/**
	 * This function is used to print the latest command.
	 */
	static function command()
	{
		return Logger::inform(self::$command);
	}

	/**
	 * This function is used to get value from the configuration.
	 * @param string $key The key to get the value from.
	 * @param mixed $fallback The fallback value if the key does not exist.
	 * @return mixed The value of the key.
	 */
	static function config(string|int $key, $fallback = null)
	{
		return $_REQUEST[$key] ?? $fallback;
	}

	/**
	 * #alias
	 */
	static function date(string $format = 'Y-m-d H:i:s'): string
	{
		return date($format);
	}

	/**
	 * This function is used to get the debug value from the configuration.
	 */
	static function debug(): bool
	{
		return (bool) self::config('debug', false);
	}

	/**
	 * This function is used to execute a command in the shell.
	 * @param string[] $options The command options to execute.
	 * @return array [success: bool, output: string[], result: number]
	 */
	static final function execute(array $options): array
	{
		// static final function execute(...$options): array > unfortunately destructing the property increases the overall complexity
		// Logger::inform(implode(' ', $options));
		exec(self::$command = implode(' ', $options), $output, $result);
		return [$result == 0, $output, $result];
	}

	static final function isLinux(): bool
	{
		return str_contains(strtolower(PHP_OS), 'linux');
	}

	static final function isWin(): bool
	{
		return str_contains(strtolower(PHP_OS), 'win');
	}

	static final function variables(array $variables): string
	{
		$result = '';

		if (self::isWin()) foreach ($variables as $key => $value) {
			$result .= "set \"$key=$value\" && ";
		}
		elseif (self::isLinux()) foreach ($variables as $key => $value) {
			$result .= "$key=$value ";
		}

		return $result;
	}

	/**
	 * This function is used to forcefully stop the execution of the process.
	 */
	static function shutdown(string $reason, int $code = 1)
	{
		throw new Exception($reason, $code);
	}
}
