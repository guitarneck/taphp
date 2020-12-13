<?php
require __DIR__.'/../taphp.lib.php';

define('DEBUG',include 'taphp-debug.php');

test('testing deep asserts', function ($t) {

   $output = Helper::TrapOutput('deeps');
   if (DEBUG) echo $output,TAP_EOL;

   $t->ok( strpos($output,'TAP version 13') !== false, 'TAPHP has runned' );

   // Fast but lame test...
   $t->ok( strpos($output,'# pass  20') !== false, 'deep tests should succeed' );

   $t->end();
});