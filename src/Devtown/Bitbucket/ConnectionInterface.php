<?php namespace Devtown\Bitbucket;

interface ConnectionInterface{

	public function getConnection($url, $config);

}