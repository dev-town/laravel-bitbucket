<?php

use Mockery as m;

class Bitbucket extends PHPUnit_Framework_TestCase
{

	private function mock()
	{
		// Mock the test response
		$testResponse = m::mock();
		$testResponse->shouldReceive('json')->once()->andReturn(json_encode(array('foo' => 'bar')));

		// Mock the test request
		$testRequest = m::mock();
		$testRequest->shouldReceive('setAuth')->once()->andReturn(true)
					 ->shouldReceive('send')->once()->andReturn($testResponse);

		// Mock the test client
		$testClient = m::mock();
		$testClient->shouldReceive('get')->once()->andReturn($testRequest)
					->shouldReceive('post')->andReturn($testRequest);

		// Mock the test connection
		$client = m::mock('Devtown\Bitbucket\GuzzleClient');
		$client->shouldReceive('getConnection')->once()->andReturn($testClient);

		return $client;
	}

	public function testRepos()
	{
		$r = new Devtown\Bitbucket\Bitbucket($this->mock());
		$data = $r->repos();

		$this->assertContains('bar', $data);
	}


	public function testIssues()
	{
		$r = new Devtown\Bitbucket\Bitbucket($this->mock());
		$data = $r->issues('repo', 'new', 'proposal', 'trivial', 10);

		$this->assertContains('bar', $data);
	}

	/**
     * @expectedException Devtown\Bitbucket\BitBucketException
     */
	public function testStatusFailure()
	{
		$r = new Devtown\Bitbucket\Bitbucket($this->mock());
		$data = $r->issues('repo', 'fail', 'proposal', 'trivial', 10);

		$this->assertContains('bar', $data);
	}

	/**
     * @expectedException Devtown\Bitbucket\BitBucketException
     */
	public function testKindFailure()
	{
		$r = new Devtown\Bitbucket\Bitbucket($this->mock());
		$data = $r->issues('repo', 'new', 'fail', 'trivial', 10);

		$this->assertContains('bar', $data);
	}

	/**
     * @expectedException Devtown\Bitbucket\BitBucketException
     */
	public function testPriorityFailure()
	{
		$r = new Devtown\Bitbucket\Bitbucket($this->mock());
		$data = $r->issues('repo', 'new', 'proposal', 'fail', 10);

		$this->assertContains('bar', $data);
	}
	
	public function testIssueComments()
	{
		$r = new Devtown\Bitbucket\Bitbucket($this->mock());
		$data = $r->issueComments('repo', 1);

		$this->assertContains('bar', $data);
	}

	public function testWiki()
	{
		$r = new Devtown\Bitbucket\Bitbucket($this->mock());
		$data = $r->issueComments('wiki', 1);

		$this->assertContains('bar', $data);
	}


	public function testCreateIssue()
	{
		$r = new Devtown\Bitbucket\Bitbucket($this->mock());
		$data = $r->createIssues('repo', array(
						'title'   	=> 'Test from API',
						'content' 	=> 'Testing creating a issue from the api',
						'status'  	=> 'new',
						'kind'  	=> 'bug',
						'priority'  => 'trivial',
						));

		$this->assertContains('bar', $data);
	}

	public function testCreateIssueComment()
	{
		$r = new Devtown\Bitbucket\Bitbucket($this->mock());
		$data = $r->createIssueComment('repo', 1, array(
						'content' => 'This is an comment created from the API'
						));

		$this->assertContains('bar', $data);
	}
	
}
