<?php

namespace tapmeppe\automaton\blueprint;

class Database
{
	protected \mysqli $server;

	function __construct(
		protected string $user,
		protected string $password,
		protected string $name,
		protected string $host = 'localhost',
	) {
		$this->server = new \mysqli($host, $user, $password, $name);
		if ($this->server->connect_errno) Utils::shutdown(
			"The connection to the database has failed!\n" . $this->server->connect_error,
			$this->server->connect_errno
		);
	}

	function __destruct()
	{
		try {
			$this->server->close();
		} catch (\Throwable $th) {
		}
	}

	/**
	 * This function is used to execute a query.
	 */
	final function dump(...$tables)
	{
		// TODO - dump the database
	}

	/**
	 * This function is used to get the database server.
	 */
	final function server(): \mysqli
	{
		return $this->server;
	}

	/**
	 * This function is used to execute a query.
	 */
	final function query(string $query): \mysqli_result|bool
	{
		return $this->server->query($query);
	}
}
