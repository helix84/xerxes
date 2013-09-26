<?xml version="1.0" encoding="UTF-8"?>

<!--

 This file is part of Xerxes.

 (c) California State University <library@calstate.edu>

 For the full copyright and license information, please view the LICENSE
 file that was distributed with this source code.

-->
<!--

 Search home page view
 author: David Walker <dwalker@calstate.edu>
 
 -->
 
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:php="http://php.net/xsl" exclude-result-prefixes="php">

<xsl:import href="../includes.xsl" />

<xsl:output method="html" />

<xsl:template match="/*">
	<xsl:call-template name="surround">
		<xsl:with-param name="surround_template">none</xsl:with-param>
		<xsl:with-param name="sidebar">none</xsl:with-param>
	</xsl:call-template>
</xsl:template>

<xsl:template name="breadcrumb">
	<xsl:call-template name="breadcrumb_start" />
	<xsl:value-of select="$text_databases_az_pagename" />
</xsl:template>


<!--
<xsl:template name="main">

		<h1><xsl:value-of select="$text_databases_az_pagename" /></h1>

</xsl:template>
-->
	<xsl:variable name="default_language">
		<xsl:value-of select="//config/languages/language[position()=1]/@code" />
	</xsl:variable>
	<xsl:variable name="language">
		<xsl:choose>
			<xsl:when test="//request/lang and //request/lang != ''"> <!-- @todo: allow only languages defined in //config/languages/language[@code] -->
				<xsl:value-of select="//request/lang" />
			</xsl:when>
			<xsl:when test="$default_language"> <!-- if it's defined, use it -->
				<xsl:value-of select="$default_language" />
			</xsl:when>
			<xsl:otherwise>
				<xsl:text>eng</xsl:text>
			</xsl:otherwise>
		</xsl:choose>
	</xsl:variable>


	<xsl:variable name="databases_searchable"	select="//config/database_list_searchable" />
	<xsl:variable name="link_target_databases" select="//config/link_target_databases" />

<xsl:variable name="show_alpha_links" select="not(/knowledge_base/request/show_alpha_links) or /knowledge_base/request/show_alpha_links != 'false'" />
<!--

<xsl:template match="/*">
	<xsl:call-template name="surround" />
</xsl:template>

<xsl:template name="breadcrumb">
	
	<xsl:choose>
		<xsl:when test="//request/action != 'alphabetical'">
		
			<xsl:call-template name="breadcrumb_databases">
				<xsl:with-param name="condition">4</xsl:with-param>
			</xsl:call-template>
		
			<xsl:copy-of select="$text_databases_az_breadcrumb_matching" /> "<xsl:value-of select="//request/query" />"
		
		</xsl:when>
		<xsl:otherwise>
			<xsl:call-template name="breadcrumb_databases" />
			<xsl:call-template name="page_name" />
		</xsl:otherwise>
	</xsl:choose>
	
</xsl:template>
-->

<xsl:template name="page_name">
	<xsl:value-of select="$text_databases_az_pagename" />
</xsl:template>

<xsl:template name="sidebar">
	<xsl:call-template name="account_sidebar" />
</xsl:template>

<xsl:template name="main">
	
	<a name="top" />
	
	<h1><xsl:call-template name="page_name" /></h1>
	
	<xsl:if test="$databases_searchable = 'true'">
		<xsl:call-template name="databases_search_box" />
	</xsl:if>
	
	<p><strong><xsl:value-of select="count(databases/database)" /><xsl:text> </xsl:text><xsl:copy-of select="$text_databases_az_databases" /></strong></p>
	
	<xsl:variable name="lower">abcdefghijklmnopqrstuvwxyz</xsl:variable>
	<xsl:variable name="upper">ABCDEFGHIJKLMNOPQRSTUVWXYZ</xsl:variable>
	
	<xsl:if test="$show_alpha_links">
	
		<div id="alphaLetters">
		
			<xsl:for-each select="databases/database">
			
				<xsl:variable name="letter" select="substring(translate(title_display,$lower,$upper), 1, 1)" />
				
				<xsl:if test="substring(translate(preceding-sibling::database[1]/title_display,$lower,$upper), 1, 1) !=  $letter">
					<a><xsl:attribute name="href"><xsl:value-of select="concat(/xerxes/base_url, '/', /xerxes/request/controller, '/', /xerxes/request/action)" />#<xsl:value-of select="$letter" /></xsl:attribute> 
					<xsl:value-of select="$letter" /></a>
					<span class="letterSeperator"><xsl:copy-of select="$text_databases_az_letter_separator" /></span> 
				</xsl:if>
			
			</xsl:for-each>
		
		</div>
	</xsl:if>
	
	<xsl:for-each select="databases/database">
	
		<xsl:if test="$show_alpha_links" >
			<xsl:variable name="letter" select="substring(translate(title_display,$lower,$upper), 1, 1)" />
		
			<xsl:if test="substring(translate(preceding-sibling::database[1]/title_display,$lower,$upper), 1, 1) !=  $letter">
				<div class="alphaHeading">
					<div class="yui-g">
						<div class="yui-u first">
							<a name="{$letter}"><h2><xsl:value-of select="$letter" /></h2></a>
						</div>
						<div class="yui-u">
							<div class="alphaBack">
								[ <a><xsl:attribute name="href"><xsl:value-of select="concat(/xerxes/base_url, '/', /xerxes/request/controller, '/', /xerxes/request/action)" />#top</xsl:attribute><xsl:copy-of select="$text_databases_az_backtop" /></a> ]
							</div>
						</div>
					</div>
				</div>
			</xsl:if>
		</xsl:if>
		
		<div class="result">
			<xsl:variable name="link_native_home" select="php:function('urlencode', string(link_native_home))" />
			<xsl:variable name="id_meta" select="metalib_id" />		
		
			<div class="resultsTitle">
			
				<a target="{$link_target_databases}">
					<xsl:if test="link_native_home">
						<xsl:attribute name="href"><xsl:value-of select="xerxes_native_link_url" /></xsl:attribute>
					</xsl:if>
					<xsl:value-of select="title_display" />
				</a>
				
				<xsl:if test="title_display">
					&#160;
					<a href="{link_guide}">

						<xsl:call-template name="img_databases_az_hint_info" />
						<xsl:text> </xsl:text>
            
						<xsl:if test="searchable">
							<xsl:call-template name="img_databases_az_hint_searchable" />
						</xsl:if>
					</a>
					
<!--
					<xsl:if test="count(group_restriction) > 0" >
						<xsl:text> </xsl:text>(<xsl:call-template name="db_restriction_display" />)
					</xsl:if>
-->
				</xsl:if>
			</div>
			<div class="resultsDescription">
				<xsl:call-template name="show_db_description" />
			</div>
		</div>
		
	</xsl:for-each>

</xsl:template>

<!--
	TEMPLATE: show_db_description
	Shows database description from IRD, respecting current language and the db_description_multilingual
	config option.
-->

<xsl:template name="show_db_description">
	<xsl:param name='description_language' select="$language" />

	<xsl:choose>
		<xsl:when test="//config/db_description_multilingual/language">
			<xsl:value-of select="description[@lang=$description_language]" disable-output-escaping="yes" />
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="description" disable-output-escaping="yes" />
		</xsl:otherwise>
	</xsl:choose>
	
</xsl:template>

<!-- 
	TEMPLATE: DB RESTRICTION DISPLAY
 	Show access rights for db, including group restrictions. Either pass in a parameter, or else it assumes that
	a <database> node is the XSL current() node. 
-->

<xsl:template name="db_restriction_display">
	<xsl:param name="database" select="current()" />

	<xsl:variable name="group_restrictions" select="$database/group_restriction" />
	
	<xsl:if test="$group_restrictions">
		<xsl:copy-of select="$text_databases_access_available" />
	</xsl:if>
	
	<xsl:for-each select="$group_restrictions">
		<xsl:value-of select="@display_name" />
		<xsl:choose>
			<xsl:when test="count(following-sibling::group_restriction) = 1">
				<xsl:text> </xsl:text><xsl:copy-of select="$text_databases_access_group_and" /><xsl:text> </xsl:text>
			</xsl:when>
			<xsl:when test="count(following-sibling::group_restriction) > 1">
			,<xsl:text> </xsl:text>
			</xsl:when>
		</xsl:choose>
	</xsl:for-each>
	<xsl:if test="$group_restrictions">
	<xsl:text>  </xsl:text><xsl:copy-of select="$text_databases_access_users" />
	</xsl:if>
</xsl:template>


</xsl:stylesheet>

