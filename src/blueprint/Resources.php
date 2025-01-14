<?php

namespace tapmeppe\automaton\blueprint;

/**
 * This class is used to administrate the directories & files.
 */
final class Resources
{
	/**
	 * This function is used to copy a directory or file.
	 * @param string $source The source directory or file.
	 * @param string $target The target directory or file.
	 * @return bool True if the resource was copied, false otherwise.
	 */
	static function copy(string $source, string $target): bool {
		if (!is_dir($source)) return copy($source, $target);
		
		self::dir($target);
		foreach (scandir($source) as $resource) {
			if (
				$resource == '.' || $resource == '..' || 
				self::copy("$source/$resource", "$target/$resource")
			) continue;
			
			return false; //stop as soon as a copy fails
		}
		return true;
	}

	/**
	 * This function is used to recursively delete a directory or file.
	 * @param string $resource The resource to delete.
	 * @return bool True if the resource was deleted, false otherwise.
	 */
	static function delete(string $resource): bool
	{
		$i = 0;
		do {
			if (self::remove($resource)) return true;
		} while (++$i < 3);

		return false;
	}

	/**
	 * This function is used to create a directory if it does not exist.
	 * @return string The directory path.
	 */
	static function dir(string $directory): string
	{
		if (!is_dir($directory)) mkdir($directory, 0755, true);
		return $directory;
	}

	/**
	 * #alias
	 */
	static function exists(string $resource): bool {
		return file_exists($resource);
		// return is_dir($resource) || is_file($resource);
	}

	static function files(string $directory, ...$flags): array {
		return array_diff(scandir($directory), ['.', '..', ...$flags]);
	}

	static function path(string $path): string
	{
		// return str_replace("\\", '/', $path);
		return str_replace(DIRECTORY_SEPARATOR, '/', $path);
	}

	/**
	 * This function is used to read files.
	 * @param string $file The file to read.
	 * @param string $fallback The fallback content.
	 * @return string The content.
	 */
	static function read(string $file, string $fallback = ''): string
	{
		try {
			if ($content = @file_get_contents($file)) return $content;
		} catch (\Throwable $th) {
		}

		return $fallback;
	}


	/**
	 * This function is used to read JSON files.
	 * @param string $file The JSON file to read.
	 * @param array $fallback The fallback object.
	 * @return Object The JSON as a PHP object.
	 */
	static function readJSON(string $file, array $fallback = []): Object
	{
		try {
			if ($object = json_decode(self::read($file))) return $object;
		} catch (\Throwable $th) {
		}

		return (object)$fallback;
	}

	/**
	 * This function is used to write files.
	 * @param string $file The file to write.
	 * @param string $content The content to write.
	 * @param int $flags The flags to use.
	 * @return bool True if the file was written, false otherwise.
	 */
	static function write(string $file, string $content, int $flags = 0): bool {
		return file_put_contents($file, $content, $flags) !== false;
	}

	// !private

	/**
	 * This function is used to recursively remove a directory or file.
	 * @param string $resource The directory or file to remove.
	 * @return bool True if the resource was removed, false otherwise.
	 */
	private static function remove(string $resource): bool
	{
		if (is_dir($resource)) {
			$resources = array_diff(scandir($resource), ['.', '..']);
			foreach ($resources as $resource1) {
				is_dir("$resource/$resource1") ? self::remove("$resource/$resource1") : unlink("$resource/$resource1");
			}
			return rmdir($resource);
		} else return !is_file($resource) || unlink($resource);
	}
}
