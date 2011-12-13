<?php

namespace Application\Controller;

use Application\Model\Saved\Engine,
	Zend\Http\Client,
	Zend\Mvc\MvcEvent;

class FolderController extends SearchController
{
	protected $id = "folder";
	
	public function init(MvcEvent $e)
	{
		// make the username the query
		
		$this->request->setParam("query", $this->request->getSessionData('username'));
		
		parent::init($e);
	}
	
	protected function getEngine()
	{
		return new Engine();
	}
	
	public function indexAction()
	{
		$this->request->setSessionData("return", $this->request->getParam("return"));
		
		$params = array (
			'controller' => 'folder',
			'action' => 'results',
			'username' => $this->request->getSessionData('username')
		);
		
		$url = $this->request->url_for($params);
		
		return $this->redirect()->toUrl($url);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	public function citation()
	{
		parent::results();
		
		$style = $this->request->getParam("style", false, "mla");

		$items = array();
		
		$results = $this->response->get("results");
		
		// header("Content-type: application/json");
		
		$x = 1;
		
		foreach ( $results->getRecords() as $result )
		{
			$id = "ITEM=$x";
			
			$record = $result->getXerxesRecord()->toCSL();
			$record["id"] = $id;
			
			$items[$id] = $record;
			$x++;
		}
		
		$json = json_encode(array("items" => $items));
		
		// header("Content-type: application/json"); echo $json; exit;
		
		$url = "http://127.0.0.1:8085?responseformat=html&style=$style";
		
		$client = new Client();
		$client->setUri($url);
		$client->setHeaders("Content-type: application/json");
		$client->setHeaders("Expect: nothing");
		$client->setRawData($json)->setEncType('application/json');
		
		$response = $client->request('POST')->getBody();;
		
		echo $response;
		exit;
	}

	protected function currentParams()
	{
		// unset query for username
		
		$params = parent::currentParams();
		$params["username"] = $params["query"];
		unset($params["query"]);
		
		return $params;
	}
}
