<?php
/*
Plugin Name: WP Betting Stats
Plugin URI: http://www.abc-betting.com/wpbettingstats
Description: This plugin adds a widget that can keep the statistics of a betting blog (e.g. the amount of units, the number of bets so far, the percentage yield, etc.). Currently the user needs to update the statistics manually using the control panel form.
Version: 0.1
Author: Kyriakos Anastasakis
Author URI: http://kyriakos.anastasakis.net
*/?>
<?php
/*  Copyright 2009  Kyriakos Anastasakis

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
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/?>
<?php //error_reporting(E_ALL);
$widget_name = 'wp_betting_stats';
$widget_name_view = 'WP Betting Statistics';

add_action("plugins_loaded", "init_wp_betting_stats");

function init_wp_betting_stats(){
	register_sidebar_widget($widget_name_view, 'init_wp_betting_stats_widget');
}


/*
 * This function is responsible for the main widget user interface.
 */
function init_wp_betting_stats_widget($args){
    echo $args['before_widget'];
    echo $args['before_title'] . 'Statistics since:<br/>' . get_option('wp_betting_start_date') . $args['after_title'];
    echo'<table border="1">';
    echo '<tr>';
    echo '<td>Current Units:' . get_option('wp_betting_units') . '</td>' ;
    echo '</tr>';
    echo '<tr>';
    echo '<td>Yield:'; 
    if (get_option('wp_betting_yield') >= 0) echo '<font color="green">+' . get_option('wp_betting_yield') . '%</font>';
    else echo '<font color="red">' . get_option('wp_betting_yield') . '%</font>'  ;
    echo '</tr>';
    echo '<tr>';
    echo '<td>Games Won/Total Games:' . get_option('wp_betting_games_won') . '/' . get_option('wp_betting_games')  . '</td>';
    echo '</tr>';
    echo '</table>';
    echo $args['after_widget'];
}

/* FROM HERE ON THE CODE HAS TO DO WITH THE ADMIN MENU */
add_action('admin_menu','wp_betting_stats_admin_menu');

function wp_betting_stats_admin_menu(){
	add_options_page('WP Betting Stats Options','WP Betting Stats',8,__FILE__,'wp_betting_stats_admin');
}

/*
 * This function is responsible for generating the GUI for the admin environment.
 */
function wp_betting_stats_admin(){
	echo '<h1>WP Betting Stats Options</h1><br/>';
	echo '<form method="post" action="options.php">';
		wp_nonce_field('update-options');
	echo '<table class="form-table">';
	echo '<tr valign="top">';
	echo '<th scope="row">Date you started gathering statistics:</th>';
	echo '<td><input type="text" name="wp_betting_start_date" value="' . get_option('wp_betting_start_date') . '" /></td>';
	echo '</tr>';

	echo '<tr valign="top">';
	echo '<th scope="row">Current units:</th>';
	echo '<td><input type="text" name="wp_betting_units" value="' . get_option('wp_betting_units') . '" /></td>';
	echo '</tr>';

	echo '<tr valign="top">';
	echo '<th scope="row">Yield (Percentage):</th>';
	echo '<td><input type="text" name="wp_betting_yield" value="' . get_option('wp_betting_yield') . '" /></td>';
	echo '</tr>';

	echo '<tr valign="top">';
	echo '<th scope="row">Games bet so far:</th>';
	echo '<td><input type="text" name="wp_betting_games" value="' . get_option('wp_betting_games') . '" /></td>';
	echo '</tr>';

	echo '<tr valign="top">';
	echo '<th scope="row">Correct predictions so far:</th>';
	echo '<td><input type="text" name="wp_betting_games_won" value="' . get_option('wp_betting_games_won') . '" /></td>';
	echo '</tr>';


	echo '</table>';


	echo '<input type="hidden" name="action" value="update" />';
	echo '<input type="hidden" name="page_options" value="wp_betting_start_date,wp_betting_units,wp_betting_yield,wp_betting_games,wp_betting_games_won" />';

	echo '<p class="submit">';
	echo '<input type="submit" class="button-primary" value="Save Changes""/>';
	echo '</p>';

	echo '</form>';
}

/* FROM HERE ON CODE THAT IS ECECUTED ON ACTIVATION*/

/*
 * The first time the plugin is activated add the default values
 */ 

register_activation_hook( __FILE__, 'wp_betting_stats_activate');

function wp_betting_stats_activate(){
	add_option('wp_betting_units',1000,'',yes);
	add_option('wp_betting_yield',0,'',yes);
	add_option('wp_betting_games',0,'',yes);
	add_option('wp_betting_games_won',0,'',yes);	
}?>
