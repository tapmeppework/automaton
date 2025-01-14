<?php

namespace tapmeppe\automaton\blueprint;

/**
 * This class is used to administrate the execution of the process.
 */
final class Logger
{
	/**
	 * The property represents the current log file stream.
	 */
	private static $stream;
	private static $log;

	/**
	 * #constructor
	 */
	static function start()
	{
		// #start
		// if (self::$stream = fopen(TAPMEPPE_LOGS . '/' . date('Y-m-d') . '.log', 'a')) {
		// 	fwrite(self::$stream, '[' . date('Y-m-d H:i:s') . '] ' . str_repeat('-', 10) . PHP_EOL);
		// }

		// #write
		// if (self::$stream) fwrite(
		// 	self::$stream,
		// 	Utils::date() . "\t" . str_replace(PHP_EOL, "\n\t", trim($message)) . PHP_EOL
		// );

		// #terminate
		// if (self::$stream) {
		// 	fwrite(self::$stream, str_repeat('-', 10) . PHP_EOL);
		// 	fclose(self::$stream);
		// }

		self::$log = TAPMEPPE_LOGS . '/' . date('Y-m-d') . '.log';

		// Keep the latest 30 logs and delete the rest.
		$logs = Resources::files(TAPMEPPE_LOGS);
		$max = count($logs) - Utils::config('logs', 30);
		for ($i = 0; $i < $max; $i++) Resources::delete(TAPMEPPE_LOGS . '/' . $logs[$i]);
	}

	// !secondary

	static function breakpoint(string $message)
	{
		self::color($message, 0, 35);
	}

	/**
	 * This function is used to shorten the class names.
	 */
	static function declass(string $message): string
	{
		return str_replace(TAPMEPPE_NAMESPACE, '@\\', $message);
	}

	static function failure(\Throwable $throwable): string
	{
		self::color($reason = $throwable->getMessage(), 0, 31);
		self::write("ERROR - $reason");
		return $reason;
	}

	static function inform(string $message)
	{
		self::color($message, 0, 34);
	}

	static function output(string $message)
	{
		self::color($message, 0, 37);
		// self::color($message, 0, 36);
	}

	static function print(array $object): string
	{
		return trim(preg_replace(
			[
				'/\s+=>\s+/',
				'/Array\s+\(/',
				'/(\n\s*)\)/',
				'/ {4}/',
				'/\n\s*\n/',
			],
			[
				' ',
				'',
				'',
				' ',
				"\n",
			],
			self::declass(print_r($object, true))
		));
	}

	static function success(string $message)
	{
		self::color($message, 0, 32);
	}

	static function warning(string $message)
	{
		self::color($message, 0, 33);
	}

	static function write(array|string $message)
	{
		Resources::write(self::$log, sprintf(
			"%s\t%s\n",
			Utils::date(),
			is_array($message) ? self::print($message) : str_replace(PHP_EOL, "\n\t", trim($message))
		));
	}

	// !private

	/**
	 * This function is used to add color codes to the message and print it.
	 * @see https://joshtronic.com/2013/09/02/how-to-use-colors-in-command-line-output/
	 * @see https://stackoverflow.com/questions/5762491/how-to-print-color-in-console-using-system-out-println/5762502#5762502
	 * @param string $message
	 * @param int[] $code
	 * @return string the encoded message
	 */
	private static function color(string $message, ...$code)
	{
		echo "\e[" . implode(";", $code) . "m$message\e[0m\n";
	}
}
