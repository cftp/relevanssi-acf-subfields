<?php 

/*
Plugin Name: Build Relevanssi Index on Cron
Plugin URI: http://codeforthepeople.com/ir/build-relevanssi-index
Description: Sets a Cron action to continuously add to the Relevanssi index
Version: 0.1
Author: Code for the People Ltd
Author URI: http://codeforthepeople.com/
*/
 
/*  Copyright 2012 Code for the People Ltd
                _____________
               /      ____   \
         _____/       \   \   \
        /\    \        \___\   \
       /  \    \                \
      /   /    /          _______\
     /   /    /          \       /
    /   /    /            \     /
    \   \    \ _____    ___\   /
     \   \    /\    \  /       \
      \   \  /  \____\/    _____\
       \   \/        /    /    / \
        \           /____/    /___\
         \                        /
          \______________________/


This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA

*/

/**
 * Hooks the WP Cron action bri_cron_action 
 *
 * @return void
 **/
function bri_relevanssi_build_index() {
	if ( function_exists( 'relevanssi_build_index' ) ) {
		relevanssi_build_index( true );
	}
}
add_action( 'bri_cron_action', 'bri_relevanssi_build_index' );

/**
 * Hooks the WP cron_schedules action 
 *
 * @return void
 **/
function bri_cron_schedules( $schedules ) {
	$schedules[ 'five-minutes' ] = array( 'interval' => 5*60,      'display' => __( 'Every five minutes', 'brioc' ) );
	return $schedules;
}
add_action( 'cron_schedules', 'bri_cron_schedules' );

function bri_activate() {
	wp_schedule_event( time(), 'five-minutes', 'bri_cron_action' );
}
register_activation_hook( __FILE__, 'bri_activate' );

function bri_deactivate() {
	wp_clear_scheduled_hook( 'bri_cron_action' );
}
register_deactivation_hook( __FILE__, 'bri_deactivate' );

