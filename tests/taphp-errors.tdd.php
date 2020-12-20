<?php
require __DIR__.'/../taphp.lib.php';

define('DEBUG',include 'taphp-debug.php');

test('testing errors', function ($t) {

   $output = Helper::TrapOutput('errors');
   if (DEBUG) echo $output,TAP_EOL;

   $t->ok( strpos($output,'TAP version 13') !== false, 'TAPHP has runned' );

   $at = 0;
   
   foreach ( ['error','ifError','ifErr','iferror','if_error'] as $assert )
   {
      $t->comment("-- testing $assert");

      $label = "not ok ".++$at." should reports `$assert`";
      $found = strpos($output,$label) !== false;
      $t->ok( $found, '`'.$label.'` should be found' );
   
      $label = "not ok ".++$at." should reports `$assert` with a better message";
      $found = strpos($output,$label) !== false;
      $t->ok( $found, '`'.$label.'` should be found' );
   
      $label = "ok ".++$at." `$assert` raised an exception";
      $found = strpos($output,TAP_EOL.$label) !== false;
      $t->ok( $found, "`$label` should be found" );
   }

   foreach ( ['exception','ifException','ifExcept','ifExpt','ifExp','ifexception','if_exception'] as $assert )
   {
      $t->comment("-- testing $assert");
      
      $label = "not ok ".++$at." should reports `$assert`";
      $found = strpos($output,$label) !== false;
      $t->ok( $found, '`'.$label.'` should be found' );
   
      $label = "not ok ".++$at." should reports `$assert` with a better message";
      $found = strpos($output,$label) !== false;
      $t->ok( $found, '`'.$label.'` should be found' );
   
      $label = "ok ".++$at." `$assert` raised an exception";
      $found = strpos($output,TAP_EOL.$label) !== false;
      $t->ok( $found, "`$label` should be found" );
   }

   $t->end();
});