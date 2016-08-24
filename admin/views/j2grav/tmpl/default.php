<?php
 
// No direct access
 
defined('_JEXEC') or die('Restricted access'); ?>

<H1>Joomla to GRAV Exporter</H1>
<div class="alert alert-info">
	<p>When click "Export Now" button, the plugin will get Joomla articles title, alias, category and tags, then will create a folder and markdown files for every article in your Joomla installation and will create a folder and markdown file for corresponding joomla category in the grav style.</p>
	<p>* Will not create nested categories *</p>
	<p>Your GRAV pages will be stored in: <pre>/administrator/components/com_j2grav/exported</pre></p>
	<p>When finished you should remove this component completely.</p>
</div>


<form action="index.php?option=com_j2grav&view=exportarticles">

	<label><b>Grav Template for articles?</b></label>
	<input type="text" name="article_template" value="item" />.md

	<label><b>Grav Template for categories?</b></label>
	<input type="text" name="category_template" value="blog" />.md

	<label><b>Joomla Language Tag?</b> If your Joomla article has language use it to create markdown file ie. default.<b>es</b>.md? </label> 
	<select name="use_article_language" >
		<option value="0">No</option>
		<option value="1" selected="selected">Yes</option>
	</select> 

	<label><b>Manually Set Language?</b> If above is set to no, you can manually set one of your language code "es", "en", etc. (without quotes) to be applied to all your files, if no need just leave empty</label>
	<input type="text" name="language" value="" /> 

	<label><b>Strip HTML tags?</b> your Joomla articles have html tags for sure, and they are markdown compatible, but maybe you want to get rid of them. </label> 
	<select name="strip_tags" >
		<option value="0">No</option>
		<option value="1">Yes</option>
	</select> 

	<input name="option" value="com_j2grav" type="hidden">
	<input name="view" value="exportarticles" type="hidden">
<br>
	<input class="btn btn-large btn-primary" type="submit" value="Export Now" />

</form>