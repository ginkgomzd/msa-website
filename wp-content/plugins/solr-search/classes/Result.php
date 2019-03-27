<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 1/30/2019
 * Time: 2:21 AM
 */
use Solarium\Core\Query\Result\QueryType as BaseResult;

class Result extends BaseResult
{
	/**
	 * StatusResult collection when multiple statuses have been requested.
	 *
	 * @var StatusResult[]
	 */
	protected $statusResults = null;

	/**
	 * Status result when the status only for one core as requested.
	 *
	 * @var StatusResult
	 */
	protected $statusResult = null;

	/**
	 * @var bool
	 */
	protected $wasSuccessful = false;

	/**
	 * @var string
	 */
	protected $statusMessage = 'ERROR';

	/**
	 * @return bool
	 */
	public function getWasSuccessful(): bool
	{
		$this->parseResponse();
		return $this->wasSuccessful;
	}

	/**
	 * @return string
	 */
	public function getStatusMessage(): string
	{
		$this->parseResponse();
		return $this->statusMessage;
	}

	/**
	 * Returns the status result objects for all requested core statuses.
	 *
	 * @return StatusResult[]|null
	 */
	public function getStatusResults()
	{
		$this->parseResponse();
		return $this->statusResults;
	}

	/**
	 * Retrives the status of the core, only available when the core was filtered to a core in the status action.
	 *
	 * @return StatusResult|null
	 */
	public function getStatusResult()
	{
		$this->parseResponse();
		return $this->statusResult;
	}
}