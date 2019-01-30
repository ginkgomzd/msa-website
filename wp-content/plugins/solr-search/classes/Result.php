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
	/** @var string */
	protected $status;
	/** @var array */
	protected $statusMessages;
	/**
	 * @return string
	 */
	public function getStatus()
	{
		$this->parseResponse();
		return $this->status;
	}
	/**
	 * @return array
	 */
	public function getStatusMessages()
	{
		$this->parseResponse();
		return $this->statusMessages;
	}
}