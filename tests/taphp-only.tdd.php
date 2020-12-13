<?php
require __DIR__.'/../taphp.lib.php';

define('DEBUG',include 'taphp-debug.php');

test('testing only', function ($t) {
   
   $output = Helper::TrapOutput('only');
   if (DEBUG) echo $output,TAP_EOL;

   $t->ok( strpos($output,'TAP version 13') !== false, 'TAPHP has runned' );

   $t->ok( strpos($output,TAP_EOL.'ok 1 you must see only this') !== false );
   $t->notok( strpos($output,'you must not see this') !== false );

   $t->end();
});