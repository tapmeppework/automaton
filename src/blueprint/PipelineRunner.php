<?php

namespace tapmeppe\automaton\blueprint;

/**
 * This class is used to create a pipeline of services.
 */
final class PipelineRunner extends Pipeline
{
	private static ?Pipeline $instance = NULL;

	/**
	 * This function is used to get the unique instance of the pipeline.
	 */
	static function instance(): self {
		if (!self::$instance) self::$instance = new self();
		return self::$instance;
	}

	/**
	 * This function is used to run the pipeline.
	 * @param array $data The data to process.
	 */
	function run(array $data)
	{
		$class = '';
		$logs = $output = $start = [];

		try {
			foreach ($this->services as $service) {
				$this->classes[] = $class = get_class($service);
				Logger::breakpoint($class = Logger::declass($class));
				Logger::write("$class - started");

				$start = [
					'time' => Utils::date(),
					'data' => $data,
				];
				// #do not merge
				$data = $service->process($data);
				$end = [
					'time' => Utils::date(),
					'success' => true,
					'data' => $data,
				];
				list($logs1, $output1) = $service->steps();
				$logs[] = compact('class', 'start', 'end') + ['steps' => $logs1];
				$output[] = compact('class', 'start', 'end') + ['steps' => $output1];

				Logger::success($success = "$class - completed");
				Logger::write($success);
			}
		} catch (ServiceException $exception) {
			$reason = Logger::failure($exception);
			$end = [
				'time' => Utils::date(),
				'success' => false,
				'reason' => $reason,
			];
			list($logs1, $output1) = $exception->steps();
			$logs[] = compact('class', 'start', 'end') + ['steps' => $logs1];
			$output[] = compact('class', 'start', 'end') + ['steps' => $output1];
		} catch (\Throwable $throwable) {
			$reason = Logger::failure($throwable);
			$logs[] = $output[] = [
				'class' => $class,
				'start' => $start,
				'end' => [
					'time' => Utils::date(),
					'success' => false,
					'reason' => $reason,
				],
			];
		}

		Logger::write($logs);
		if (Utils::debug()) {
			Logger::breakpoint('Detailled output');
			Logger::inform(Logger::print($output));
		}
	}
}
