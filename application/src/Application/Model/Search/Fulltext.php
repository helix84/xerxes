<?php

/*
 * This file is part of Xerxes.
 *
 * (c) California State University <library@calstate.edu>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Model\Search;

use Xerxes\Utility\DataValue;

/**
 * Database Value Full Text
 *
 * @author David Walker <dwalker@calstate.edu>
 */

class Fulltext extends DataValue
{
	public $issn;
	public $title;
	public $startdate;
	public $enddate;
	public $embargo;
	public $updated;
	public $live;
}
