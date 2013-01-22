<?php

namespace Xerxes\Mvc;

use Symfony\Component\HttpFoundation,
	Xerxes\Utility\Parser,
	Xerxes\Utility\Registry,
	Xerxes\Utility\Xsl;

/**
 * Response
 * 
 * @author David Walker
 * @copyright 2013 California State University
 * @link http://xerxes.calstate.edu
 * @license 
 * @version
 * @package Xerxes
 */

class Response extends HttpFoundation\Response
{
	private $_vars = array(); // variables
	private $_script_path; // path to the distro script
	private $_view_dir; // view directory
	private $_view; // view file
	
	/**
	 * Set variable
	 * 
	 * @param string $name
	 * @param mixed $value
	 */
	
	public function setVariable($name, $value)
	{
		$this->_vars[$name] = $value;
	}
	
	/**
	 * Get variable
	 * 
	 * @param string $name
	 */
	
	public function getVariable($name)
	{
		if ( array_key_exists($name, $this->_vars) )
		{
			return $this->_vars[$name];
		}
	}
	
	/**
	 * Set location of view script
	 *
	 * @param string $dir
	 */
	
	public function setViewDirectory($dir)
	{
		$this->_view_dir = $dir;
	}	
	
	/**
	 * Set the view script
	 * 
	 * @param string $view
	 */
	
	public function setView($view)
	{
		$this->_view = $view;
	}
	
	/**
	 * Processes the view script against the data.
	 */
	
	public function render($format)	
	{
		// internal xml
		
		if ( $format == "xerxes" )
		{
			$this->headers->set('Content-type', 'text/xml');
			$this->setContent($this->toXML()->saveXML());
		}
		
		// no view set
		
		elseif ( $this->_view == null )
		{
			// do nothing
		}
		
		// xslt view
			
		elseif (strstr($this->_view, '.xsl') )
		{
			$xml = $this->toXML();
			$html = $this->transform($xml, $this->_view);
			$this->setContent($html);
		}
			
		// php view
			
		else
		{
			// buffer the output so we can catch and return it
			
			ob_start();
			require_once $this->_view_dir . "/" . $this->_view;
			$html = ob_get_clean();
			
			$this->setContent($html);
		}
		
		return $this;
	}
	
	/**
	 * Return variables as XML
	 */
	
	public function toXML()
	{
		$xml = Parser::convertToDOMDocument('<xerxes />');
	
		foreach ( $this->_vars as $id => $object )
		{
			Parser::addToXML($xml, $id, $object);
		}
	
		return $xml;
	}
	
	/**
	 * Transform XML to HTML
	 * 
	 * @param mixed $xml  XML-like data
	 * @param string $path_to_xsl
	 * @param array $params
	 */
	
	protected function transform($xml, $path_to_xsl, array $params = array())
	{
		$import_array = array();
		
		// the xsl lives here

		$distro_xsl_dir = $this->_view_dir . "/";
		$local_xsl_dir = realpath(getcwd()) . "/views/";
		
		// language
		
		// english file is included by default (as a fallback)
		
		array_push($import_array, "labels/eng.xsl");
		
		/*
		$request = new Request();
		$language = $request->getParam("lang");
		
		if ( $language == "" )
		{
			$language = $registry->defaultLanguage();
		}
		
		// if language is set to something other than english
		// then include that file to override the english labels
		
		if ( $language != "eng" &&  $language != '') 
		{
			array_push($import_array, "labels/$language.xsl");
		}
		*/
		
		// make sure we've got a reference to the local includes too
		
		array_push($import_array, "includes.xsl");
		
		// transform
		
		$xsl = new Xsl($distro_xsl_dir, $local_xsl_dir);
		
		return $xsl->transformToXml($xml, $path_to_xsl, 'html', $params, $import_array);
	}
}
