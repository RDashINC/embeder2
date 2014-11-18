========================================================================
       Embeder - Make an executable Windows-binary file from a PHP script

       License : PHP License (http://www.php.net/license/3_0.txt)
       Author : Eric Colinet <e dot colinet at laposte dot net>
	   Author : Jared Allard <rainbowdashdc at mezgrman dot de>
       http://wildphp.free.fr/wiki/doku?id=win32std:embeder
========================================================================

How-To Build

Open embeder.vcxproj OR (manual)

Add too include path

    C:\path\to\php\source\main
    C:\path\to\php\source\Zend
    C:\path\to\php\source\TSRM
    C:\path\to\php\source
    C:\path\to\php\source\sapi\embed
    C:\path\to\php\source\ext\standard

Build <built-exe>.exe

Copy <built-exe>.exe as ../out/console.exe OR run post.cmd

How Does this work?

Compiles a php_embed interacting exe, which looks too res://PHP/RUN for a PHP file.
On build time, run.php is included. However, this can be used to supply another PHP or one can
be included via visual studio.