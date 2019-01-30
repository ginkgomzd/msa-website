<?php
/**
 * Created by PhpStorm.
 * User: ljubi
 * Date: 1/30/2019
 * Time: 2:20 AM
 */
use Solarium\Core\Query\AbstractResponseParser as ResponseParserAbstract;
use Solarium\Core\Query\ResponseParserInterface as ResponseParserInterface;

class ResponseParser extends ResponseParserAbstract implements ResponseParserInterface
{
	/**
	 * Get result data for the response
	 *
	 * @param Result
	 * @return array
	 */
	public function parse($result)
	{
		$data = $result->getData();
		$result = $this->addHeaderInfo($data, []);
		$result['status'] = $data['status'];
		$result['statusMessages'] = $data['statusMessages'];
		return $result;
	}
}