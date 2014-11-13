<?php
//========================================================================
//       Embeder - Make an executable Windows-binary file from a PHP script
//
//       License : PHP License (http://www.php.net/license/3_0.txt)
//       Author : Eric Colinet <e dot colinet at laposte dot net>
//       http://wildphp.free.fr/wiki/doku?id=win32std:embeder
//==========================================================


/**
 * embeder2.1 - add file function has third parameter defining resource path
 *
 * (c) 2006 by frantik
 * (c) 2014 by RainbowDashDC 
 *
 * http://wiki.swiftlytilting.com/phc-win
 * http://github.com/RDashINC/embeder2
 *
 */


/* Exit function */
function err($txt) {
	echo ucfirst(basename($_SERVER['argv'][0]))." ".(defined('EMBEDED')?'(embeded) ':'').'- Powered by PHP version '.phpversion()."\n";
	die($txt."\n");
}

/* File transformation function */
function _f($file, $force=false) { return $force||defined('EMBEDED')?'res:///PHP/'.md5($file):$file; }

/* Check if win32std is loaded */
if(!extension_loaded('win32std')) {
	err("I need win32std !");
}

/* Conf */
define('EMBEDER_BASE_EXE_PATH', 'out/');

/* Action list */
$actions = array(
	'new'      => array('new_file', array('name')),
	'main'     => array('add_main', array('name', 'file')),
	'add'      => array('add_file', array('name', 'file','link')),
	'manifest' => array('add_manifest', array('name', 'file')),
	'list'     => array('display_list', array('name')),
	'view'     => array('display_resource', array('name', 'section', 'value', 'lang')),
);

/* Action functions */
function new_file($name, $type= 'console') {
	$base_exe = EMBEDER_BASE_EXE_PATH.$type.'.exe';
	$exe = ".\\{$name}.exe";
	check_exe($exe, true);
	if(!copy(_f(EMBEDER_BASE_EXE_PATH.$type.'.exe'), $exe)) err("Can't create '$exe'");
	echo "'$exe' created\n";
}

function add_main($name, $file) { 
	$exe= ".\\{$name}.exe";
	check_exe($exe);
	update_resource($exe, 'PHP', 'RUN', file_get_contents($file), 1036);
}

function add_file($name, $file, $link) {
	$exe= ".\\{$name}.exe";
	check_exe($exe);
	update_resource($exe, 'PHP', md5($link), file_get_contents($file));
}

/* Requires custom embeder version, 2.0.1 */
function add_manifest($name, $file) {
	$exe= ".\\{$name}.exe";
	check_exe($exe);
	update_resource($exe, 24, 1, file_get_contents($file), 1033);
}

function update_resource($file, $section, $name, $data, $lang=null) {
	$res= "res://$file/$section/$name";
	if(!res_set($file, $section, $name, $data, $lang)) err("Can't update '$res'\n");
	echo "Updated '$res";
	if(isset($lang)) {
		echo "/$lang";
	}
	echo "' with ".strlen($data)." bytes\n";
}

function check_exe($exe, $exists=false) {
	if($exists) {
		if(file_exists($exe)) {
			err("'$exe' already exists.");
		}
	} else {
		if(!file_exists($exe)) {
			err("'$exe' doesn't exist.");
		}
	}
}

function display_list($name) {
	$exe = ".\\{$name}.exe";

	check_exe($exe);

	$h = res_open($exe);
	if(!$h) err( "can't open '$exe'" );

	echo "Res list of '$exe': \n";
	$list= res_list_type($h, true);
	if( $list===FALSE ) err( "Can't list type" );
	
	for( $i= 0; $i<count($list); $i++ ) {
		echo $list[$i]."\n";
		$res= res_list($h, $list[$i]);
		for( $j= 0; $j<count($res); $j++ ) {
			echo "\t".$res[$j]."\n";
		}
	}
	res_close($h);
}

function display_resource($name, $section, $value, $lang=null) {
	$exe= "{$name}.exe";
	$res= "res://{$exe}/{$section}/{$value}/${lang}";
	check_exe($exe);

	echo "- Displaying '$res'\n";
	echo file_get_contents("res://{$exe}/{$section}/{$value}");
	echo "\n-End\n";
}

/* Run specified action */
if( !isset($argv[1]) ) err( "Please specify something to do.\nUsage: {$argv[0]} action [params...]\nWhere action can be: ".implode(', ', array_keys($actions))."\n");
foreach( $actions as $k => $v ) {
	if( $k==$argv[1] ) {
		$params= $argv;
		array_shift($params);
		array_shift($params);
		if( count($params) != count($v[1]) ) err("Bad number of parameters, '$k' needs: ".implode(", ", $v[1]));
		call_user_func_array($v[0], $params);
		exit(0);
	}
}
err("Unknown action '{$argv[1]}'");
?>