<?php
require __DIR__.'/../taphp.lib.php';

define('DEBUG',include 'taphp-debug.php');

test('testing skip', function ($t) {
   
   $output = Helper::TrapOutput('skip');
   if (DEBUG) echo $output,TAP_EOL;

   $t->ok( strpos($output,'TAP version 13') !== false, 'TAPHP has runned' );

   $t->ok( strpos($output,'skip with option # SKIP') !== false );
   $t->notok( strpos($output,'this should not even run 00') !== false );

   $t->ok( strpos($output,'skip function # SKIP') !== false );
   $t->notok( strpos($output,'this should not even run 01') !== false );
  
   $t->ok( strpos($output,'skip subtest') !== false );
   $t->ok( strpos($output,'the subtest to skip # SKIP') !== false );
   $t->notok( strpos($output,'this should not even run 02') !== false );
   $t->ok( strpos($output,TAP_EOL.'ok 1 this runs') !== false );
   
   $t->ok( strpos($output,'skip in a test') !== false );
   $t->ok( strpos($output,TAP_EOL.'ok 2 this runs') !== false );
   $t->ok( strpos($output,TAP_EOL.'not ok 3 failing assert is skipped # SKIP') !== false );
   $t->ok( strpos($output,'this runs too 03') !== false );

   $t->ok( strpos($output,'skip with explanation') !== false );
   $t->ok( strpos($output,'run only if `whatever`') !== false );
   
   $t->ok( strpos($output,'tests might skip in the middle') !== false );
   $t->ok( strpos($output,TAP_EOL.'ok 6 this runs 05') !== false );
   $t->ok( strpos($output,'stop here, no test anymore # SKIP') !== false );
   $t->notok( strpos($output,'failing assert must be skipped 05') !== false );
     
   $t->ok( strpos($output,'canceling skip in the middle') !== false );
   $t->ok( strpos($output,TAP_EOL.'ok 8 this runs 06') !== false );
   $t->ok( strpos($output,TAP_EOL.'ok 9 skip canceled') !== false );
   $t->ok( strpos($output,TAP_EOL.'ok 10 this should runs too 06') !== false );

   $t->end();
});