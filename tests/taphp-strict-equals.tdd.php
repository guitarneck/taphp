<?php
require __DIR__.'/../taphp.lib.php';

define('DEBUG',include 'taphp-debug.php');

test('testing strict asserts', function ($t) {

   $output = Helper::TrapOutput('strict-equals');
   if (DEBUG) echo $output,TAP_EOL;

   $t->ok( strpos($output,'TAP version 13') !== false, 'TAPHP has runned' );

   $at = 0;

   foreach ( ['strictEqual','equal','equals','isEqual','strictEquals','is','strict_equal','is_equal'] as $assert )
   {
      $t->comment("-- testing $assert");

      $label = "ok ".++$at." testing `$assert` should succeed";
      $found = strpos($output,TAP_EOL.$label) !== false;
      $t->ok( $found, "`$label` should be found" );
   
      $label = "not ok ".++$at." testing not `$assert` should failed";
      $found = strpos($output,$label) !== false;
      $t->ok( $found, "`$label` should be found" );

      $label = "ok ".++$at." testing deep `$assert` should succeed";
      $found = strpos($output,TAP_EOL.$label) !== false;
      $t->ok( $found, "`$label` should be found" );
   
      $label = "not ok ".++$at." testing deep not `$assert` should failed";
      $found = strpos($output,$label) !== false;
      $t->ok( $found, "`$label` should be found" );
   }

   foreach ( ['notStrictEqual','notEqual','notEquals','isNotEqual','doesNotEqual','isInequal','notStrictEquals','isNot','not','not_strict_equal','not_equal','is_not_equal','is_not'] as $assert )
   {
      $t->comment("-- testing $assert");

      $label = "ok ".++$at." testing `$assert` should succeed";
      $found = strpos($output,TAP_EOL.$label) !== false;
      $t->ok( $found, "`$label` should be found" );

      $label = "not ok ".++$at." testing not `$assert` should failed";
      $found = strpos($output,$label) !== false;
      $t->ok( $found, "`$label` should be found" );   

      $label = "ok ".++$at." testing deep `$assert` should succeed";
      $found = strpos($output,TAP_EOL.$label) !== false;
      $t->ok( $found, "`$label` should be found" );

      $label = "not ok ".++$at." testing deep not `$assert` should failed";
      $found = strpos($output,$label) !== false;
      $t->ok( $found, "`$label` should be found" );   
   }
   
   $t->end();
});