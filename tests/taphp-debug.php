<?php

$paths = array(
   get_include_path(),
   __DIR__.'/..'
);
set_include_path( implode(PATH_SEPARATOR,$paths) );

class Helper
{
   static
   function TrapOutput ( $filename )
   {
      $curdir = __DIR__;
      ob_start();
      system("php -f $curdir/subtests/taphp-$filename.subtest.php 2>&1"); // out & err
      return ob_get_clean();   
   }

   static
   function clean ( $text )
   {
      return str_replace(TAP_EOL,'',$text);
   } 
}

$debug = false;
if (PHP_SAPI === "cli")
{
   foreach ( $argv as $arg )
   {
      if ( $arg === '-d' || $arg === '---debug')
      {
         $debug = true;
         break;
      }
   }
}
else
{
   if ( isset($_REQUEST['debug']) ) $debug = true;
}
return $debug;
?>