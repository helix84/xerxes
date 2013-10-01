<?xml version="1.0" encoding="UTF-8"?>

<!--

 This file is part of Xerxes.

 (c) California State University <library@calstate.edu>

 For the full copyright and license information, please view the LICENSE
 file that was distributed with this source code.

-->
<!--

 Summon search home page view
 author: David Walker <dwalker@calstate.edu>
 
 -->
 
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:php="http://php.net/xsl" exclude-result-prefixes="php">

<xsl:import href="../search/index.xsl" />


<xsl:template name="searchbox_hidden_fields_module">

	<xsl:for-each select="//config/preselected_facets/facet">
		<input type="hidden" name="{@name}" value="{@value}" />
	</xsl:for-each>

</xsl:template>

	<xsl:template name="searchbox">
		
		<xsl:param name="action"><xsl:value-of select="$base_url" />/<xsl:value-of select="//request/controller" />/search</xsl:param>
		
		<form id="form-main-search" action="{$action}" method="get">	
				
			<xsl:if test="//request/lang">
				<input type="hidden" name="lang" value="{//request/lang}" />
			</xsl:if>
			
			<xsl:call-template name="searchbox_hidden_fields_module" />
			<xsl:call-template name="searchbox_hidden_fields_local" />
			
			<xsl:if test="request/sort">
				<input type="hidden" name="sort" value="{request/sort}" />
			</xsl:if>
			
			<xsl:choose>
				<xsl:when test="$is_mobile = '1'">
					<xsl:call-template name="searchbox_mobile" />
				</xsl:when>
				<xsl:otherwise>
					<xsl:call-template name="searchbox_full" />
				</xsl:otherwise>
			</xsl:choose>
			
			<div style="padding-top:2em;" >
				<input type="checkbox" id="facet-0-3" class="facet-selection-option facet-0" name="facet.IsFullText" value="true" checked="checked" />
				<xsl:text> </xsl:text>
				<label for="facet-0-3"><xsl:copy-of select="$text_summon_facets_fulltext" /></label>
			</div>
			
			<xsl:for-each select="//config[@source='summon']/category_groups/group">
				<h2><xsl:value-of select="@id" /></h2>
				<xsl:for-each select="facet_group">
					<ul>
						<li class="facet-selection">
							<input type="checkbox" id="facet-4-1" class="facet-selection-option facet-4" name="facet.{@facet}">
								<xsl:attribute name="value">
									<xsl:for-each select="facet"><xsl:value-of select="@id" />
										<xsl:if test="position() != last()">
											<xsl:text>|</xsl:text>
										</xsl:if>
									</xsl:for-each>
								</xsl:attribute>
							</input>
							<xsl:text> </xsl:text>
							<label for="facet-4-1">
								<xsl:call-template name="category_group_map">
									<xsl:with-param name="id"><xsl:value-of select="@id" /></xsl:with-param>
								</xsl:call-template>
							</label>
						</li>
					</ul>
				</xsl:for-each>
			</xsl:for-each>
		</form>
		
	</xsl:template>

</xsl:stylesheet>
