<?php
/*
Plugin Name: Read More Read Less
Plugin URI:  https://www.imskh.com/projects/readmorereadless.zip
Description: So easy and lightweight plugin that helps you to show some portion of large data and show and hide on button click. Also if there is any URL in that link, it will take you there. Place this code in your template file &lt;label class="redirectTo" style="display: none">Here you can provide the link where you want to redirect the page and its optional. If its empty then read more read less functionality will work.&lt;/label> and in the between the tag set the URL you want to redirect the user and use this shortcode to "[readmoreless maxchar=50]Here you can put your string[/readmoreless]" to show the data and in between the tag set the string you want to show. maxchar is the variable where you set the maximum words you want to show when page loads.
Version:     1.0.0
Author:      Shashank Kumar
Author URI:  https://www.imskh.com
License:     GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt
Text Domain: read-more-read-less
Domain Path: /languages
*/
// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
/**
 * This function divides the string as per the given max_char limit and then sets to their respective tags
 *
 * @param array $rmrl_atts
 * @param null $rmrl_content
 *
 * @return string
 */
function rmrl_show_more( $rmrl_atts = [], $rmrl_content = null ) {
	$rmrl_max_char = 200;
	if ( isset( $rmrl_atts['maxchar'] ) ) {
		$rmrl_max_char = $rmrl_atts['maxchar'];
	}
	if ( strlen( $rmrl_content ) <= $rmrl_max_char ) {
		return $rmrl_content;
	}
	$rmrl_content    = preg_replace( "/<img[^>]+\>/i", "(image) ", $rmrl_content );
	$rmrl_match1     = rmrl_close_tags( substr( $rmrl_content, 0, $rmrl_max_char ) );
	$rmrl_match2     = rmrl_close_tags( substr( $rmrl_content, $rmrl_max_char ) );
	$rmrl_structure1 = "<span class='rmrl_pre_data'>" . $rmrl_match1 . "</span><span class='rmrl_post_data'>" . $rmrl_match2 . "</span>";
	$rmrl_structure2 = '<strong class="rmrl_read_more"><a>&nbsp;Read More... </a></strong><strong class="rmrl_read_less"><a>&nbsp;Read Less... </a></strong>';

	return $rmrl_structure1 . $rmrl_structure2;
}

add_shortcode( 'readmoreless', 'rmrl_show_more' );

/**
 * Initialises the script and style files for the plugin
 */
function rmrl_scripts_styles() {
	wp_register_style( "rmrl_style", plugin_dir_url( __FILE__ ) . 'css/style.css' );
	wp_register_script( "rmrl_script", plugin_dir_url( __FILE__ ) . 'js/script.js', "", "", true );
	wp_enqueue_style( 'rmrl_style' );
	wp_enqueue_script( 'rmrl_script' );
}

add_action( 'wp_enqueue_scripts', 'rmrl_scripts_styles' );
/**
 * This function automatically opens/closes the rmrl_html tags depending on their respective rmrl_html tags opening/closing
 *
 * @param $rmrl_html
 *
 * @return string
 */
function rmrl_close_tags( $rmrl_html ) {
	preg_match_all( '#<([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $rmrl_html, $rmrl_result );
	$rmrl_opened_tags = $rmrl_result[1];
	preg_match_all( '#</([a-z]+)>#iU', $rmrl_html, $rmrl_result );
	$rmrl_closed_tags = $rmrl_result[1];
	$rmrl_len_opened = count( $rmrl_opened_tags );
	$rmrl_len_closed = count( $rmrl_closed_tags );
	if ( count( $rmrl_closed_tags ) == $rmrl_len_opened ) {
		return $rmrl_html;
	}
	if ( $rmrl_len_opened > $rmrl_len_closed ) {
		$rmrl_opened_tags = array_reverse( $rmrl_opened_tags );
		for ( $i = 0; $i < $rmrl_len_opened; $i ++ ) {
			if ( ! in_array( $rmrl_opened_tags[ $i ], $rmrl_closed_tags ) ) {
				$rmrl_html .= '</' . $rmrl_opened_tags[ $i ] . '>';
			} else {
				unset( $rmrl_closed_tags[ array_search( $rmrl_opened_tags[ $i ], $rmrl_closed_tags ) ] );
			}
		}
	} else {
		$rmrl_closed_tags = array_reverse( $rmrl_closed_tags );
		for ( $i = 0; $i < $rmrl_len_closed; $i ++ ) {
			if ( ! in_array( $rmrl_closed_tags[ $i ], $rmrl_opened_tags ) ) {
				$rmrl_html = '<' . $rmrl_closed_tags[ $i ] . '>' . $rmrl_html;
			} else {
				unset( $rmrl_opened_tags[ array_search( $rmrl_closed_tags[ $i ], $rmrl_opened_tags ) ] );
			}
		}
	}

	return $rmrl_html;
}