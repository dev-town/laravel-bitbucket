<?php namespace Devtown\Bitbucket;

use Guzzle\Http\Client;

class Bitbucket extends BaseApi
{
	private $statuses 	= array('new','open','resolved','on hold','invalid','duplicate','wontfix');
	private $kinds 		= array('proposal', 'bug', 'enhancement', 'task');
	private $priorities = array('trivial', 'minor', 'major', 'critical', 'blocker');


	/**
	 * Get a list of repos
	 * 
	 * $data = Bitbucket::repos();
	 *
	 * @return Array
	 */
	public function repos()
	{
		$this->request = $this->client->get('user/repositories');
		return $this->send();
	}


	/**
	 * Get the issues from a repository
	 * 
	 * $data = Bitbucket::issues('repo');
	 * 
	 * @param  string $repo
	 * @param  string $status
	 * @param  string $kind
	 * @param  string $priority
	 * @param  integer $start
	 * @return Array
	 */
	public function issues($repo, $status = null, $kind = null, $priority = null, $start = null)
	{
		$params = array();

		if(isset($status) && !in_array($status, $this->statuses))
			throw new BitBucketException("Bitbucket Status is invalid");

		if(isset($kind) && !in_array($kind, $this->kinds))
			throw new BitBucketException("Bitbucket Kind is invalid");

		if(isset($priority) && !in_array($priority, $this->priorities))
			throw new BitBucketException("Bitbucket priority is invalid");

		// Set any params
		if(isset($status)) $params['status'] 		= $status;
		if(isset($kind)) $params['kind'] 			= $kind;
		if(isset($priority)) $params['priority'] 	= $priority;
		if(isset($start)) $params['start'] 			= (int) $start;


		$queryString = http_build_query($params);

		$url = "repositories/{$this->user}/{$repo}/issues?".$queryString;
		$this->request = $this->client->get($url);

		return $this->send();
	}


	/**
	 * Get a list of issue comments
	 *
	 * $data = Bitbucket::issueComments('repo', 1);
	 *
	 * @return Array
	 */
	public function issueComments($repo, $id)
	{
		$this->request = $this->client->get("repositories/{$this->user}/{$repo}/issues/{$id}/comments");
		return $this->send();
	}


	/**
	 * Get the Wiki content
	 *
	 * $data = Bitbucket::wiki('repo');
	 *
	 * @param  string $repo
	 * @param  string $page
	 * @return Array
	 */
	public function wiki($repo, $page = 'Home')
	{
		$this->request = $this->client->get("repositories/{$this->user}/{$repo}/wiki/{$page}/");
		return $this->send();
	}


	/**
	 * Create an issue
	 *
	 * 	$data = Bitbucket::createIssues('repo', array(
	 *	'title'   	=> 'Test from API',
	 *	'content' 	=> 'Testing creating a issue from the api',
	 *	'status'  	=> 'new',
	 *	'kind'  	=> 'bug',
	 *	'priority'  => 'trivial',
	 *	));
	 * 
	 * @param  string $repo
	 * @param  array  $data
	 * @return Array
	 */
	public function createIssues($repo, $data = array())
	{
		if(!in_array($data['status'], $this->statuses))
			throw new BitBucketException("Bitbucket Status is invalid");

		if(!in_array($data['kind'], $this->kinds))
			throw new BitBucketException("Bitbucket Kind is invalid");

		if(!in_array($data['priority'], $this->priorities))
			throw new BitBucketException("Bitbucket priority is invalid");

		if(!isset($data['title']) || empty($data['title']))
			throw new BitBucketException("Bitbucket title is required");

		if(!isset($data['content']) || empty($data['content']))
			throw new BitBucketException("Bitbucket content is required");

		$url = "repositories/{$this->user}/{$repo}/issues";
		$this->request = $this->client->post($url, null, array(
				'status' 	=> $data['status'],
				'kind' 		=> $data['kind'],
				'priority' 	=> $data['priority'],
				'title' 	=> $data['title'],
				'content' 	=> $data['content'],
			));
		return $this->send();
	}


	/**
	 * Create an comment on an issue
	 *
	 * 	$data = Bitbucket::createIssueComment(repo', 2, array(
	 *		'content' => 'This is an comment created from the API'
	 *	));
	 * 
	 * @param  string $repo
	 * @param  integer $id
	 * @param  array  $data
	 * @return Array
	 */
	public function createIssueComment($repo, $id, $data = array())
	{
		if(!isset($data['content']) || empty($data['content']))
			throw new BitBucketException("Bitbucket content is required");

		$url = "repositories/{$this->user}/{$repo}/issues/{$id}/comments/";
		$this->request = $this->client->post($url, null, array(
				'content' 	=> $data['content'],
			));
		return $this->send();
	}
}
