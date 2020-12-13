<?php
require __DIR__.'/../taphp.lib.php';

define('DEBUG',include 'taphp-debug.php');

echo 'this should takes a couple of seconds...',TAP_EOL;

test('testing timeout', function ($t) {
   
   $output = Helper::TrapOutput('timeout');
   if (DEBUG) echo $output,TAP_EOL;

   $t->ok( strpos($output,'TAP version 13') !== false, 'TAPHP has runned' );

   $label = TAP_EOL.'ok 1 testing timeout success';
   $found = strpos($output,$label) !== false;
   $t->ok( $found, '`'.Helper::clean($label).'` should be found' );

   $label = 'not ok 2 testing timeout failure';
   $found = strpos($output,$label) !== false;
   $t->ok( $found, '`'.$label.'` should be found' );

   $label = TAP_EOL.'ok 3 this timeout subtest succeed';
   $found = strpos($output,$label) !== false;
   $t->ok( $found, '`'.Helper::clean($label).'` should be found' );

   $label = 'not ok 4 this timeout subtest failed';
   $found = strpos($output,$label) !== false;
   $t->ok( $found, '`'.$label.'` should be found' );

   $t->end();
});