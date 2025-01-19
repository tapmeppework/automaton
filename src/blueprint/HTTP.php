<?php

namespace tapmeppe\automaton\blueprint;

use Exception;

/**
 * This class is used to administrate the execution of the process.
 */
class HTTP
{
	/**
	 * This function is used to fetch resources from a remote server via HTTP.
	 * @param $url
	 * @param $options
	 * @param $success
	 * @param $failure The callback function invoked in case of a fetch error.
	 * @see https://serpapi.com/blog/how-to-use-curl-in-php/
	 */
	static function fetch(string $url, array $options, callable $success, callable $failure)
	{
		$curl = curl_init($url);
		curl_setopt_array(
			$curl,
			[CURLOPT_RETURNTRANSFER => true] + $options
		);
		$response = curl_exec($curl);
		if (curl_errno($curl)) $failure(curl_error($curl));
		else $success($response, $curl);
		curl_close($curl);
	}

	/**
	 * This function is used to retrieve resources from a remote server via HTTP with authentication.
	 * @param $url
	 * @param $auth encoded authenticator
	 * @param $options
	 * @param $success
	 * @param $failure The callback function invoked in case of a fetch error.
	 */
	static function retrieve(string $url, string $auth, array $options, callable $success, callable $failure)
	{
		self::fetch(
			$url,
			[
				CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
				CURLOPT_USERPWD => base64_decode($auth),
			] + $options,
			$success,
			$failure
		);
	}

	/**
	 * The following options must be used for this function to work properly:
	 * - CURLOPT_NOBODY => true, //HEAD request
	 * - CURLOPT_HEADER => true, //prepend the response header to the body
	 * @see https://gist.github.com/surferxo3/522e9882e9f00b47de8e72c553232c05 extract header from response
	 */
	static function headerString2Array(string $response, \CurlHandle $curl): array
	{
		$response = substr($response, 0, curl_getinfo($curl, CURLINFO_HEADER_SIZE));
		// ...
		$response = substr($response, 0, strpos($response, "\r\n\r\n"));
		$headers = [];
		foreach (explode("\r\n", $response) as $i => $line) {
			if ($i == 0) $headers['http_code'] = $line;
			else {
				list($key, $value) = explode(': ', $line);
				$headers[$key] = $value;
			}
		}

		return $headers;
	}
}