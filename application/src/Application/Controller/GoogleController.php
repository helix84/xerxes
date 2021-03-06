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

use Application\Model\Google\Engine;
use Application\View\Helper\Google as SearchHelper;

class GoogleController extends SearchController
{
	protected $id = "google";

	protected function init()
	{
		parent::init();
	
		$this->helper = new SearchHelper($this->event, $this->id, $this->engine);
	}	
	
	protected function getEngine()
	{
		return new Engine();
	}
}
