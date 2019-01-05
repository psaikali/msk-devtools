<?php

namespace MSK\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Check if a string contains ALL words from an array
 *
 * @param array $array Array of strings
 * @return boolean
 */
function string_contains_all_words( $string, $array ) {
	$missed = false;

	foreach ( $array as $word ) {
		if ( strpos( $string, $word ) !== false ) {
			continue;
		} else {
			$missed = true;
			break;
		}
	}

	return ! $missed;
}

/**
 * Does a user have a specific role ?
 *
 * @return boolean
 */
function user_has_role( $role = 'subscriber' ) {
	if ( is_user_logged_in() ) {
		$user  = wp_get_current_user();
		$roles = (array) $user->roles;
		return in_array( $role, $roles, true );
	}

	return false;
}

/**
 * Get readable memory usage
 *
 * @return void
 */
function memory_usage( $size = null ) {
	if ( ! $size ) {
		$size = memory_get_usage();
	}

	$unit = array( 'b', 'kb', 'mb', 'gb', 'tb', 'pb' );
	return round( $size / pow( 1024, ( $i = floor( log( $size, 1024 ) ) ) ), 2 ) . ' ' . $unit[ $i ];
}
