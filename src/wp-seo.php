<?php
   defined('ABSPATH') || die('');
   
add_action( 'manage_post_posts_custom_column','rytme_yoast_post', 11, 2 );
add_action( 'manage_page_posts_custom_column','rytme_yoast_post', 11, 2 );	
function rytme_yoast_post($title,$id) {

	if ($title == 'wpseo-title' || $title == 'wpseo-metadesc'){
			echo '<br><button class="rytme-'.$title.'" data-id="'.$id.'" >'.__('rytr me', 'rytme').'</button>';
	}
}	