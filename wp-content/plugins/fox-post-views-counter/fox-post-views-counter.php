<?php
/*
Plugin Name: (Fox) Post Views Counter
Description: Post Views Counter allows you to display how many times a post, page or custom post type had been viewed in a simple, fast and reliable way.
Version: 1.0
Author: WiThemes
Author URI: https://themeforest.net/user/withemes
Plugin URI: https://themeforest.net/item/the-fox-contemporary-magazine-theme-for-creators/11103012
Text Domain: post-views-counter
Domain Path: /languages

Post Views Counter
Copyright (C) 2014-2018, Digital Factory - info@digitalfactory.pl

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) )
	exit;

class Fox_Post_View_Counter {
    
    public $options;
    public $defaults = array(
        'general'	 => array(
            'post_types_count'		 => array( 'post' ),
            'counter_mode'			 => 'php',
            'post_views_column'		 => true,
            'time_between_counts'	 => array(
                'number' => 24,
                'type'	 => 'hours'
            ),
            'reset_counts'			 => array(
                'number' => 30,
                'type'	 => 'days'
            ),
            'flush_interval'		 => array(
                'number' => 0,
                'type'	 => 'minutes'
            ),
            'exclude'				 => array(
                'groups' => array(),
                'roles'	 => array()
            ),
            'exclude_ips'			 => array(),
            'strict_counts'			 => false,
            'restrict_edit_views'	 => false,
            'deactivation_delete'	 => false,
            'cron_run'				 => true,
            'cron_update'			 => true
        ),
        'display'	 => array(
            'label'				 => 'Post Views:',
            'post_types_display' => array( 'post' ),
            'page_types_display' => array( 'singular' ),
            'restrict_display'	 => array(
                'groups' => array(),
                'roles'	 => array()
            ),

            'position'			 => 'after',
            'display_style'		 => array(
                'icon'	 => true,
                'text'	 => true
            ),
            'link_to_post'		 => true,
            'icon_class'		 => 'dashicons-chart-bar'
        ),
        'version'	 => '1.2.14'
    );

    function __construct() {
        
        add_action( 'plugins_loaded', [ $this, 'post_view_no_conflict' ], 0 );
        
        // settings
        $this->options = array(
            'general'	 => array_merge( $this->defaults['general'], get_option( 'post_views_counter_settings_general', $this->defaults['general'] ) ),

            // // @withemes
            // 'display'	 => array_merge( $this->defaults['display'], get_option( 'post_views_counter_settings_display', $this->defaults['display'] ) )
            'display'	 => array_merge( $this->defaults['display'], $this->defaults['display'] )
        );
        
        register_activation_hook( __FILE__, [ $this, 'multisite_activation' ] );
        register_deactivation_hook( __FILE__, [ $this, 'multisite_deactivation' ] );
        
        // @withemes
        // check when new blog created
        add_action( 'wp_insert_site', array( $this, 'wpmu_site_inserted' ) );
        add_action( 'wp_delete_site', array( $this, 'wpmu_site_deleted' ) );
        
    }
    
    /**
     * delete the table when site deleted
     * @withemes
     */
    function wpmu_site_deleted( $site ) {

        global $wpdb;
        $current_blog_id = $wpdb->blogid;

        $blog_id = $site->blog_id;

        $activated_blogs = get_site_option( 'post_views_counter_activated_blogs', false, false );
        if ( ! $activated_blogs ) $activated_blogs = [];

        // switch to the new blog for a while to run activate single
        switch_to_blog( $blog_id );

        // delete table from database
        $wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->get_blog_prefix( $blog_id ) . 'post_views' );
        $this->remove_cache_flush();

        // remove from options
        foreach ( $activated_blogs as $k => $id ) {
            if ( $id == $blog_id ) unset( $activated_blogs[ $k ] );
        }

        // switch back to the current blog
        switch_to_blog( $current_blog_id );

        update_site_option( 'post_views_counter_activated_blogs', $activated_blogs, array() );

    }

    /**
     * create new tables when a new blog created
     * @withemes
     */
    function wpmu_site_inserted( $site ) {

        global $wpdb;
        $current_blog_id = $wpdb->blogid;

        $blog_id = $site->blog_id;

        $activated_blogs = get_site_option( 'post_views_counter_activated_blogs', false, false );
        if ( ! $activated_blogs ) $activated_blogs = [];

        // switch to the new blog for a while to run activate single
        switch_to_blog( $blog_id );
        $this->activate_single();
        $activated_blogs[] = (int) $blog_id;

        // switch back to the current blog
        switch_to_blog( $current_blog_id );

        update_site_option( 'post_views_counter_activated_blogs', $activated_blogs, array() );

    }
    
    /**
     * Multisite activation.
     * 
     * @global object $wpdb
     * @param bool $networkwide
     */
    public function multisite_activation( $networkwide ) {
        if ( is_multisite() && $networkwide ) {
            global $wpdb;

            $activated_blogs = array();
            $current_blog_id = $wpdb->blogid;

            /* @withemes - remove wpdb->prepare */
            $blogs_ids = $wpdb->get_col( 'SELECT blog_id FROM ' . $wpdb->blogs );

            foreach ( $blogs_ids as $blog_id ) {
                switch_to_blog( $blog_id );
                $this->activate_single();
                $activated_blogs[] = (int) $blog_id;
            }

            switch_to_blog( $current_blog_id );
            update_site_option( 'post_views_counter_activated_blogs', $activated_blogs, array() );
        } else
            $this->activate_single();
    }
    
    /**
     * Single site activation.
     * 
     * @global array $wp_roles
     */
    public function activate_single() {
        global $wpdb, $charset_collate;

        // required for dbdelta
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

        // create post views table
        dbDelta( '
            CREATE TABLE IF NOT EXISTS ' . $wpdb->prefix . 'post_views (
                id bigint unsigned NOT NULL,
                type tinyint(1) unsigned NOT NULL,
                period varchar(8) NOT NULL,
                count bigint unsigned NOT NULL,
                PRIMARY KEY  (type, period, id),
                UNIQUE INDEX id_type_period_count (id, type, period, count) USING BTREE,
                INDEX type_period_count (type, period, count) USING BTREE
            ) ' . $charset_collate . ';'
        );

        // add default options
        add_option( 'post_views_counter_settings_general', $this->defaults['general'], '', 'no' );
        // add_option( 'post_views_counter_settings_display', $this->defaults['display'], '', 'no' );
        add_option( 'post_views_counter_version', $this->defaults['version'], '', 'no' );

        // schedule cache flush
        $this->schedule_cache_flush();
    }

    /**
     * Multisite deactivation.
     * 
     * @global array $wpdb
     * @param bool $networkwide
     */
    public function multisite_deactivation( $networkwide ) {
        if ( is_multisite() && $networkwide ) {
            global $wpdb;

            $current_blog_id = $wpdb->blogid;

            /* @withemes - remove wpdb->prepare */
            $blogs_ids = $wpdb->get_col( 'SELECT blog_id FROM ' . $wpdb->blogs );

            if ( ! ($activated_blogs = get_site_option( 'post_views_counter_activated_blogs', false, false )) )
                $activated_blogs = array();

            foreach ( $blogs_ids as $blog_id ) {
                switch_to_blog( $blog_id );
                $this->deactivate_single( true );

                if ( in_array( (int) $blog_id, $activated_blogs, true ) )
                    unset( $activated_blogs[array_search( $blog_id, $activated_blogs )] );
            }

            switch_to_blog( $current_blog_id );
            update_site_option( 'post_views_counter_activated_blogs', $activated_blogs );
        } else
            $this->deactivate_single();
    }

    /**
     * Single site deactivation.
     * 
     * @global array $wp_roles
     * @param bool $multi
     */
    public function deactivate_single( $multi = false ) {
        if ( $multi ) {
            $options = get_option( 'post_views_counter_settings_general' );
            $check = $options['deactivation_delete'];
        } else
            $check = $this->options['general']['deactivation_delete'];

        // delete default options
        if ( $check ) {
            delete_option( 'post_views_counter_settings_general' );
            delete_option( 'post_views_counter_settings_display' );

            global $wpdb;

            // delete table from database
            $wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'post_views' );
        }

        // remove schedule
        wp_clear_scheduled_hook( 'pvc_reset_counts' );
        remove_action( 'pvc_reset_counts', array( Post_Views_Counter()->cron, 'reset_counts' ) );

        $this->remove_cache_flush();
    }
    
    /**
     * Schedule cache flushing if it's not already scheduled.
     * 
     * @param bool $forced
     */
    public function schedule_cache_flush( $forced = true ) {
        if ( $forced || ! wp_next_scheduled( 'pvc_flush_cached_counts' ) )
            wp_schedule_event( time(), 'post_views_counter_flush_interval', 'pvc_flush_cached_counts' );
    }

    /**
     * Remove scheduled cache flush and the corresponding action.
     */
    public function remove_cache_flush() {
        wp_clear_scheduled_hook( 'pvc_flush_cached_counts' );
        remove_action( 'pvc_flush_cached_counts', array( Post_Views_Counter()->cron, 'flush_cached_counts' ) );
    }
    
    function post_view_no_conflict() {
        
        if ( class_exists( 'Post_Views_Counter' ) ) {
        
            add_action( 'admin_notices', [ $this, 'notification_require_deactivate' ] );

        } else {

            require_once 'post-views-counter.php';

        }
        
    }
    
    function notification_require_deactivate() {
        
        echo '<div class="notice notice-error is-dismissible"><p>' .
        'IMPORTANT: Since FOX v4.0, we have implemented <strong style="color:blue">(Fox) Post View Counter</strong> for better incorporation with FOX. We strongly recommend you to deactivate <strong style="color:red">Post Views Counter</strong> plugin.<br> No worries, all of your view counts so far are still unchanged.'
        . '</p></div>';
    
        return;
        
    }

}
new Fox_Post_View_Counter();