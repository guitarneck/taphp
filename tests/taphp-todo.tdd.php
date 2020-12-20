<?php
require __DIR__.'/../taphp.lib.php';

define('DEBUG',include 'taphp-debug.php');

test('testing todo', function ($t) {
   
   $output = Helper::TrapOutput('todo');
   if (DEBUG) echo $output,TAP_EOL;

   $t->ok( strpos($output,'TAP version 13') !== false, 'TAPHP has runned' );

   $t->ok( strpos($output,'not ok 2 should never happens 1 # TODO') !== false );
   $t->ok( strpos($output,'not ok 3 should never happens 2 # TODO') !== false );
   $t->ok( strpos($output,'not ok 4 this should never happens 3 # TODO') !== false );
   $t->ok( strpos($output,TAP_EOL.'ok 5 this runs # TODO') !== false );
   $t->ok( strpos($output,'not ok 7 failing assert but todo # TODO') !== false );
   $t->ok( strpos($output,'not ok 9 # TODO') !== false );
   $t->ok( strpos($output,TAP_EOL.'ok 10 this pass in todo inside # TODO') !== false );
   $t->ok( strpos($output,'not ok 11 this fail in todo inside # TODO') !== false );
   $t->ok( strpos($output,TAP_EOL.'ok 12 this test runs after todo') !== false );

   $t->ok( strpos($output,'# pass  12') !== false );
   $t->ok( strpos($output,'# todo  8') !== false );

   $t->end();
});