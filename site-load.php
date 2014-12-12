<?php
/**
 * Created by PhpStorm.
 * User: Rince
 * Date: 2014/12/10
 * Time: 0:17
 */

define( 'ABS_PATH', dirname(__FILE__) . '/' );

error_reporting( E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_ERROR | E_WARNING | E_PARSE | E_USER_ERROR | E_USER_WARNING | E_RECOVERABLE_ERROR );

if ( file_exists( ABS_PATH . 'config/config.php') ) {
	require_once( ABS_PATH . 'config/config.php' );
}

function auto_loader($class){
	if(is_file( ABS_PATH . 'include/' . $class . '.php')){
		require_once( ABS_PATH . 'include/' . $class . '.php');
	}
}

spl_autoload_register('auto_loader');