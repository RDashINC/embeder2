<?
/**
  * Exemple embeder project
  */

/* File transformation function */
function _f($file, $force=false) { return $force||defined('EMBEDED')?'res:///PHP/'.md5($file):$file; }

echo "Embeder test project\n";

/* Try including */
include _f('include.inc');

/* Try file_exists */
if( !file_exists(_f('dat/data.dat')) )
	echo "the file 'dat/data.dat' doesn't exists (it's a win32std current limitation)\n";
else
	echo "the file 'dat/data.dat' exists\n";

/* Try content */
echo 'Content: '.file_get_contents(_f('dat/data.dat'));

echo "Test end\n";

?>