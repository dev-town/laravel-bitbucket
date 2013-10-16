<?php namespace Devtown\Bitbucket;

use Guzzle\Http\Client;

class GuzzleConnection implements ConnectionInterface
{

	public function getConnection($url, $config)
	{
		$client = new Client($url, $config);
		return $client;
	}
}