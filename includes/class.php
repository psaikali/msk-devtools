<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( class_exists( 'MSK' ) ) {
	return;
}

/**
 * Proxy class which lets us call static methods directly
 */
class MSK {
	public static function debug( ...$args ) {
		return \MSK\Debug\debug( ...$args );
	}

	public static function pp( ...$args ) {
		return \MSK\Debug\pp( ...$args );
	}

	public static function inspect_hooks( ...$args ) {
		return \MSK\Debug\inspect_hooks( ...$args );
	}

	public static function user_has_role( ...$args ) {
		return \MSK\Utils\user_has_role( ...$args );
	}

	public static function memory_usage( ...$args ) {
		return \MSK\Utils\memory_usage( ...$args );
	}
}
