<?php
/**
 * @version     0.3.0
 * @package     com_j2grav
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU/GPL
 * @author      Hugo Avila
 */
 
// No direct access
 
defined('_JEXEC') or die('Restricted access'); ?>

<H1>Joomla to GRAV Exporter</H1>
<div class="alert alert-info">
	<p>When click "Export Now" button, the plugin will get Joomla articles title, alias, category and tags, then will create a folder and markdown files for every article in your Joomla installation and will create a folder and markdown file for corresponding joomla category in the grav style.</p>
	<p>* If you already exported your joomla files to grav, you should download and remove exported files, when using again it will not overwrite current exported	files.*</p>
	<p>* Will not create nested categories *</p>
	<p>Your GRAV pages will be stored in: <pre>/administrator/components/com_j2grav/exported</pre></p>
	<p>When finished you should remove this component completely.</p>
</div>


<form action="index.php?option=com_j2grav">

	<input name="option" value="com_j2grav" type="hidden">
	<input name="view" value="exportarticles" type="hidden">
	
	<label><b>Grav Template for articles?</b></label>
	<input type="text" name="article_template" value="item" />.md

	<label><b>Grav Template for categories?</b></label>
	<input type="text" name="category_template" value="blog" />.md

	<label><input type="checkbox" name="import_tags" /><b>Import Joomla Article Tags</b></label>
	
	<label><input type="checkbox" name="force_visibility_article" /> <b>Force visibility for all articles</b></label>
	
	<label><input type="checkbox" name="force_visibility_category" /> <b>Force visibility for all categories</b></label>

	<label><input type="checkbox" name="force_visibility_category" /> <b>Force visibility for all categories</b></label>

	<label><input type="checkbox" name="use_article_language" /> <b>Use Joomla Language Tag?</b> If your Joomla article has language, use it to create markdown file ie. default.<b>es</b>.md? </label> 


	<label><b>Manually Set Language?</b> If above is set to no, you can manually set one of your language code "es", "en", etc. (without quotes) to be applied to all your files, if no need just leave empty</label>
	<input type="text" name="language" value="" /> 


	<label><b>Content Format?</b> you could just leave the content as it is in joomla database, or Strip HTML tags, or convert to Markdown with the Markdownify library from https://github.com/Elephant418/Markdownify</label> 
	<select name="content_format" >
		<option value="html">Do not alter format</option>
		<option value="strip_tags">Strip HTML Tags</option>
		<option value="markdownify">Markdownify</option>
	</select> 

<br>
	<input class="btn btn-large btn-primary" type="submit" value="Export Now" />

</form>