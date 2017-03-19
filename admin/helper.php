<?php
/**
 * @version     0.2.0
 * @package     com_j2grav
 * @copyright   Copyright (C) 2011. All rights reserved.
 * @license     GNU/GPL
 * @author      Hugo Avila
 */

// No direct access
defined('_JEXEC') or die;


/**
 * Rda helper.
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
		$category_template = $jinput->get('category_template', 'default', 'ALNUM');
		$article_template = $jinput->get('article_template', 'default', 'ALNUM');
		//$strip_tags = $jinput->get('strip_tags', 0, 'BOOLEAN');
		$content_format = $jinput->get('content_format', 'html', 'WORD');
		$use_article_language = $jinput->get('use_article_language', 0, 'BOOLEAN');
		$language = $jinput->get('language', '', 'WORD');

		if ( $content_format == 'markdownify') {
			require_once( JPATH_COMPONENT.'\vendor\markdownify\src\Markdownify\Converter.php' );
			require_once( JPATH_COMPONENT.'\vendor\markdownify\src\Markdownify\Parser.php' );
			$converter = new Markdownify\Converter;

		}


		if ($language) {
			$language = '.'.$language;
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
			
			// get category alias
			$db = JFactory::getDbo();
			$db->setQuery("SELECT cat.alias FROM #__categories cat WHERE cat.id=".$item->catid);
			$category_alias = $db->loadResult();


			// create markdown file for article
			$articleFile = JPATH_COMPONENT.jFolder::makeSafe(DS.$export_folder_name.DS.$category_alias.DS.$item->alias.DS.$article_template.$language.'.md');
			
			if ( !JFile::exists($articleFile) ) {

				$content = '';
				$content = "---\r";
				$content .= "title: ". $item->title . "\r";
				$content .= "tags: ". $tags . "\r";
				$content .= "---\r";

				/*
				if ($item_details->introtext != $item_details->articletext) {
					$content .= ($strip_tags) ? strip_tags($item_details->introtext) : $item_details->introtext;
					$content .= "\r===\r";
				} 
				*/

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
			$categoryFile = JPATH_COMPONENT.jFolder::makeSafe(DS.$export_folder_name.DS.$category_alias.DS.$category_template.$language.'.md');
			
			if ( !JFile::exists($categoryFile) ) {
				$content = '';
				$content = "---\r";
				$content .= "title: ". $item->category_title . "\r";
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

		//$zip = new JArchiveZip();
		//$zipFile = JPATH_COMPONENT.jFolder::makeSafe(DS.$export_folder_name.DS.'pages.zip');
		//$zip->create($zipFile,$files));

		JFactory::getApplication()->enqueueMessage( count($files)." files successfully created" );

		return $files;		
	}


}
