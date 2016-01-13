<?php

	/*  
	 * =========================================
	 *  Embeder - Make an executable Windows-binary file from a PHP script	
	 *	
	 *  License : PHP License (http://www.php.net/license/3_0.txt)	
	 *  Author : Eric Colinet <e dot colinet at laposte dot net>	
	 *  http://wildphp.free.fr/wiki/doku?id=win32std:embeder	
	 *  =========================================
	 *
	 * embeder3 - add file function has third parameter defining resource path
	 *
	 * (c) 2006 by frantik
	 * (c) 2014 by RainbowDashDC 
	 * (c) 2015 by Darksynx
	 *
	 * http://wiki.swiftlytilting.com/phc-win
	 * http://github.com/RDashINC/embeder2
	 * https://github.com/Darksynx/PHC3
	 *
	 */

	
	ECHO ' EMBEDER 3 @ 2006-2016 by frantik, RainbowDashDC, Darksynx ', PHP_EOL, PHP_EOL; 
 
 		@mkdir('myapp');
		

	/* Action list */
	$actions = array(
		'-e' => array('new_php_exe',      array('name', 'file', 'type', 'icon', 'upx', 'evb'), 'Create Base EXE and PHPscript' , 'myfilename index.php console/windows ico.ico/-noicon -upx:yes/no/best -evb:yes/no'),
		'-p' => array('pharx',     	  array('name', 'file'),         		 	'phar my script' , 'name.phar index.php/noindex (index is first execute script)'),
		'-i' => array('add_ico',     	  array('name', 'file'),              		 'Change icon with Resource Hacker' , 'ico.ico myfile.exe'),
		'-n' => array('new_file',         array('name'),                             'Create Base EXE' , 'myfile'),
		'-m' => array('add_main',         array('name', 'file'),                     'Add main PHP file to exe' , 'myfile index.php'),
		'-a' => array('add_file',         array('name', 'file','link'),              'Add file to exe' , 'myfile file link'),
		'-t' => array('change_type',      array('name', 'type'),                     'Change EXE type.' , 'myfile console/windows'),
		'-l' => array('display_list',     array('name'),                             'List contents of EXE' , 'myfile'),
		'-v' => array('display_resource', array('name', 'section', 'value', 'lang'), 'View EXE file content' , 'myfile section value lang'),
		'-h' => array('help', 			  array(), 									 'View list options' , ''),
	);
 
 
	$mbdr = new embeder();
	
	$mbdr->helpvar($actions);
	

	/* Run specified action */
	if( !isset($argv[1]) ) {
		$mbdr->banner();

		echo 'Usage: ', $argv[0] ,' action [params...]', PHP_EOL;
		echo 'Available commands:', PHP_EOL;
		/* Print out arrays, then get last object for "description" */
		foreach($actions as $k => $v) {
			$i=0;
			$cl = strlen($k);
			$cld = 11-$cl;
			$pdesc = $v[2];
			$pcount = count($v[1]);
			
			/* Print out num args, command, desc */
			echo ' '.$k;
			while($i!==$cld) {
				$i=$i+1;
				echo ' ';
			}
			echo $pdesc,PHP_EOL;
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
				$mbdr->err('Bad number of parameters, \'' . $k . '\' needs: ' . implode(', ', $v[1]));
			}

			/* Call Function */
			call_user_func_array(array($mbdr,$v[0]), $params);

			/* Exit with zero code */
			exit(0);
		}
	}
	$mbdr->err('Unknown command \'' . $argv[1] .'\'');
 
 
class embeder {
	
	const EMBEDER_BASE_EXE_PATH = 'out/';
	
	public $_helpvar = array();
		
	public function helpvar($var) { $this->_helpvar = $var; }	
	
	
	public function help() {		
		echo 'COMMAND LINE : ', PHP_EOL,PHP_EOL;		
		foreach($this->_helpvar as $k => $l) {
			echo chr(32),$k , chr(32) , ' [ ', $l[3], ' ] ', PHP_EOL ,"\t - ",$l[2], PHP_EOL,PHP_EOL;	
		}	
		echo PHP_EOL;	
	}	
	
	public function pharx($name, $file) {
			$this->phar_php($file, 'myapp', $name);
	}
	
	public function phar_php($file, $folder, $name='data.phar') {
				
				
				$p = new Phar($name, FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::KEY_AS_FILENAME, $name);
				$p->hasMetadata();
				$p->startBuffering();
				if($file != 'noindex') { $p->setStub('<?php Phar::mapPhar();require("phar://'.$name.'/' . $file .'");__HALT_COMPILER(); ?>'); }
				$p->buildFromDirectory($folder);
				$p->compressFiles(Phar::GZ);
				$p->setSignatureAlgorithm(Phar::SHA512);
				$p->stopBuffering();				
				
	}
	
	
	
	/* Banner */
	public function banner() {
		echo strtolower(basename($_SERVER['argv'][0])) . chr(32) . (defined('EMBEDED')?'(embeded) ':'') . '- Powered by PHP version ' . phpversion() . PHP_EOL;
	}

	/* Exit function */
	public function err($txt) {
		$this->banner();
		die($txt . PHP_EOL);
	}

	/* File transformation function */
	public function _f($file, $force=false) { return $force||defined('EMBEDED') ? 'res:///PHP/' . md5($file) : $file; }

	/* Action functions */
	public function add_ico($name, $file) {
		echo system('.\\ResourceHacker.exe -addoverwrite '. $name . ',' . $name . ',' . $file . ', icon, 101, 1036 ');	
	}
	
	/* Action functions */
	public function evb_exe() {
		echo system('.\\enigmavbconsole.exe .\\tmp\\compil_gen.evb ');	
	}

		/* Action functions */
	public function upx_exe($name, $best='') {
		echo system('.\\upx.exe ' . $best . ' ' . $name , $out);
	}
	
	
	/* Action functions */
	public function new_php_exe($name, $file, $type,  $icon, $upx, $evb) {
		
		@mkdir('tmp');
		@mkdir('tmp\phar');
		@mkdir('tmp\evb');
		@mkdir('tmp\release');
		@mkdir('release');
		
		
		Copy('php5ts.dll','myapp\php5ts.dll');
		
		@unlink('tmp\\'.$name . '.exe');
		
		$this->new_file($name, 'tmp\\');

		echo '>> icon: ' , $icon, PHP_EOL;
		if($icon != '-noicon') { echo '>> icon',PHP_EOL;
		
			$this->add_ico( realpath('.').'\\tmp\\' . $name . '.exe', realpath('.').'\\myapp\\' . $icon);
			
		}
		
		@unlink('ResourceHacker.ini');
		@unlink('ResourceHacker.log');

			copy('myapp\\'.$file, 'tmp\\'.$file);
			
			// compilation avec index 
			
			$this->add_main($name, $file, 'tmp\\' , 'tmp\\');

		
		echo '>> Evb: ' , $evb, PHP_EOL;
		if($evb == '-evb:yes') { 
			
			$this->evb_gen($name,$file,$icon);

			echo '>> upx: ' , $upx, ' loading ...' , PHP_EOL;
			if($upx == '-upx:yes') { 
				
				$this->upx_exe('.\\tmp\\evb\\*.dll');
				
			} elseif($upx == '-upx:best') { 
				
				$this->upx_exe('.\\tmp\\evb\\*.dll', '--best --lzma');
				
			}
			
			$this->evb_exe();
			
			copy('tmp\\release\\'.$name.'.exe', 'tmp\\' . $name.'.exe');
			
		} else {
		
		echo '>> upx:' , $upx, PHP_EOL;
		if($upx == '-upx:yes') { 
			
			$this->upx_exe('.\\tmp\\' . $name . '.exe');
			
			
		} elseif($upx == '-upx:best') { 
				
				$this->upx_exe('.\\tmp\\' . $name . '.exe', '--best --lzma');
				
			}
			
		}
		
		copy('tmp\\'.$name.'.exe', 'release\\' . $name.'.exe');
		echo '>> copy tmp\\'.$name.'.exe', '  release\\' . $name.'.exe',PHP_EOL;
		
		$fp = fopen('.\\release\\debug_'. $name.'.exe'.'.bat', 'w');
			fwrite($fp, $name.'.exe' . PHP_EOL . 'pause' . PHP_EOL);
		fclose($fp);
		
		$this->unliked('.\\tmp\\evb');
		$this->unliked('.\\tmp\\phar');
		$this->unliked('.\\tmp\\release');
		$this->unliked('.\\tmp');
		@rmdir('.\\tmp');
		
		echo PHP_EOL, ' - ' , $name.'.exe is in "\\release" folder', PHP_EOL , PHP_EOL; 
		
	}


	public function unliked($folder) {
		echo PHP_EOL;
		$d = dir($folder);
		while (false !== ($entry = $d->read())) {
			if($entry[0] != '.') {
				if(is_file($d->path . '\\' . $entry)) {
					@unlink($d->path . '\\' . $entry);
					echo '>> delete tmp : ' , $d->path ," \t ", $entry, PHP_EOL;
				} else {
					usleep(150000);
					@rmdir($d->path . '\\' . $entry);
					echo '>> delete tmp : ' , $d->path ," \t ", $entry, PHP_EOL;
				}
			}
		}
		$d->close();	
	}
	
	
	/* Action functions */
	public function new_file($name, $folder='', $type= 'console') {
		$base_exe = self::EMBEDER_BASE_EXE_PATH.$type.'.exe';
		$exe = '.\\' . $folder . $name . '.exe';
		$this->check_exe($exe, true);
		if(!copy($this->_f($base_exe), $exe)) $this->err('Can\'t create \'' . $exe . '\'');
		echo '\'', $exe ,'\'' ,' created',PHP_EOL;
	}

	public function add_main($name, $file , $folder='' , $folderfile='') { 
		$exe = '.\\' . $folder . $name . '.exe';
		$this->check_exe($exe);
		$this->update_resource($exe, 'PHP', 'RUN', file_get_contents($folderfile . $file), 1036);
	}

	public function add_file($name, $file, $link) {
		$exe = '.\\' . $name . '.exe';
		$this->check_exe($exe);
		$this->update_resource($exe, 'PHP', md5($link), file_get_contents($file));
	}

	public function change_type($name, $type , $folder='') {
		$exe = '.\\' . $folder . $name . '.exe';
		$types = array('CONSOLE', 'WINDOWS');
		
		/* Check if EXE exists */
		$this->check_exe($exe);
		
		/* Check TYPE paramater */
		if(!in_array($new_format= strtoupper($type), $types)) {
			$this->err('Type not supported');
		}
		
		/* Open file handle in r+b mode */
		$f = fopen($exe, 'r+b');
		
		/* Change EXE type */
		$type_record = unpack('Smagic/x58/Loffset', fread($f, 32*4));
		if($type_record['magic'] != 0x5a4d ) {
			$this->err('Not an MSDOS executable file');
		}
		if(fseek($f, $type_record['offset'], SEEK_SET) != 0) {
			$this->err('Seeking error ' . $type_record['offset']);
		}
		
		// PE Record
		$pe_record = unpack('Lmagic/x16/Ssize', fread($f, 24));
		if($pe_record['magic'] != 0x4550 ) {
			$this->err('PE header not found');
		}
		if($pe_record['size'] != 224 ) {
			$this->err('Optional header not in NT32 format');
		}
		if(fseek($f, $type_record['offset']+24+68, SEEK_SET) != 0) {
			$this->err('Seeking error ' . $type_record['offset']);
		}
		if(fwrite($f, pack('S', $new_format=='CONSOLE'?3:2))===false) {
			$this->err('Write error');
		}
		
		/* Close file handle */
		fclose($f);

		echo 'File type changed too \'' , $new_format , '\'';
	}

	public function update_resource($file, $section, $name, $data, $lang=null) {
		$res= 'res://'.$file.'/'.$section.'/'.$name;
		if(!res_set($file, $section, $name, $data, $lang)) $this->err('Can\'t update \'' . $res . '\'' . PHP_EOL );
		echo 'Updated ', $res, PHP_EOL;
		if(isset($lang)) {
			echo '/' , $lang;
		}
		echo '\' with ' , strlen($data) , ' bytes' , PHP_EOL;
	}

	public function check_exe($exe, $exists=false) {
		if($exists) {
			if(file_exists($exe)) {
				$this->err('\'' . $exe . '\' already exists.');
			}
		} else {
			if(!file_exists($exe)) {
				$this->err('\'' . $exe . '\' doesn\'t exist.');
			}
		}
	}

	public function display_list($name) {
		$exe = '.\\' . $name . '.exe';

		$this->check_exe($exe);

		$h = res_open($exe);
		if(!$h) $this->err( 'can\'t open \'' . $exe .'\'' );

		echo 'Res list of \'' , $exe , '\': ' , PHP_EOL;
		$list= res_list_type($h, true);
		if( $list===FALSE ) $this->err( 'Can\'t list type' );
		
		for( $i= 0; $i<count($list); $i++ ) {
			echo $list[$i] , PHP_EOL;
			$res= res_list($h, $list[$i]);
			for( $j= 0; $j<count($res); $j++ ) {
				echo "\t" , $res[$j] , PHP_EOL;
			}
		}
		res_close($h);
	}

	public function display_resource($name, $section, $value, $lang=null) {
		$exe= $name . '.exe';
		$res= 'res://'.$exe.'/'.$section.'/'.$value.'/'. $$lang;
		$this->check_exe($exe);

		echo '- Displaying \'' , $res , '\'', PHP_EOL;
		echo file_get_contents('res://' . $exe . '/' . $section . '/' . $value);
		echo PHP_EOL , '-End' , PHP_EOL ;
	}

	
	public function evb_gen($name,$file,$ico,$comp='TRUE') {

	$file_list = '';
	$d = dir('myapp');
	while (false !== ($entry = $d->read())) {
	   //if($entry[0] != '.') {
	$path_parts = pathinfo($entry);
	if($entry[0] != '.' and $entry != $file  and $entry != $ico) {
	
	copy('.\\myapp\\' . $entry, '.\\tmp\\evb\\' . $entry);
	
	$file_list .= <<< ENDX
						<File>
							<Type>2</Type>
							<Name>$entry</Name>
							<File>.\\evb\\$entry</File>
							<ActiveX>false</ActiveX>
							<ActiveXInstall>false</ActiveXInstall>
							<Action>0</Action>
							<OverwriteDateTime>false</OverwriteDateTime>
							<OverwriteAttributes>false</OverwriteAttributes>
							<PassCommandLine>false</PassCommandLine>
						</File>
ENDX;
	   }
	}
	$d->close();


	$g1 = <<< END
	<?xml encoding="utf-8"?>
	<>
		<InputFile>.\\$name.exe</InputFile>
		<OutputFile>.\\release\\$name.exe</OutputFile>
		<Files>
			<Enabled>true</Enabled>
			<DeleteExtractedOnExit>true</DeleteExtractedOnExit>
			<CompressFiles>$comp</CompressFiles>
			<Files>
				<File>
					<Type>3</Type>
					<Name>%DEFAULT FOLDER%</Name>
					<Files>
	$file_list
					</Files>
				</File>
			</Files>
		</Files>
		<Registries>
			<Enabled>false</Enabled>
			<Registries>
				<Registry>
					<Type>1</Type>
					<Virtual>true</Virtual>
					<Name>Classes</Name>
					<ValueType>0</ValueType>
					<Value/>
					<Registries/>
				</Registry>
				<Registry>
					<Type>1</Type>
					<Virtual>true</Virtual>
					<Name>User</Name>
					<ValueType>0</ValueType>
					<Value/>
					<Registries/>
				</Registry>
				<Registry>
					<Type>1</Type>
					<Virtual>true</Virtual>
					<Name>Machine</Name>
					<ValueType>0</ValueType>
					<Value/>
					<Registries/>
				</Registry>
				<Registry>
					<Type>1</Type>
					<Virtual>true</Virtual>
					<Name>Users</Name>
					<ValueType>0</ValueType>
					<Value/>
					<Registries/>
				</Registry>
				<Registry>
					<Type>1</Type>
					<Virtual>true</Virtual>
					<Name>Config</Name>
					<ValueType>0</ValueType>
					<Value/>
					<Registries/>
				</Registry>
			</Registries>
		</Registries>
		<Packaging>
			<Enabled>false</Enabled>
		</Packaging>
		<Options>
			<ShareVirtualSystem>true</ShareVirtualSystem>
			<MapExecutableWithTemporaryFile>true</MapExecutableWithTemporaryFile>
			<AllowRunningOfVirtualExeFiles>false</AllowRunningOfVirtualExeFiles>
		</Options>
	</>
END;
		
		$fp = fopen('.\\tmp\\compil_gen.evb', 'w');
		fwrite($fp, $g1);
		fclose($fp);
		
	}	
	
	
}
?>
