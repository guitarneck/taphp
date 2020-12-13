<?php
require __DIR__.'/../taphp.lib.php';

define('DEBUG',include 'taphp-debug.php');

test('testing only twice', function ($t) {
   
   $output = Helper::TrapOutput('only-twice');
   if (DEBUG) echo $output,TAP_EOL;

   $t->notok( strpos($output,'TAP version 13') !== false, 'TAPHP has failed' );

   $t->notok( strpos($output,'you must see only this') !== false );
   $t->notok( strpos($output,'you must not see this') !== false );

   $t->ok( strpos($output,'there can only be one `only test`') !== false );

   $t->end();
});