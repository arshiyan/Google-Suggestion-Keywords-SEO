<?php

/*
 * Plugin Name: Google Suggestion Keywords SEO (gsks)
 * Description: Add automatically 10 keywords to header from Google suggestion
* Version: 1.0
 * Author: Mohammad Javad Arshiyan
 * Author URI: http://arshiyan.ir
 */

// Don't call the file directly
if (!defined('ABSPATH')) exit;


//Add to header some keywords (meta)
function gsks_add_meta_tags() {
  echo '<meta name="keywords" content="'.gsks_getkeyword(get_the_title()).'">';
}
add_action('wp_head', 'gsks_add_meta_tags');

//Get google suggestions
function gsks_getkeyword($keyword) 
{
    $keywords = array();
    $data = gsks_get_data('http://suggestqueries.google.com/complete/search?output=firefox&client=firefox&hl=en-US&q='.urlencode($keyword));
   
    if (($data = json_decode($data, true)) !== null) {
        $keywords = $data[1];
    }
    
    $string = '';
    $i = 1;
    foreach ($keywords as $k) 
    {
        $string .= $k . ', ';
        if ($i++ == 10) break;
    }
    return $string;
}

//curl for get data from google
function gsks_get_data($url) {
	$ch = curl_init();
	$timeout = 5;
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
	$data = curl_exec($ch);
	curl_close($ch);
	return $data;
}