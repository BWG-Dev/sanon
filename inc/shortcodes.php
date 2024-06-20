<?php

function find_meeting_shortcode($atts, $content = null)
{
	//ob_start();
		return '<a class="btn-primary" href="/find-a-meeting/">Find a Meeting</a>';
	//$output = ob_get_contents();
	//ob_end_clean();

	return $output;
}

add_shortcode('find_meeting', 'find_meeting_shortcode');

function image_background_shortcode($atts, $content = null) 
{
	ob_start();
		echo '<div class="purple-image-container">'. $content .'</div>';
	$output = ob_get_contents();
	ob_end_clean();

	return $output;
}

add_shortcode('image_background', 'image_background_shortcode');

