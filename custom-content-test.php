<?php
/*
Plugin Name: Custom Content Test
Version: 0.2
Description: A couple of extra post types and taxonomies to test various other plugins.
Author: Keith Drakard
Author URI: https://drakard.com/
*/


if (! defined('WPINC')) die;

class CustomContentTest {

	public function __construct() {
		add_action('init', array($this, 'custom_post_types'));
		add_action('init', array($this, 'custom_taxonomies'));
		add_action('pre_get_posts', array($this, 'add_cpt_to_loop_and_feed'));
	}

	public function custom_post_types() {
       	register_post_type('custom_post_type', array(
			'labels'			=> array(
										'name'			=> __('Custom Post Types'),
										'singular_name'	=> __('Custom Post Type'),
									),
			'public'			=> true,
			'hierarchical'		=> false,
			'supports'			=> array('title', 'editor', 'thumbnail', 'comments'), 
			'has_archive'		=> true,
			'menu_position'		=> 20,
			'show_in_admin_bar'	=> true,
			'show_in_rest'		=> true,
			'menu_icon'			=> 'dashicons-admin-users',
       	));
	}


	public function custom_taxonomies() {
		register_taxonomy('hierarchical_term', 'post', array(
			'label'				=> __('Hierarchical Terms'),
			'singular_label'	=> __('Hierarchical Term'),
			'hierarchical'		=> true,
			'query_var'			=> true,
			'public'			=> true,
			'show_admin_column'	=> true,
			'show_in_rest'		=> true,
			'show_ui'			=> true,		));

		register_taxonomy('custom_tag', 'post', array(
			'label'				=> __('Custom Tags'),
			'singular_label'	=> __('Custom Tag'),
			'hierarchical'		=> false,
			'query_var'			=> true,
			'public'			=> true,
			'show_admin_column'	=> true,
			'show_in_rest'		=> true,
			'show_ui'			=> true,
		));

		// add taxonomy
		register_taxonomy_for_object_type('hierarchical_term', 'page');
		register_taxonomy_for_object_type('hierarchical_term', 'post');
		register_taxonomy_for_object_type('custom_tag', 'page');
		register_taxonomy_for_object_type('custom_tag', 'post');

		register_taxonomy_for_object_type('post_tag', 'custom_post_type');
		register_taxonomy_for_object_type('custom_tag', 'custom_post_type');
		register_taxonomy_for_object_type('category', 'custom_post_type');
	}

	public function add_cpt_to_loop_and_feed($query) {
		if (! is_admin()) {
			$post_types = $query->get('post_type');
			if ($post_types != '') {
				if (is_string($post_types)) $post_types = [$post_types];
				$post_types = array_merge($post_types, array('custom_post_type'));
				$query->set('post_type', $post_types);
			}
			
		}
		return $query;
	}

}

$CustomContentTest = new CustomContentTest();
