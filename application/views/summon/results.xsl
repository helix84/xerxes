<?xml version="1.0" encoding="UTF-8"?>

<!--

 This file is part of Xerxes.

 (c) California State University <library@calstate.edu>

 For the full copyright and license information, please view the LICENSE
 file that was distributed with this source code.

-->
<!--

 Summon results view
 author: David Walker <dwalker@calstate.edu>
 
 -->
 
<xsl:stylesheet version="1.0"
	xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
	xmlns:php="http://php.net/xsl" exclude-result-prefixes="php">

<xsl:import href="../includes.xsl" />
<xsl:import href="../search/results.xsl" />

<xsl:output method="html" />

<xsl:template match="/*">
	<xsl:call-template name="surround">
		<xsl:with-param name="surround_template">none</xsl:with-param>
		<xsl:with-param name="sidebar">none</xsl:with-param>
	</xsl:call-template>
</xsl:template>

<xsl:template name="breadcrumb">
	<xsl:call-template name="breadcrumb_search" />
	<xsl:value-of select="$text_search_results" />
</xsl:template>

<xsl:template name="page_name">
	<xsl:value-of select="//request/query" />
</xsl:template>

<xsl:template name="title">
	<xsl:call-template name="page_name" />
</xsl:template>

<xsl:template name="main">
	<xsl:call-template name="search_page" />
</xsl:template>

<xsl:template name="search_recommendations">

	<xsl:if test="not(//request/start)">
	
		<xsl:if test="results/database_recommendations  and //config/show_database_recommendations = 'true'">
	
			<div class="results-database-recommendations">
			
				<h2>
					<xsl:copy-of select="$text_summon_recommendation" />
				</h2>
		
				<ul>
			
				<xsl:for-each select="results/database_recommendations/database_recommendation">
					
					<li>
						<a href="{link}"><xsl:value-of select="title" /></a>
						
						<xsl:if test="description">
							
							<p><xsl:value-of select="description" /></p>
						
						 </xsl:if>
	
					</li>
					
				</xsl:for-each>	
				
				</ul>
				
			</div>
			
		</xsl:if>
		
		<xsl:if test="results/best_bets and not(//config/best_bets = 'false')">
		
			<div class="results-database-recommendations">
			
				<xsl:for-each select="results/best_bets/best_bet">
					
					<div class="results-bestbet">
					
						<h2><a href="{link}"><xsl:value-of select="title" /></a></h2>
						
						<div class="description">
						
							<xsl:if test="description">
								<xsl:value-of disable-output-escaping="yes" select="description" />
							</xsl:if>
							
						</div>
					
					</div>
					
				</xsl:for-each>
				
			</div>
			
		</xsl:if>
		
	</xsl:if>
	
</xsl:template>

<xsl:template name="facet_narrow_results">

	<h3><xsl:copy-of select="$text_summon_facets_refine" /></h3>

	<xsl:variable name="scholarly">
		<xsl:if test="//request/*[@original_key = 'facet.IsScholarly']">
			<xsl:text>true</xsl:text>
		</xsl:if>		
	</xsl:variable>

	<xsl:variable name="peer">
		<xsl:if test="//request/*[@original_key = 'facet.IsPeerReviewed']">
			<xsl:text>true</xsl:text>
		</xsl:if>		
	</xsl:variable>

	<xsl:variable name="fulltext">
		<xsl:if test="//request/*[@original_key = 'facet.IsFullText']">
			<xsl:text>true</xsl:text>
		</xsl:if>
	</xsl:variable>

	<xsl:variable name="showall">
		<xsl:value-of select="$scholarly" /><xsl:value-of select="$fulltext" />
	</xsl:variable>

	<xsl:variable name="newspapers">
		<xsl:value-of select="//request/*[@original_key = 'facet.newspapers']" />
	</xsl:variable>
	
	<xsl:variable name="holdings">
		<xsl:if test="//request/*[@original_key = 'facet.holdings']">
			<xsl:text>true</xsl:text>
		</xsl:if>
	</xsl:variable>

	<form id="form-facet-0" action="{//request/controller}/search" method="get">

		<xsl:call-template name="hidden_search_inputs">
			<xsl:with-param name="exclude_limit">facet.IsScholarly,facet.IsPeerReviewed,facet.IsFullText,facet.holdings,facet.newspapers</xsl:with-param>
		</xsl:call-template>
		
		<ul>
		
			<xsl:if test="not(//config/limit_to_holdings) or //config/limit_to_holdings = 'false'">
		
				<li class="facet-selection">
				
					<input type="checkbox" class="facet-selection-clear" id="facet-0">
						<xsl:if test="$showall = ''">
							<xsl:attribute name="checked">checked</xsl:attribute>
						</xsl:if>
					</input>
					<xsl:text> </xsl:text>
					<label for="facet-0"><xsl:copy-of select="$text_summon_facets_all" /></label>
					
				</li>		
				
			</xsl:if>		
			
			<xsl:if test="not(//config/show_scholarly_limit) or //config/show_scholarly_limit = 'true'">
			
				<li class="facet-selection">
				
					<input type="checkbox" id="facet-0-1" class="facet-selection-option facet-0" name="facet.IsScholarly" value="true">
						<xsl:if test="$scholarly = 'true'">
							<xsl:attribute name="checked">checked</xsl:attribute>
						</xsl:if>
					</input>
					<xsl:text> </xsl:text>
					<label for="facet-0-1"><xsl:copy-of select="$text_summon_facets_scholarly" /></label>
				
				</li>
			
			</xsl:if>

			<xsl:if test="//config/show_peer_reviewed_limit = 'true'">
	
				<li class="facet-selection">
				
					<input type="checkbox" id="facet-0-2" class="facet-selection-option facet-0" name="facet.IsPeerReviewed" value="true">
						<xsl:if test="$peer = 'true'">
							<xsl:attribute name="checked">checked</xsl:attribute>
						</xsl:if>
					</input>
					<xsl:text> </xsl:text>
					<label for="facet-0-2"><xsl:copy-of select="$text_summon_facets_refereed" /></label>
				
				</li>
				
			</xsl:if>
			
			<xsl:if test="not(//config/show_fulltext_limit) or //config/show_fulltext_limit = 'true'">
		
				<li class="facet-selection">
				
					<input type="checkbox" id="facet-0-3" class="facet-selection-option facet-0" name="facet.IsFullText" value="true">
						<xsl:if test="$fulltext = 'true'">
							<xsl:attribute name="checked">checked</xsl:attribute>
						</xsl:if>
					</input>
					<xsl:text> </xsl:text>
					<label for="facet-0-3"><xsl:copy-of select="$text_summon_facets_fulltext" /></label>
				
				</li>
			
			</xsl:if>

			<xsl:if test="//config/newspapers_optional = 'true'">
	
				<li class="facet-selection">
				
					<input type="checkbox" id="facet-0-4" class="facet-selection-option facet-0" name="facet.newspapers" value="true">
						<xsl:if test="$newspapers = 'true'">
							<xsl:attribute name="checked">checked</xsl:attribute>
						</xsl:if>
					</input>
					<xsl:text> </xsl:text>
					<label for="facet-0-4"><xsl:copy-of select="$text_summon_facets_newspaper-add" /></label>
				
				</li>
				
			</xsl:if>

			<xsl:if test="//config/newspapers_optional = 'exclude'">
	
				<li class="facet-selection">
				
					<input type="checkbox" id="facet-0-5" class="facet-selection-option facet-0" name="facet.newspapers" value="false">
						<xsl:if test="$newspapers = 'false'">
							<xsl:attribute name="checked">checked</xsl:attribute>
						</xsl:if>
					</input>
					<xsl:text> </xsl:text>
					<label for="facet-0-5"><xsl:copy-of select="$text_summon_facets_newspaper-exclude" /></label>
				
				</li>
				
			</xsl:if>
	
			<xsl:if test="//config/limit_to_holdings = 'true'">
	
				<li class="facet-selection">
				
					<input type="checkbox" id="facet-0-6" class="facet-selection-option facet-0" name="facet.holdings" value="false">
						<xsl:if test="$holdings = 'true'">
							<xsl:attribute name="checked">checked</xsl:attribute>
						</xsl:if>
					</input>
					<xsl:text> </xsl:text>
					<label for="facet-0-6"><xsl:copy-of select="$text_summon_facets_beyond-holdings" /></label>
				
				</li>
				
			</xsl:if>
		</ul>
		
		<xsl:call-template name="facet_noscript_submit" />
	
	</form>

</xsl:template>

<xsl:template name="module_javascript">

<script type="application/javascript">
	$("img.cover").load(function() {
		if ( $( this ).attr('src') == 'images/no-image.gif') {
			$( this ).remove();
		}
	});
</script>
	
</xsl:template>


	<xsl:template name="brief_result_info-cover">
		<div class="cover" style="float:right; margin-left:1em">
		
			<xsl:variable name="cover-size">medium</xsl:variable>
			<xsl:choose>
				<xsl:when test="standard_numbers/isbn">
					<img class="cover">
						<xsl:attribute name="src">
							<xsl:value-of select="concat('http://utb.summon.serialssolutions.com/2.0.0/image/isbn/', //config[@source='summon']/client_id, '/', standard_numbers/isbn, '/', $cover-size)" />
						</xsl:attribute>
					</img>
				</xsl:when>
				<xsl:when test="standard_numbers/issn">
					<img class="cover">
						<xsl:attribute name="src">
							<xsl:value-of select="concat('http://utb.summon.serialssolutions.com/2.0.0/image/issn/', //config[@source='summon']/client_id, '/', substring(standard_numbers/issn, 1, 4), '-', substring(standard_numbers/issn, 5, 4), '/', $cover-size)" />
						</xsl:attribute>
					</img>
				</xsl:when>
			</xsl:choose>
		
		</div>
	</xsl:template>
	
</xsl:stylesheet>
