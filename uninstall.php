<?php
include_once 'display_post_number.php';

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit ();

delete_option( 'dn_posts_category' );
delete_option( 'dn_posts_archive' );
delete_option( 'dn_posts_search' );
delete_option( 'dn_posts_tag' );

$categories = category_list();  
foreach($categories as $category) :
	delete_option( 'dn_posts_home_ex_' . $category->term_id );
endforeach;