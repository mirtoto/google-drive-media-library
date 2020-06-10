<?php
/*
    Plugin Name: Google Drive Media Library
    Plugin URI: http://wordpress.org/plugins/google-drive-media-library/
    Description: Mapping file from google drive into Wordpress Media
    Author: Felix Wei (v1.0), Mirosław Toton (v1.X)
    Version: 1.2
    Author URI:
    License: GNU General Public License v2.0 or later
    License URI: http://www.opensource.org/licenses/gpl-license.php
*/

/**
 * Copyright (c)2014 Felix Wei.
 * 
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * ()at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

require_once "includes/class-GDMLWeb.php";

/*
 * Replace url from Google Drive
 * Created on 26 August 2014
 * Updated on 26 August 2014
 * */
add_filter('wp_get_attachment_url', 'gdml_getMediaURLFile', 10, 2);
function gdml_getMediaURLFile($url, $post_id)
{
    if (strpos($url, 'GDML-Mapping/'))
	{
		$attachmentMeta = wp_get_attachment_metadata($post_id);
		if (isset($attachmentMeta["GDMLID"]))
		{
			$url = 'https://drive.google.com/uc?id=' . $attachmentMeta["GDMLID"];
		}
		else
		{
			$folder = get_option('gdml_mapping_folder');
			$directory = wp_upload_dir();

			$url = str_replace($directory['baseurl'] . '/GDML-Mapping/', 'https://googledrive.com/host/' . $folder . '/', $url);
		}
	}
	
	//echo "<div>".$attachmentMeta["GDMLID"]."<br/></div>";	
		
    return $url;
}

/*
 * Embed java script and css style
 * Created on 26 August 2014
 * Updated on 5 September 2014
 * */
function gdml_adminScript()
{
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_script('jquery-ui-tooltip');
	wp_enqueue_script('jquery-ui-tabs');
	wp_enqueue_script('gdml-javascript',  plugin_dir_url(__FILE__)."js/gdml.js");
	wp_enqueue_style ('gdml-css', plugin_dir_url(__FILE__).'css/gdml.css');
	wp_enqueue_style ('jquery-ui-css', plugin_dir_url(__FILE__).'css/jquery-ui.css');
	//wp_enqueue_style ('jquery-ui-css-admin', plugin_dir_url( __FILE__ ).'css/jquery-ui-classic.css' );
}


/*
 * Add page on Wordpress Admin -> Media
 * Created on 26 August 2014
 * Updated on 26 August 2014
 * */
function gdml_media_actions()
{
	if (!is_admin())
	{
        wp_die("You are not authorized to view this page");
	}
	else
	{
		add_media_page("Google Drive Media Library", "Google Drive Media Library", 1, "google-drive-media-library-management", "gdml_media");
        add_action('admin_enqueue_scripts', 'gdml_adminScript');
	}
}

function gdml_media()
{
	include "google-drive-media-management.php";
}

add_action('admin_menu', 'gdml_media_actions');


/*
 * Add logic to process ajax post 
 * Created on 26 August 2014
 * Updated on 26 August 2014
 * */
function gdml_ajax_post()
{
	if (isset($_POST['mappingFolderNonce']))
    {
		$GDMLWebService = new GDMLWeb();
		$message = $GDMLWebService->gdml_saveMappingFolder($_POST['mappingFolder'], 
			$_POST['mappingFolderNonce'], 'mapping-folder-nonce');
		echo $message;
    }

	if (isset($_POST['mappingFileNonce']))
	{
		$GDMLWebService = new GDMLWeb();
		$folder = get_option('gdml_mapping_folder');
		$message = $GDMLWebService->gdml_saveMappingFile(
			$_POST['mappingFileName'],
			$_POST['mappingFileID'],
			$folder,
			$_POST['mappingFileDescription'],
			$_POST['mappingFileNonce'],
			'mapping-file-nonce'
		);
        echo $message;
    }

    die();
}

add_action('wp_ajax_gdml_action', 'gdml_ajax_post');
