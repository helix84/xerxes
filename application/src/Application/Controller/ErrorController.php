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

class ErrorController extends ActionController
{
    public function displayError(\Exception $e)
    {
    	$code = $e->getCode();
    	$message = $e->getMessage();
    	$line = $e->getFile() . ', line ' . $e->getLine();
    	$trace = $e->getTraceAsString();
    	
    	// send the error to the log
    	
    	trigger_error("Xerxes Error ($line): $message");
    	
    	$error = array();
    	$error['code'] = $code;
    	$error['message'] = $message;
    	
    	// only include location and trace if reporting is turned on
    	
    	if ( error_reporting() == E_ALL )
    	{
    		$error['line'] = $line;
    		$error['trace'] = $trace;
    	}
    	
    	$this->response->setVariable('error', $error);
    	$this->response->setView('error/index.xsl');
    	
    	return $this->response;
    }
}
