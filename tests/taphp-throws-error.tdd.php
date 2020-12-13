<?php
require __DIR__.'/../taphp.lib.php';

define('DEBUG',include 'taphp-debug.php');

test('testing throws-error', function ($t) {

   $output = Helper::TrapOutput('throws-error');
   if (DEBUG) echo $output,TAP_EOL;

   $t->ok( strpos($output,'TAP version 13') !== false, 'TAPHP has runned' );   
   $t->ok( strpos($output,'Fatal error') !== false, 'PHP has failed' );

   $t->end();
});