<?php

namespace tapmeppe\automaton\blueprint;

class Git extends Director
{
	/**
	 * @param string $directory The directory where the repository has been or will be cloned.
	 * @param string $repository The repository URL (https or ssh).
	 * @param bool $enforce If true, the repository will be added as option during commands such as `git pull`, `git push`.
	 * 											This is helpful when the repository is not publicly available and the credentials should implicitly be used.
	 */
	function __construct(
		string $directory,
		protected string $repository = '',
	) {
		parent::__construct($directory, 'git');
	}

	/**
	 * This function is used to register the changes in the repository.
	 * @see https://git-scm.com/docs/git-add
	 */
	function add(array $options = [])
	{
		return $this->process(array_merge(['add'], $options));
	}
	function addAll(array $options = [])
	{
		return $this->add(array_merge(['--all'], $options));
	}

	/**
	 * This function is used to get the repository branches.
	 * @return array [success: bool, output: string[], result: number]
	 * @see https://git-scm.com/docs/git-branch
	 */
	function branch(array $options = [])
	{
		return $this->process(array_merge(['branch'], $options));
	}

	/**
	 * This function is used to clone the repository.
	 * @return array [success: bool, output: string[], result: number]
	 * @see https://git-scm.com/docs/git-clone
	 */
	function clone(array $options = [])
	{
		return $this->process(array_merge(['clone'], $options, ['.']));
	}

	/**
	 * This function is used to register the changes in the repository.
	 * @see https://git-scm.com/docs/git-commit
	 */
	function commit(array $options = [])
	{
		return $this->process(array_merge(['commit'], $options));
	}
	function commitMsg(string $message, array $options = [])
	{
		return $this->commit(array_merge(['--message', "\"$message\""], $options));
	}

	/**
	 * This function is used to get the hash for the current commit of the repository.
	 * @return string|NULL The hash of the current commit or NULL if the hash could not be retrieved.
	 * @see https://betterstack.com/community/questions/how-to-get-has-for-current-commit/
	 * @see https://git-scm.com/docs/git-rev-parse
	 * @see https://git-scm.com/docs/git-log
	 */
	function hash(): string|NULL
	{
		list($success, $output) = $this->process(['rev-parse HEAD']);
		// list($success, $output) = $this->process(['log --pretty=format:"%H" -n 1']);
		// list($success, $output) = $this->process(['log --pretty=format:"%H" -1']);
		return $success ? $output[0] : NULL;
	}

	function isEmpty(): bool
	{
		return empty(Resources::files($this->directory, '.git'));
	}

	function isRepository(): bool
	{
		return file_exists($this->directory . '/.git') && $this->status()[0];
	}

	/**
	 * This function is used to pull the repository.
	 * @see https://git-scm.com/docs/git-log
	 * @see https://git-scm.com/book/en/v2/Git-Basics-Viewing-the-Commit-History
	 */
	function log(array $options = [])
	{
		return $this->process(array_merge(['log'], $options));
	}

	/**
	 * This function is used to pull the repository.
	 * @see https://git-scm.com/docs/git-pull
	 */
	function pull(array $options = [])
	{
		return $this->process(array_merge(['pull'], $options));
	}

	/**
	 * This function is used to push the repository.
	 * @see https://git-scm.com/docs/git-push
	 */
	function push(array $options = [])
	{
		return $this->process(array_merge(['push'], $options));
	}

	function repository(): string
	{
		return $this->repository;
	}

	/**
	 * This function is used to get the current state of the repository.
	 * @see https://git-scm.com/docs/git-status
	 */
	function status(array $options = [])
	{
		return $this->process(array_merge(['status'], $options));
	}
}
