<?php
require __DIR__.'/../taphp.lib.php';

define('DEBUG',include 'taphp-debug.php');

test('testing throws', function ($t) {

   $output = Helper::TrapOutput('throws');
   if (DEBUG) echo $output,TAP_EOL;

   $t->ok( strpos($output,'TAP version 13') !== false, 'TAPHP has runned' );

   // Fast but lame test...
   $at = 0;
   
   $t->ok( strpos($output,'not ok '.++$at) !== false );

   $t->ok( strpos($output,'not ok '.++$at) !== false );

   $t->ok( strpos($output,TAP_EOL.'ok '.++$at) !== false );

   $t->ok( strpos($output,TAP_EOL.'ok '.++$at) !== false );

   $t->ok( strpos($output,TAP_EOL.'ok '.++$at) !== false );

   $t->ok( strpos($output,'not ok '.++$at) !== false );

   $t->ok( strpos($output,TAP_EOL.'ok '.++$at) !== false );

   $t->ok( strpos($output,'not ok '.++$at) !== false );

   $t->end();
});