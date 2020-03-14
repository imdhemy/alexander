<?php


namespace Tests;

/**
 * Trait InitWordpress
 * @package Tests
 */
trait InitWordpress
{
    public function initWp(){
        $_tests_dir = rtrim(__DIR__ . '/wordpress-develop/tests/phpunit');
        if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
            echo "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL;
            exit( 1 );
        }
        require_once $_tests_dir . '/includes/bootstrap.php';
    }
}