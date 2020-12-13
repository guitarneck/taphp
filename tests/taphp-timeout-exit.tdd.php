<?php
require __DIR__.'/../taphp.lib.php';

define('DEBUG',include 'taphp-debug.php');

define('NO_END','test exited without ending: ');
define('BAD_PLAN','plan != count: ');

echo 'this should takes a couple of seconds...',TAP_EOL;

test('testing timeout exit', function ($t) {
   
   $output = Helper::TrapOutput('timeout-exit');
   if (DEBUG) echo $output,TAP_EOL;

   $t->ok( strpos($output,'TAP version 13') !== false, 'TAPHP has runned' );

   $t->comment('=== timeout suite');

   $label = 'timeout suite with no plan nor end';
   $found = strpos($output,NO_END.$label) !== false && strpos($output,BAD_PLAN.$label) === false;
   $t->ok( $found, '`'.$label.'` should failed' );

   $label = 'timeout suite with plan';
   $found = strpos($output,NO_END.$label) !== false && strpos($output,BAD_PLAN.$label) !== false;
   $t->ok( !$found, '`'.$label.'` should succeed' );

   $label = 'timeout suite with end';
   $found = strpos($output,NO_END.$label) !== false && strpos($output,BAD_PLAN.$label) !== false;
   $t->ok( !$found, '`'.$label.'` should succeed' );

   $label = 'timeout suite with plan and end';
   $found = strpos($output,NO_END.$label) !== false && strpos($output,BAD_PLAN.$label) !== false;
   $t->ok( !$found, '`'.$label.'` should succeed' );

   $t->comment('=== timeout test');

   $label = 'timeout test with no plan nor end';
   $found = strpos($output,NO_END.$label) !== false && strpos($output,BAD_PLAN.$label) === false;
   $t->ok( $found, '`'.$label.'` should failed' );

   $label = 'timeout test with plan';
   $found = strpos($output,NO_END.$label) !== false && strpos($output,BAD_PLAN.$label) !== false;
   $t->ok( !$found, '`'.$label.'` should succeed' );

   $label = 'timeout test with end'; //end() already called
   $found = strpos($output,NO_END.$label) !== false && strpos($output,BAD_PLAN.$label) !== false;
   $t->ok( !$found, '`'.$label.'` should succeed' );

   $label = 'timeout test with plan and end'; //end() already called
   $found = strpos($output,NO_END.$label) !== false && strpos($output,BAD_PLAN.$label) !== false;
   $t->ok( !$found, '`'.$label.'` should succeed' );

   $t->end();
});