<?php

/**
 * Created by PhpStorm.
 * User: Saeid
 * Date: 3/28/2018
 * Time: 10:02 AM
 */
class theme_cache {
	public static function get( $cache_name ) {
		get_transient($cache_name);
	}
	public static function save( $cache_name,$cache_value,$expire_date=DAY_IN_SECONDS ) {
		set_transient($cache_name,$cache_value,$expire_date);
	}

	public static function delete( $cache_name ) {
		delete_transient($cache_name);
	}
}