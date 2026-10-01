<?php
	defined('BASEPATH') OR exit('No direct script access allowed');

	$active_group  = 'production';
	$query_builder = TRUE;

	$db['default'] = array(
		'dsn'          => '',
		'hostname'     => 'localhost',
		'username'     => 'rsudpmco_simrs',
		'password'     => '3b(p@5#_uzT,',
		'database'     => 'rsudpmco_simrs',
		'dbdriver'     => 'mysqli',
		'dbprefix'     => '',
		'pconnect'     => FALSE,
		'db_debug'     => (ENVIRONMENT !== 'production'),
		'cache_on'     => FALSE,
		'cachedir'     => '',
		'char_set'     => 'utf8',
		'dbcollat'     => 'utf8_general_ci',
		'swap_pre'     => '',
		'encrypt'      => FALSE,
		'compress'     => FALSE,
		'stricton'     => FALSE,
		'failover'     => array(),
		'save_queries' => TRUE
	);

	$db['development'] = array(
		'dsn'          => '',
		'hostname'     => '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=192.168.200.182)(PORT=1521))(CONNECT_DATA=(SERVER=DEDICATED) (SERVICE_NAME=DEVRSPM)))',
		'username'     => 'SIMRS_MANAGER',
		'password'     => 'SIMRS_SYSTEM',
		'database'     => '',
		'dbdriver'     => 'oci8',
		'dbprefix'     => '',
		'pconnect'     => FALSE,
		'db_debug'     => (ENVIRONMENT !== 'development'),
		'cache_on'     => FALSE,
		'cachedir'     => '',
		'char_set'     => 'utf8',
		'dbcollat'     => 'utf8_general_ci',
		'swap_pre'     => '',
		'encrypt'      => FALSE,
		'compress'     => FALSE,
		'stricton'     => FALSE,
		'failover'     => array(),
		'save_queries' => TRUE
	);

	$db['production'] = array(
		'dsn'          => '',
		'hostname'     => '(DESCRIPTION=(ADDRESS=(PROTOCOL=TCP)(HOST=rac-scan.rsudpm.local)(PORT=1521))(CONNECT_DATA=(SERVER=DEDICATED) (SERVICE_NAME=PRODPM)))',
		'username'     => 'SIMRS_MANAGER',
		'password'     => 'SIMRS_SYSTEM',
		'database'     => '',
		'dbdriver'     => 'oci8',
		'dbprefix'     => '',
		'pconnect'     => FALSE,
		'db_debug'     => (ENVIRONMENT !== 'production'),
		'cache_on'     => FALSE,
		'cachedir'     => '',
		'char_set'     => 'utf8',
		'dbcollat'     => 'utf8_general_ci',
		'swap_pre'     => '',
		'encrypt'      => FALSE,
		'compress'     => FALSE,
		'stricton'     => FALSE,
		'failover'     => array(),
		'save_queries' => TRUE
	);
	 
    $db['pg_prod']['hostname'] = '192.168.200.40';
    $db['pg_prod']['username'] = 'user_testing';
    $db['pg_prod']['password'] = 'Testing123!';
    $db['pg_prod']['database'] = 'antriandb';
    $db['pg_prod']['dbdriver'] = 'postgre';
    $db['pg_prod']['dbprefix'] = '';
    $db['pg_prod']['pconnect'] = FALSE;
    $db['pg_prod']['db_debug'] = TRUE;
    $db['pg_prod']['cache_on'] = FALSE;
    $db['pg_prod']['cachedir'] = '';
    $db['pg_prod']['char_set'] = 'utf8';
    $db['pg_prod']['dbcollat'] = 'utf8';
    $db['pg_prod']['swap_pre'] = '';
    $db['pg_prod']['autoinit'] = TRUE;
    $db['pg_prod']['stricton'] = FALSE;
?>