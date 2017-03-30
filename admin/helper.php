<?php
/**
 * @version     0.4.0
 * @package     com_j2grav
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU/GPL
 * @author      Hugo Avila
 */

// No direct access
defined('_JEXEC') or die;


/**
 * J2Grav helper.
 */
class J2gravHelper
{

	public static function articles2files()
	{
		
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		
		if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);

		$jinput = JFactory::getApplication()->input;

		$export_folder_name = 'exported'.DS.'pages';
		$category_template = $jinput->get('category_template', 'default', 'WORD');
		$article_template = $jinput->get('article_template', 'default', 'WORD');
		//$strip_tags = $jinput->get('strip_tags', 0, 'BOOLEAN');
		$content_format = $jinput->get('content_format', 'html', 'WORD');
		$use_article_language = $jinput->get('use_article_language', 0, 'BOOLEAN');
		$import_tags = $jinput->get('import_tags', 0, 'BOOLEAN');
		$force_visibility_article = $jinput->get('force_visibility_article', 0, 'BOOLEAN');
		$force_visibility_category = $jinput->get('force_visibility_category', 0, 'BOOLEAN');
		$language = $jinput->get('language', '', 'WORD');
		$categories_export_mode = $jinput->get('categories_export_mode', 0, 'INT');

		if ( $content_format == 'markdownify') {
			require_once( JPATH_COMPONENT.DS.'vendor'.DS.'markdownify'.DS.''.DS.'src'.DS.'Markdownify'.DS.'Converter.php' );
			require_once( JPATH_COMPONENT.DS.'vendor'.DS.'markdownify'.DS.''.DS.'src'.DS.'Markdownify'.DS.'Parser.php' );
			$converter = new Markdownify\Converter;
		}

		if ($language) {
			$language = '.' . $language;
		} else {
			$language = '';
		}

		$files = array();

		JModelLegacy::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_content/models', 'ContentModel');

		// Get an instance of the generic articles model
		$items_model = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));		
		$items = $items_model->getItems();

		// Get an instance of the generic article model
		$item_model = JModelLegacy::getInstance('Article', 'ContentModel', array('ignore_request' => true));

		// loop articles
		foreach ($items as $item) {

			
			// get item details
			$item_details = $item_model->getItem($item->id);
			
			// get item tags
			$item_details->tagsArray = $item_details->tags->getItemTags('com_content.article',$item_details->id);
			$tags = '';

			foreach ($item_details->tagsArray as $tag) {
				$tags.= $tag->title." ";
			}

			// get item language and use it if requiered
			if ( $use_article_language ) {
				// get article language just first two chars like en from en_GB
				$joomlaLanguage = substr($item_details->language,0,2);
				$language = "";
				if ( $joomlaLanguage != "*" ) {
					$language = ".".$joomlaLanguage;
				}
			}
			
			
			switch ($categories_export_mode) {

				case 1: 	// Each article in its own category folder
					$category_path = self::getCategoryAlias($item->catid).DS;
					break;

				case 2: 	// Nested categories as folder paths
					$category_path = self::getCategoryPath($item->catid);
					break;
				
				default:	// No folder for categories
					$category_path = ''
					break;
			}



			// create markdown file for article
			$articleFile = JPATH_COMPONENT.jFolder::makeSafe(DS.$export_folder_name.DS.$category_path.$item->alias.DS.$article_template.$language.'.md');
			
			if ( !JFile::exists($articleFile) ) {

				$content = '';
				$content = "---\r";
				$content .= "title: ". $item->title . "\r";
				if ($import_tags) {
					$content .= "taxonomy:\r"; 
					if ($tags) { $content .= "    tags: ". $tags . "\r"; }
					if ($categories_as_taxonomy) { $content .= "    category: ". $tags . "\r"; }

				}
				if ($force_visibility_article) {  $content .= "visible: true\r"; }
				$content .= "---\r";


				// apply selected content format
				switch ($content_format) {

					case 'html' :
						$content .= $item_details->articletext;
						break;

					case 'strip_tags' :
						$content .= strip_tags($item_details->articletext);
						break;

					case 'markdownify' :
						$content .= $converter->parseString($item_details->articletext);
						break;
				
				}


				if ( JFile::write( $articleFile, $content ) ){
					//echo $articleFile.' *created* <br>';
					$files[] = $articleFile;
				} else {
					JFactory::getApplication()->enqueueMessage( "!ERROR Creating ".$articleFile."<br>" );
				}

			}


			// create markdown file for category
			$categoryFile = JPATH_COMPONENT.jFolder::makeSafe(DS.$export_folder_name.DS.$category_path.$category_template.$language.'.md');
			
			if ( !JFile::exists($categoryFile) ) {
				$content = '';
				$content = "---\r";
				$content .= "title: ". $item->category_title . "\r";
				if ($force_visibility_category) {  $content .= "visible: true\r"; }
				$content .= "---\r";

				if ( JFile::write($categoryFile, $content) ) {
					//echo $categoryFile.' *created* <br>';
					$files[] = $categoryFile;

				} else {
					//echo "!ERROR Creating ".$categoryFile."<br>";
					JFactory::getApplication()->enqueueMessage( "!ERROR Creating ".$categoryFile."<br>" );
				}
			}


		}


		JFactory::getApplication()->enqueueMessage( count($files)." files successfully created" );

		return $files;		
	}



	protected static function getCategoryPath($cat_id)
	{
		$model_categories = JCategories::getInstance('Content');
		$category = $model_categories->get($cat_id);
		$parent = $category->getParent();

		$path = self::getCategoryAlias($cat_id) . DS;
			
		if ($parent->id <> 'root'){
			$path = self::getCategoryPath($parent->id) . $path;
		}
		
		return $path;

	}


	protected static function getCategoryAlias($cat_id){
		// get category alias
		$db = JFactory::getDbo();
		$db->setQuery("SELECT cat.alias FROM #__categories cat WHERE cat.id=".$cat_id);
		$category_alias = $db->loadResult();
		return $category_alias . DS;
	}




}
