<?php
require __DIR__.'/../taphp.lib.php';

define('DEBUG',include 'taphp-debug.php');

test('testing asserts', function ($t) {

   $output = Helper::TrapOutput('asserts');
   if (DEBUG) echo $output,TAP_EOL;

   $t->ok( strpos($output,'TAP version 13') !== false, 'TAPHP has runned' );

   $at = 0;

   foreach ( ['assert','ok','true'] as $assert )
   {
      $t->comment("-- testing $assert");

      $label = TAP_EOL."ok ".++$at." testing `$assert` should succeed";
      $found = strpos($output,$label) !== false;
      $t->ok( $found, '`'.Helper::clean($label).'` should be found' );
   
      $label = "not ok ".++$at." testing `$assert` should failed";
      $found = strpos($output,$label) !== false;
      $t->ok( $found, '`'.$label.'` should be found' );
   }

   foreach ( ['notOK','false','notok','not_ok'] as $assert )
   {
      $t->comment("-- testing $assert");
      
      $label = TAP_EOL."ok ".++$at." testing `$assert` should succeed";
      $found = strpos($output,$label) !== false;
      $t->ok( $found, '`'.Helper::clean($label).'` should be found' );
   
      $label = "not ok ".++$at." testing `$assert` should failed";
      $found = strpos($output,$label) !== false;
      $t->ok( $found, '`'.$label.'` should be found' );
   }
   
   $t->comment("-- testing pass");
   $t->ok( strpos($output,TAP_EOL.'ok '.++$at) !== false );
   $t->comment("-- testing fail");
   $t->ok( strpos($output,'not ok '.++$at) !== false );

   $t->end();
});