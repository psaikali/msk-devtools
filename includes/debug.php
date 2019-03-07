<?php

namespace MSK\Debug;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Pretty print
 *
 * @param mixed $arr
 * @param boolean $admin
 * @param boolean $echo
 * @return void
 */
function pp( $arr, $admin = false, $echo = true ) {
	$output = '';

	$id = "debug-pp-" . rand( 0, 1000 );

	$extra_class = ( $admin ) ? 'admin' : '';

	if ( $admin && current_user_can( 'manage_options' ) ) {
		$output .= "<a class='debug-pp-link-debug' href='#" . $id . "'>debug</a>";
		$output .= "<a class='debug-pp-link-close' href='#'>x</a>";
	}

	if ( ( $admin && current_user_can( 'manage_options' ) ) || ! $admin ) {
		$output .= "<pre style='text-align:left;' class='msk-debug-pp " . $extra_class . "' id='" . $id . "'><code>";
		$output .= print_r( $arr, true );
		$output .= "</code></pre>";
	}

	if ( ! did_action( 'msk_debug_load_styles' ) ) {
		do_action( 'msk_debug_load_styles' );
	}

	if ( $echo ) {
		echo $output;
	} else {
		return $output;
	}
}

/**
 * Load styles for the debug boxes
 *
 * @return void
 */
function debug_load_styles() {
	?>
	<style>
		pre.msk-debug-pp {
			transition: .5s all ease-out; 
			background: #c9eef6; 
			padding:1em; 
			margin:1em; 
			position:relative; 
			border-radius:4px; 
			overflow-x:scroll; 
			text-align:left;
			line-height: 1.4;
			font-size: 16px;
			border:3px solid #bbdde4;
		}

		pre.msk-debug-pp code {
			white-space: inherit;
			font-family:'PT Mono';
			font-weight:500;
		}
	</style>
	<?php
}
add_action( 'msk_debug_load_styles', __NAMESPACE__ . '\\debug_load_styles' );

/**
 * Debug stuff in debug.log file
 */
function debug( ...$logs ) {
	if ( defined( 'WP_DEBUG_LOG' ) && true === WP_DEBUG_LOG ) {
		$emojis = [ '🔎 ', '💡 ', '🔦 ', '🔌 ', '🔍 ', '🔧 ', '🔩 ', '🔨 ', '🚧 ', '⚡ ' ];
		$title = str_repeat( $emojis[ array_rand( $emojis ) ], 3 );

		foreach ( $logs as $log ) {
			error_log( '█░█░█░█░█░█░█░█░█░█░█░█░█░█ ' . $title . ' █░█░█░█░█░█░█░█░█░█░█░█░█░█' );
			if ( is_array( $log ) || is_object( $log ) ) {
				error_log( print_r( $log, true ) );
			} else {
				error_log( $log );
			}
		}
	}
}

/**
 * Inspect/list functions called on hooks containing specific term
 *
 * @param array Array of terms that the hook should contain
 * @return array
 */
function inspect_hooks( $terms = [ 'wp_' ] ) {
	global $wp_filter;
	$related_hooks = [];
	$total         = 0;

	if ( ! is_array( $terms ) ) {
		$terms = [ $terms ];
	}

	foreach ( $wp_filter as $key => $val ) {
		if ( \MSK\Utils\string_contains_all_words( $key, $terms ) ) {
			foreach ( $val->callbacks as $priority ) {
				foreach ( $priority as $callback ) {
					foreach ( $callback as $function => $function_data ) {
						if ( $function !== 'function' ) {
							continue;
						}

						if ( is_array( $function_data ) ) {
							$method = $function_data[1];

							if ( is_string( $function_data[0] ) ) {
								$classname = $function_data[0];
							} else {
								$classname = get_class( $function_data[0] );
							}

							if ( method_exists( $function_data[0], $method ) ) {
								$reflection    = new \ReflectionMethod( $classname, $method );
								$function_name = $classname . '->' . $method;
								$related_hooks[ $key ][] = sprintf( '<strong>%1$s</strong> in <em>%2$s</em> <small>L%3$d</small>', $function_name, str_replace( ABSPATH, '', $reflection->getFileName() ), $reflection->getStartLine() );
							} else {
								$function_name = $classname . '->' . $method;
								$related_hooks[ $key ][] = sprintf( '<strong>%1$s</strong> (method not found)', $function_name );
							}
						} else {
							try {
								$reflection = new \ReflectionFunction( $function_data );
							} catch (\ReflectionException $e) {
								continue;
							}

							if ( $function_data instanceof \Closure ) {
								$related_hooks[ $key ][] = sprintf( 'closure in <em>%1$s</em> <small>L%2$d</small>', str_replace( ABSPATH, '', $reflection->getFileName() ), $reflection->getStartLine() );
							} else {
								$related_hooks[ $key ][] = sprintf( '<strong>%3$s</strong> in <em>%1$s</em> <small>L%2$d</small>', str_replace( ABSPATH, '', $reflection->getFileName() ), $reflection->getStartLine(), $function_data );
							}
						}

						$total++;
					}
				}
			}
		}
	}

	$related_hooks['total'] = $total;

	return $related_hooks;
}
