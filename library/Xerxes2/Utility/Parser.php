<?php

namespace Xerxes\Utility;

/**
 * Utility class for basic parsing functions
 * 
 * @author David Walker
 * @copyright 2008 California State University
 * @link http://xerxes.calstate.edu
 * @license http://www.gnu.org/licenses/
 * @version
 * @package  Xerxes_Framework
 */ 

class Parser
{
	public static function toSentenceCase($strInput)
	{						
		if ( strlen($strInput) > 1 )
		{
			// drop everything
			
			$strInput = self::strtolower($strInput);
			
			// capitalize the first letter
			
			$strInput = self::strtoupper(substr($strInput, 0, 1)) . substr($strInput, 1);
			
			// and the start of a subtitle
			
			$strInput = self::capitalizeSubtitle($strInput);
		}
		
		return $strInput;
	}
	
	private static function capitalizeSubtitle($strFinal)
	{
		$arrMatches = array();
		
		if ( preg_match("/: ([a-z])/", $strFinal, $arrMatches) )
		{
			$strLetter = ucwords($arrMatches[1]);
			$strFinal = preg_replace("/: ([a-z])/", ": " . $strLetter, $strFinal );
		}
		
		return $strFinal;
	}
	
	
	/**
	 * Determine whether the url is part of a group of domains
	 * 
	 * @param string $strURL	the url to test
	 * @param string $strDomain	a comma-separated list of domains
	 *
	 * @return bool				true if in domain, false otherwise
	 */
	
	public static function withinDomain($strURL, $strDomain)
	{
		$bolPassed = false;
		
		if ( strlen($strURL) > 4 )
		{
			// only do it if it's an absolute url, local are fine
				
			if ( substr($strURL, 0, 4) == "http" )
			{
				$arrAllowed = explode(",", $strDomain);
				
				// if any in our list match
				
				$bolPassed = false;
				
				foreach ( $arrAllowed as $strAllowed )
				{
					$strAllowed = trim(str_replace(".", "\\.", $strAllowed));
					$strAllowed = trim(str_replace("*", "[^.]*", $strAllowed));
					
					if ( preg_match('/^http[s]{0,1}:\/\/' . $strAllowed .'.*/', $strURL) )
					{
						$bolPassed = true;
					}
				}
			}
		}
		
		return $bolPassed;
	}
	

	/**
	 * Simple function to strip off the previous part of a string
	 * from the start of the term to the beginning, including the term itself
	 * 
	 * @param string $strExpression		whole string to search 
	 * @param string $strRemove			term to match and remove left of from 
	 * @return string 					chopped string
	 * @static
	 */

	public static function removeLeft ( $strExpression, $strRemove ) 
	{		
		$iStartPos = 0;		// start position of removing term
		$iStopPos = 0;		// end position of removing term
		$strRight = "";		// right remainder of the string to return
		
		// if it really is there
		if ( strpos($strExpression, $strRemove) !== false )
		{
			// find the starting position of string to remove
			$iStartPos = strpos($strExpression, $strRemove);
			
			// find the end position of string to remove
			$iStopPos = $iStartPos + strlen($strRemove);
			
			// return everything after that
			$strRight = substr($strExpression, $iStopPos, strlen($strExpression) - $iStopPos);
			
			return $strRight;
		} 
		else 
		{
			return $strExpression;
		}
	}

	/**
	 * Simple function to strip off the remainder of a string
	 * from the start of the term to the end of the string, including the term itself
	 * 
	 * @param string $strExpression		whole string to search 
	 * @param string $strRemove			term to match and remove right of from 
	 * @return string chopped string
	 * @static 
	 */ 

	public static function removeRight ( $strExpression, $strRemove ) 
	{		
		$iStartPos = 0;		// start position of removing term
		$strLeft = "";		// left portion of to return

		// if it really is there
		if ( strpos( $strExpression, $strRemove) !== false ) 
		{

			// find the starting position of to remove
			$iStartPos = strpos( $strExpression, $strRemove);
			
			// get everything before that
			$strLeft = substr( $strExpression, 0, $iStartPos);
							
			return $strLeft;
		} 
		else 
		{
			return $strExpression;
		}
	}
	
	/**
	 * Clean data for inclusion in an XML document, escaping illegal
	 * characters
	 *
	 * @param string $string data to be cleaned
	 * @return string cleaned data
	 * @static 
	 */
	
	public static function escapeXml( $string )
	{
		$string = str_replace('&', '&amp;', $string);
		$string = str_replace('<', '&lt;', $string);
		$string = str_replace('>', '&gt;', $string);
		$string = str_replace('\'', '&#39;', $string);
		$string = str_replace('"', '&quot;', $string);
		
		$string = str_replace("&amp;#", "&#", $string);
		$string = str_replace("&amp;amp;", "&amp;", $string);
		
		// trying to catch unterminated entity references
		
		$string = preg_replace('/(&#[a-hA-H0-9]{2,5})\s/', "$1; ", $string);
		
		return $string;
	}
	
	/**
	 * use multi-byte string lower case if available
	 * 
	 * @param string $string the string to drop to lower case
	 */
	
	public static function strtolower($string)
	{
		if ( function_exists("mb_strtolower") )
		{
			return mb_strtolower($string, "UTF-8");
		}
		else
		{
			return strtolower($string);
		}
	}

	
	/**
	 * use multi-byte string upper case if available
	 * 
	 * @param string $string the string to raise to upper case
	 */

	public static function strtoupper($string)
	{
		if ( function_exists("mb_strtoupper") )
		{
			return mb_strtoupper($string, "UTF-8");
		}
		else
		{
			return strtoupper($string);
		}
	}
	
	public static function preg_replace($pattern, $replacement, $subject)
	{
		if ( function_exists("mb_ereg_replace") )
		{
			// preg strings have / at the start and end, so we need to take those
			// off for this mb_ereg one (annoying!) for it to work correctly
			 
			$pattern = substr($pattern,1);
			$pattern = substr($pattern,0,-1);
			
			return mb_ereg_replace($pattern, $replacement, $subject);
		}
		else
		{
			return preg_replace($pattern, $replacement, $subject);
		}			
	}
	
	public static function number_format($number, $decimals = 0)
	{
		$number = (int) preg_replace('/\D/', '', $number);
		
		$localeconv = localeconv();
		
		if ( $localeconv['thousands_sep'] == "" )
		{
			$localeconv['thousands_sep'] = ",";
		}
		
		return number_format($number, $decimals, $localeconv['decimal_point'], $localeconv['thousands_sep']);
	}
	
	/**
	 * Convert string, DOMNode to DOMDocument
	 */
	
	public static function convertToDOMDocument($xml)
	{
		// alread a document
		
		if ( $xml instanceof \DOMDocument )
		{
			return $xml;
		}
		
		// convert simplexml to string, which will 
		// get covered by string below
		
		if ( $xml instanceof \SimpleXMLElement )
		{
			$xml = $xml->asXML();
		}
		
		// convertable type
		
		if ( is_string($xml) )
		{
			$document = new \DOMDocument();
			$document->loadXML($xml);
			
			return $document;
		}
		elseif ( $xml instanceof \DOMNode )
		{
			// we'll convert this node to a DOMDocument
				
			// first import it into an intermediate doc, 
			// so we can also import namespace definitions as well as nodes
				
			$intermediate = new \DOMDocument();
			$intermediate->loadXML("<wrapper />");
				
			$import = $intermediate->importNode($xml, true);
			$our_node = $intermediate->documentElement->appendChild($import);
				
			// now get just our xml, minus the wrapper
				
			$document = new \DOMDocument();
			$document->loadXML($intermediate->saveXML($our_node));
			
			return $document;
		}
		else
		{
			throw new \InvalidArgumentException("param 1 must be of type string, SimpleXMLElement, DOMNode, or DOMDocument");
		}
	}
	
	/**
	 * Remove from array based on key or key/value
	 *
	 * @param array $params			array
	 * @param string|array $key		the name of the param to remove
	 * @param string $value			[optional] only if the param has this value
	 */
	
	public static function removeFromArray(array $array, $key, $value = "")
	{
		$keys = array();
		
		// key can be string or array
		
		if ( ! is_array($key) )
		{
			$keys = array($key);
		}
		else
		{
			$keys = $key;
		}
		
		foreach ( $keys as $key )
		{
			if ( array_key_exists( $key, $array ) )
			{
				// delete by key
		
				if ( $value == "" )
				{
					unset($array[$key]);
				}
		
				// delete only if value also matches
		
				else
				{
					$stored = $array[$key];
		
					// if this is an array, we need to find the right one
		
					if ( is_array( $stored ) )
					{
						for ( $x = 0; $x < count($stored); $x++ )
						{
							if ( $stored[$x] == $value )
							{
								unset($array[$key][$x]);
							}
						}
		
						// reset the keys
		
						$array[$key] = array_values($array[$key]);
					}
					elseif ( $stored == $value )
					{
						unset($array[$key]);
					}
				}
			}
		}
	
		return $array;
	}
	
	/**
	 * Exclude properties from object serialization array
	 * 
	 * @param array $object_vars	from get_object_vars()
	 * @param array $exclude		properties to exclude
	 * @return array
	 */
	
	public static function removeProperties(array $object_vars, array $exclude)
	{
		$properties = array_keys($object_vars); // get all properties
		$properties = array_diff($properties, $exclude); // now exclude these
		
		return $properties;
	}
	
	/**
	 * Strips periods and pads the subnets of an IP address to three spaces
	 * 
	 * e.g., 144.37.1.23 = 144037001023
	 *
	 * @param string $original			original ip address
	 * @return string					address normalized with extra zeros
	 */
	
	public static function normalizeIpAddress($original)
	{
		$normalized = "";
		$arrAddress = explode( ".", $original );
	
		foreach ( $arrAddress as $subnet )
		{
			$normalized .= str_pad( $subnet, 3, "0", STR_PAD_LEFT );
		}
	
		return $normalized;
	}
	
	/**
	 * Opposite of normalizeIpAddress
	 *
	 * e.g., 144037001023 = 144.37.1.23
	 *
	 * @param string $original			normalized ip address
	 * @return string					nicely formatted version
	 */	
	
	public function formatIpAddress($normalized)
	{
		$parts = str_split($normalized, 3);
		
		$parts_int = array();
		
		foreach ( $parts as $part )
		{
			$parts_int[] = (int) $part;
		}
		
		$formatted = implode('.', $parts_int);
		
		return $formatted;
	}
	
	/**
	 * Strips periods and pads the subnets of a range of IP addresses
	 *
	 * Range can use wildcard (*) or hyphen to separate endpoints.
	 *
	 * @param string $range			ip range
	 * @return array				start,end
	 */	
	
	public static function normalizeIpRange($range)
	{
		$iStart = null;
		$iEnd = null;
	
		if ( strpos( $range, "-" ) !== false )
		{
			// range expressed with start and stop addresses
	
			$arrLocalRange = explode( "-", $range );
	
			$iStart = self::normalizeIpAddress( str_replace( "*", "000", $arrLocalRange[0]) );
			$iEnd = self::normalizeIpAddress( str_replace( "*", "255", $arrLocalRange[1]) );
		}
		else
		{
			// range expressed with wildcards
	
			$strStart = str_replace( "*", "000", $range );
			$strEnd = str_replace( "*", "255", $range );
	
			$iStart = self::normalizeIpAddress( $strStart );
			$iEnd = self::normalizeIpAddress( $strEnd );
		}
	
		return array($iStart,$iEnd);
	}
	
	
	/**
	 * Is the ip address within the supplied ip range(s)
	 * 
	 * Comma separated ranges, where each range can use
	 * wildcard (*) or hyphen to separate endpoints.
	 *
	 * @param string $address		ip address
	 * @param string $ranges		ip ranges, separate multiple ranged by comma
	 * @return bool					true if in range, otherwise false
	 */
	
	public static function isIpAddrInRanges($address, $ranges)
	{
		$local = false;
	
		// normalize the remote address
	
		$remote_address = self::normalizeIpAddress( $address );
	
		// multiple ranges separated by comma
	
		$arrRange = array();
		$arrRange = explode( ",", $ranges );
	
		// loop through ranges
	
		foreach ( $arrRange as $range )
		{
			$range = str_replace( " ", "", $range );
			$iStart = null;
			$iEnd = null;
	
			// normalize the campus range
	
			list($iStart,$iEnd) = self::normalizeIpRange($range);
			
			// see if remote address falls in between the campus range
	
			if ( $remote_address >= $iStart && $remote_address <= $iEnd )
			{
				$local = true;
			}
		}
		
		return $local;
	}
	
	/**
	 * Recursively convert and add data to XML
	 * 
	 * @param \DOMDocument $xml			document to add data to
	 * @param mixed $id					id of the data
	 * @param mixed $object				data
	 * 
	 * @throws \Exception
	 */
	
	public static function addToXML(\DOMDocument &$xml, $id, $object)
	{
		$object_xml = null;
	
		// no value, no mas!
	
		if ( $object == "" )
		{
			return null;
		}
	
		// already an xml-based object, so take it
	
		elseif ( $object instanceof \DOMDocument )
		{
			$object_xml = $object;
		}
	
		// simplexml, same deal, but make it dom, yo
	
		elseif ( $object instanceof \SimpleXMLElement )
		{
			$simple_xml = $object->asXML();
	
			if ( $simple_xml != "" )
			{
				if ( ! strstr($simple_xml, "<") )
				{
					throw new \Exception("SimpleXMLElement was malformed");
				}
	
				$object_xml = new \DOMDocument();
				$object_xml->loadXML($simple_xml);
			}
		}
	
		// object
	
		elseif ( is_object($object) )
		{
			// this object defines its own toXML method, so use that
	
			if ( method_exists($object, "toXML") )
			{
				$object_xml = $object->toXML();
			}
			else
			{
				$reflection = new \ReflectionObject($object);
				
				// no id supplied, likely because this is an array, 
				// so take class name (no namespace) as id
	
				if ( is_int($id) )
				{
					$id = strtolower($reflection->getShortName());
				}
				
				$object_xml = new \DOMDocument();
				$object_xml->loadXML("<$id />");
	
				// only public properties
	
				foreach ( $reflection->getProperties(\ReflectionProperty::IS_PUBLIC) as $property )
				{
					self::addToXML($object_xml, $property->name, $property->getValue($object));
				}
			}
		}
	
		// array
	
		elseif ( is_array($object) )
		{
			if ( count($object) == 0 )
			{
				return null;
			}
	
			$object_xml = new \DOMDocument();
			$object_xml->loadXML("<$id />");
	
			foreach ( $object as $property => $value )
			{
				// if the name of the array is plural, then make the childen singular
				// if this is an array of objects, then the object may override this
	
				if ( is_int($property) && substr($id,-1) == "s" )
				{
					$property = substr($id,0,-1);
				}
	
				self::addToXML($object_xml, $property, $value);
			}
		}
	
		// assumed to be primitive type (string, bool, or int, etc.)
	
		else
		{
			// no id supplied, likely from array, so give it a proper name
			
			if ( is_int($id) )
			{
				$id = "object_$id";
			}
			
			// just create a simple new element and return this thing
	
			$element = $xml->createElement($id, Parser::escapeXml($object) );
			$xml->documentElement->appendChild($element);
			return $xml;
		}
	
		// if we got this far, then we've got a domdocument to add
	
		$import = $xml->importNode($object_xml->documentElement, true);
		$xml->documentElement->appendChild($import);
	
		return $xml;
	}	
}