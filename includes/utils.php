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

/**
 * Remove anonymous object filter
 *
 * @link https://wordpress.stackexchange.com/questions/57079/how-to-remove-a-filter-that-is-an-anonymous-object#57088
 * @param string $tag
 * @param string $class
 * @param string $method
 * @return void
 */
function remove_class_hook( $tag, $class, $method ) {
	if ( ! isset( $GLOBALS['wp_filter'][ $tag ] ) ) {
		return;
	}

	$filters = $GLOBALS['wp_filter'][ $tag ];

	if ( empty ( $filters ) ) {
		return;
	}

	foreach ( $filters as $priority => $filter ) {
		foreach ( $filter as $identifier => $function ) {
			if ( is_array( $function)
				and is_a( $function['function'][0], $class )
				and $method === $function['function'][1]
			) {
				remove_filter(
					$tag,
					array ( $function['function'][0], $method ),
					$priority
				);
			}
		}
	}
}
