<?xml version="1.0" encoding="UTF-8"?>
<!DOCTYPE xsl:stylesheet  [
	<!ENTITY nbsp   "&#160;">
	<!ENTITY copy   "&#169;">
	<!ENTITY reg    "&#174;">
	<!ENTITY times  "&#215;">
	<!ENTITY trade  "&#8482;">
	<!ENTITY mdash  "&#8212;">
	<!ENTITY ldquo  "&#8220;">
	<!ENTITY rdquo  "&#8221;"> 
	<!ENTITY pound  "&#163;">
	<!ENTITY yen    "&#165;">
	<!ENTITY euro   "&#8364;">
]>
<!--

 This file is part of Xerxes.

 (c) California State University <library@calstate.edu>

 For the full copyright and license information, please view the LICENSE
 file that was distributed with this source code.

-->
<!--

 Databases search page
 author: David Walker <dwalker@calstate.edu>
 
 -->
 
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:php="http://php.net/xsl" exclude-result-prefixes="php">

<xsl:import href="../databases/database.xsl" />
<xsl:import href="includes.xsl" />

<xsl:template name="module_nav">

	<xsl:call-template name="module_nav_display">
		<xsl:with-param name="url">databases/database/<xsl:value-of select="databases/id" /></xsl:with-param>
	</xsl:call-template>

</xsl:template>

<xsl:template name="main">
	
	<xsl:call-template name="databases_full" />
	
	<xsl:call-template name="databases_edit" />
				
</xsl:template>

<xsl:template name="databases_edit">

	<div class="navbar navbar-inverse navbar-fixed-bottom databases-edit">
		<div class="navbar-inner">
			<div class="container">
				<div class="nav-collapse collapse">
				
					<ul class="nav" style="width:100%">
						<li style="float:right">
							<a id="delete-category" class="delete-confirm" href="{//request/controller}/delete-database?id={database/id}" 
								style="background-color:#400; border: 1px solid #efefef; border-top: 0px" role="button"> 
								<i class="icon-trash"></i>&nbsp; Delete Database
							</a>							
						</li>	
						<li>
							<a id="facet-more-link-{group_id}" href="{//request/controller}/edit-database?id={database/id}" role="button" data-toggle="modal"> 
								<i class="icon-edit"></i>
								&nbsp;<xsl:text> Edit Database</xsl:text>
							</a>							
						</li>
					</ul>
					
				</div>
			</div>
		</div>
	</div>
	
</xsl:template>

</xsl:stylesheet>