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

/* Banner */
function banner() {
	echo strtolower(basename($_SERVER['argv'][0]))." ".(defined('EMBEDED')?'(embeded) ':'').'- Powered by PHP version '.phpversion()."\n";
}

/* Exit function */
function err($txt) {
	banner();
	die($txt."\n");
}

/* File transformation function */
function _f($file, $force=false) { return $force||defined('EMBEDED')?'res:///PHP/'.md5($file):$file; }

/* Check if win32std is loaded */
if(!extension_loaded('win32std')) {
	err("win32std not found.");
}

/* Conf */
define('EMBEDER_BASE_EXE_PATH', 'out/');

/* Action list */
$actions = array(
	'new'  => array('new_file',         array('name'),                             "Create Base EXE"),
	'main' => array('add_main',         array('name', 'file'),                     "Add main PHP file to exe"),
	'add'  => array('add_file',         array('name', 'file','link'),              "Add file to exe"),
	'type' => array('change_type',      array('name', 'type'),                     "Change EXE type."),
	'list' => array('display_list',     array('name'),                             "List contents of EXE"),
	'view' => array('display_resource', array('name', 'section', 'value', 'lang'), "View EXE file content"),
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

function change_type($name, $type) {
    $exe = ".\\{$name}.exe";
    $types = array('CONSOLE', 'WINDOWS');
    
    /* Check if EXE exists */
    check_exe($exe);
    
    /* Check TYPE paramater */
    if(!in_array($new_format= strtoupper($type), $types)) {
        err("Type not supported");
    }
    
    /* Open file handle in r+b mode */
    $f = fopen($exe, 'r+b');
    
    /* Change EXE type */
    $type_record = unpack('Smagic/x58/Loffset', fread($f, 32*4));
    if($type_record['magic'] != 0x5a4d ) {
        err("Not an MSDOS executable file");
    }
    if(fseek($f, $type_record['offset'], SEEK_SET) != 0) {
        err("Seeking error (+{$type_record['offset']})");
    }
    
    // PE Record
    $pe_record = unpack('Lmagic/x16/Ssize', fread($f, 24));
    if($pe_record['magic'] != 0x4550 ) {
        err("PE header not found");
    }
    if($pe_record['size'] != 224 ) {
        err("Optional header not in NT32 format");
    }
    if(fseek($f, $type_record['offset']+24+68, SEEK_SET) != 0) {
        err("Seeking error (+{$type_record['offset']})");
    }
    if(fwrite($f, pack('S', $new_format=='CONSOLE'?3:2))===false) {
        err("Write error");
    }
    
    /* Close file handle */
    fclose($f);

    echo "File type changed too '".$new_format."'";
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
if( !isset($argv[1]) ) {
	banner();

	echo "Usage: {$argv[0]} action [params...]\n";
	echo "Available commands:\n";
	/* Print out arrays, then get last object for "description" */
	foreach($actions as $k => $v) {
        $i=0;
        $cl = strlen($k);
        $cld = 11-$cl;
        $pdesc = $v[2];
        $pcount = count($v[1]);
        
        /* Print out num args, command, desc */
        echo " {$k}";
        while($i!==$cld) {
            $i=$i+1;
            echo " ";
        }
        echo "{$pdesc}\n";
	}
    
    die();
}

foreach($actions as $k => $v ) {
	if($k==$argv[1]) {
		$params = $argv;

		/* Shift ARRAY */
		array_shift($params);
		array_shift($params);

		/* Count Param number */
		if(count($params) != count($v[1])) {
			err("Bad number of parameters, '$k' needs: ".implode(", ", $v[1]));
		}

		/* Call Function */
		call_user_func_array($v[0], $params);

		/* Exit with zero code */
		exit(0);
	}
}
err("Unknown command '{$argv[1]}'");
?>