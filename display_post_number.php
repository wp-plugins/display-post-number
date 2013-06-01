<?php
/*
Plugin Name:	Display Post Number
Plugin URI:		http://wpcos.com/
Description:	サイトのページで表示する記事件数を設定するプラグイン
Version:		0.2
Author:			wpcos
Author URI:		http://wpcos.com/
*/

/*  Copyright 2013 wpcos (email : )

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

function display_post_number_setteing($query) {
	if ( !is_admin() && $query->is_main_query() && $query->is_home() ) {
		$query->set('posts_per_page', 'posts_per_page');
		
		$categories = category_list(); 
		foreach($categories as $category) :
			if( get_option( 'dn_posts_home_ex_' . $category->term_id ) == 1 ) {
				$cat_id .= (-$category->term_id . ',');
			}
		endforeach;
		$query->set('cat', $cat_id);
	}
	else if ( !is_admin() && $query->is_main_query() && is_category() ) {
		$query->set('posts_per_page', get_option('dn_posts_category') );
	}
	else if ( !is_admin() && $query->is_main_query() && is_archive() ) {
		$query->set('posts_per_page', get_option('dn_posts_archive') );
	}
	else if ( !is_admin() && $query->is_main_query() && is_search() ) {
		$query->set('posts_per_page', get_option('dn_posts_search') );
	}
	else if ( !is_admin() && $query->is_main_query() && is_tag() ) {
		$query->set('posts_per_page', get_option('dn_posts_tag') );
	}
}
add_action('pre_get_posts', 'display_post_number_setteing');

// プラグイン有効化時の設定
function display_number_active_setting() {
	add_option('dn_posts_category', 5, '', no);
	add_option('dn_posts_archive', 5, '', no);
	add_option('dn_posts_search', 5, '', no);
	add_option('dn_posts_tag', 5, '', no);
}
register_activation_hook( __FILE__, 'display_number_active_setting');
 
function display_number_settings_init() {
	add_settings_section('display_number_section',
		'Display Number',//表示設定で表示される見出し
		'display_number_section_callback',
		'reading');


	add_settings_field('dn_posts_home_ex',
		'トップページ表示除外カテゴリー',
		'home_ex_field_callback',
		'reading',
		'display_number_section');
		
	add_settings_field('dn_posts_category',
		'カテゴリー記事表示数',
		'category_number_field_callback',
		'reading',
		'display_number_section');
	
	add_settings_field('dn_posts_archive',
		'アーカイブ記事表示数',
		'archive_number_field_callback',
		'reading',
		'display_number_section');
		
	add_settings_field('dn_posts_search',
		'検索記事表示数',
		'search_number_field_callback',
		'reading',
		'display_number_section');

	add_settings_field('dn_posts_tag',
		'タグ記事表示数',
		'tag_number_field_callback',
		'reading',
		'display_number_section');

	$categories = category_list();
	foreach($categories as $category) :
		register_setting('reading','dn_posts_home_ex_' . $category->term_id );
	endforeach;

	register_setting('reading','dn_posts_category');
	register_setting('reading','dn_posts_archive');
	register_setting('reading','dn_posts_search');
	register_setting('reading','dn_posts_tag');
}
add_action('admin_init', 'display_number_settings_init');
 
function display_number_section_callback() {
	echo '<p>投稿記事の表示件数、除外カテゴリーを設定</p>';
}
 
function home_ex_field_callback() {
	$count = 0;
	$categories = category_list();
	foreach($categories as $category) :
		echo '<input name="dn_posts_home_ex_' . $category->term_id . '" type="checkbox" id="dn_posts_home_ex_' . $category->term_id . '" value="1"' . checked( get_option( 'dn_posts_home_ex_' . $category->term_id ), 1, false) . ' /> <label for="dn_posts_home_ex_' . $category->term_id . '">'. $category->cat_name . '</label>　';
		
		$count++;
		if( $count % 5 == 0 ) {
			echo '<br />';
		}	
		if( get_option( 'dn_posts_home_ex_' . $category->term_id ) == 1 ) {
				$j .= (-$category->term_id . ',');
		}
	endforeach;	
}
 
function category_number_field_callback() {
	echo '<input name="dn_posts_category" type="number" step="1" min="1" max="15" id="dn_posts_category" value="' . get_option( 'dn_posts_category' ) . '" class="small-text" /> ' . __( 'posts' );
}
 
function archive_number_field_callback() {
	echo '<input name="dn_posts_archive" type="number" step="1" min="1" id="dn_posts_archive" value="' . get_option( 'dn_posts_archive' ) . '" class="small-text" /> ' . __( 'posts' );
}

function search_number_field_callback() {
	echo '<input name="dn_posts_search" type="number" step="1" min="1" id="dn_posts_search" value="' . get_option( 'dn_posts_search' ) . '" class="small-text" /> ' . __( 'posts' );
}

function tag_number_field_callback() {
	echo '<input name="dn_posts_tag" type="number" step="1" min="1" id="dn_posts_tag" value="' . get_option( 'dn_posts_tag' ) . '" class="small-text" /> ' . __( 'posts' );
}

function category_list() {
	return get_categories(array('child_of' => 0));
}

if( get_option('dn_posts_category') == null ) {
 update_option('dn_posts_category', 1);
}
if( get_option('dn_posts_archive') == null ) {
 update_option('dn_posts_archive', 1);
}
if( get_option('dn_posts_search') == null ) {
 update_option('dn_posts_search', 1);
}
if( get_option('dn_posts_tag') == null ) {
 update_option('dn_posts_tag', 1);
}
?>