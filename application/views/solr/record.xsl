<?xml version="1.0" encoding="UTF-8"?>

<!--

 author: David Walker
 copyright: 2011 California State University
 version: $Id$
 package: Xerxes
 link: http://xerxes.calstate.edu
 license: 
 
 -->

<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:php="http://php.net/xsl" exclude-result-prefixes="php">
	
<xsl:import href="../includes.xsl" />
<xsl:import href="../search/results.xsl" />
<xsl:import href="../search/record.xsl" />
<xsl:import href="../search/books.xsl" />

<xsl:output method="html" />

<xsl:template match="/*">
	<xsl:call-template name="surround" />
</xsl:template>

<xsl:template name="breadcrumb">
	<xsl:call-template name="breadcrumb_search" />
	<xsl:value-of select="$text_search_record" />
</xsl:template>

<xsl:template name="page_name">
	<xsl:value-of select="//xerxes_record/title_normalized" />
</xsl:template>

<xsl:template name="main">
		
	<xsl:call-template name="record" />
	
</xsl:template>
</xsl:stylesheet>