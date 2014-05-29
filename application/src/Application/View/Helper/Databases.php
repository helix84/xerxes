<?php

/*
 * This file is part of Xerxes.
 *
 * (c) California State University <library@calstate.edu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\View\Helper;

/**
 * View helper for databases
 *
 * @author David Walker <dwalker@calstate.edu>
 */

use Application\Model\Knowledgebase\Category;
use Application\Model\Knowledgebase\Database;
use Xerxes\Mvc\MvcEvent;
use Xerxes\Mvc\Request;

class Databases
{
	/**
	 * @var Request
	 */
	private $request;
	
	/**
	 * Create new Database Helper
	 *
	 * @param MvcEvent $e
	 */
	
	public function __construct(MvcEvent $e)
	{
		$this->request = $e->getRequest();
	}
	
	/**
	 * Add database navigation links
	 * 
	 * @param array $categories
	 * @return array
	 */
	
	public function getEditLink()
	{
		$controller = $this->request->getParam('controller');
		$switch = 'databases';
		
		if( $controller == 'databases')
		{
			$switch = 'databases-edit';
		}
		
		$params = $this->request->getParams();
		$params['controller'] = $switch;
		
		return $this->request->url_for($params);
	}
	
	/**
	 * Add links for Category, Database, and Librarian
	 * 
	 * @param mixed $object  array|Category|Database
	 * @param bool $deep     whether to go deep if a Category is supplied
	 */
	
	public function injectDataLinks($object, $deep = true)
	{
		// array
		
		if ( is_array($object) )
		{
			foreach ( $object as $item ) // so take 'em each in turn
			{
				$this->injectDataLinks($item, $deep);
			}
			
			return null;
		}
		
		// not an object and not an array, so what is it?
		
		if ( ! is_object($object) )
		{
			throw new \DomainException('Param must be of type array, Category or Database');
		}
		
		// Database
		
		if ( $object instanceof Database )
		{
			$params = array(
				'controller' => 'databases',
				'action' => 'database',
				'id' => $object->getId()
			);
			
			$object->url = $this->request->url_for($params);
		}
		
		// Category
		
		elseif ($object instanceof Category)
		{
			// category link
			
			$params = array (
				'controller' => 'databases',
				'action' => 'subject',
				'subject' => $object->getNormalized()
			);
			
			$object->url = $this->request->url_for($params);
			
			// embed link
			
			$params = array (
				'controller' => 'embed',
				'action' => 'gen-subject',
				'subject' => $object->getNormalized()
			);
				
			$object->url_embed = $this->request->url_for($params);			
			
			// only continue if we are going deep
			
			if ( $deep == true )
			{
				// Librarian
				
				foreach ( $object->getLibrarians() as $librarian )
				{
					$params = array (
						'controller' => 'databases',
						'action' => 'librarian',
						'id' => $librarian->getId()
					);					
				}
				
				// Databases
				
				foreach ( $object->getSubcategories() as $subcategory )
				{
					foreach ( $subcategory->getDatabases() as $database )
					{
						$this->injectDataLinks($item, $deep);
					}
				}
			}
		}
	}
}
