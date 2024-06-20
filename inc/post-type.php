<?php

register_post_type('meetings', array(
    'labels' => array(
        'name'     => 'Meetings',
        'singular_name' => 'Meeting',
        'add_new' => __( 'Add New' ),
        'add_new_item' => __( 'Add new meeting' ),
        'view_item' => 'View meeting',
        'edit_item' => 'Edit meeting',
        'new_item' => __('New meeting'),
        'view_item' => __('View meeting'),
        'search_items' => __('Search meetings'),
        'not_found' =>  __('No meetings found'),
        'not_found_in_trash' => __('No meetings found in Trash'),
    ),
    'public' => true,
    'show_in_menu' => true,
    'show_ui' => true,
    'menu_position' => 21,
    'supports' => array('title', 'editor', 'thumbnail', 'page-attributes', ),
    /* disable public details pages as they will only be shown using AJAX query by location
    'publicly_queryable' => false,
    'exclude_from_search' => true,
    */
    'exclude_from_search' => false,
    'rewrite' => array("slug" => "meeting"),
    'query_var' => 'meeting',
));
