<?php

/*
 * This file is part of Xerxes.
 *
 * (c) California State University <library@calstate.edu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Controller;

use Xerxes\Mvc\ActionController;
use Application\Model\DataMap\Databases;

class DatabasesController extends ActionController
{
	public function indexAction()
	{
		$params = array('controller' => 'databases');
		
		return $this->redirectTo($params);
	}
	
	public function alphabeticalAction()
	{
		$databases = new Databases();
		
		$this->response->setVariable('databases', $databases->processDatabases());
			
		$this->response->setView('databases/alphabetical.xsl');
		
		return $this->response;
	}


}
