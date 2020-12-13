<?php
require __DIR__.'/../taphp.lib.php';

define('DEBUG',include 'taphp-debug.php');

test('testing bailout', function ($t) {
   
   $output = Helper::TrapOutput('bailout');
   if (DEBUG) echo $output,TAP_EOL;

   $t->ok( strpos($output,'TAP version 13') !== false, 'TAPHP has runned' );

   $label = 'Bail out! critical';
   $found = strpos($output,$label) !== false;
   $t->ok( $found, '`'.$label.'` should be found' );

   $label = 'you must not see this';
   $found = strpos($output,$label) !== false;
   $t->ok( !$found, '`'.$label.'` should not be found' );

   $t->end();
});