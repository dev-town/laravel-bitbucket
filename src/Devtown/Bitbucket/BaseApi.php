<?php namespace Devtown\Bitbucket;


class BaseApi
{
	protected $user;
	protected $password;
	protected $client;
	protected $request;
	protected $apiUrl = 'https://api.bitbucket.org/';
	protected $apiVersion = '1.0';

	public function __construct($client)
	{
		$this->client = $client->getConnection($this->apiUrl.'/{version}/', array(
			'version' => $this->apiVersion
		));
	}

	/**
	 * Save the auth details
	 *
	 * @param  string $user
	 * @param  string $password
	 * @return VOID
	 */
	public function auth($user, $password)
	{
		$this->user 	= $user;
		$this->password = $password;
	}


	/**
	 * Make the API call
	 *
	 * @return array
	 */
	protected function send()
	{
		$this->request->setAuth($this->user, $this->password);
		$response = $this->request->send();
		return $response->json();
	}

	/**
	 * Display the API Call URL
	 *
	 * @return  string
	 */
	protected function showUrl()
	{
		return $this->request->getUrl();
	}

}