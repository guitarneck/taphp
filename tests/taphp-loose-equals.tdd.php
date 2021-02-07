<?php
require __DIR__.'/../taphp.lib.php';

define('DEBUG',include 'taphp-debug.php');

test('testing loose asserts', function ($t) {

   $output = Helper::TrapOutput('loose-equals');
   if (DEBUG) echo $output,TAP_EOL;

   $t->ok( strpos($output,'TAP version 13') !== false, 'TAPHP has runned' );

   $at = 0;

   foreach ( ['looseEqual','looseEquals','loose_equal'] as $assert )
   {
      $t->comment("-- testing $assert");

      $label = "ok ".++$at." testing `$assert` should succeed";
      $found = strpos($output,TAP_EOL.$label) !== false;
      $t->ok( $found, "`$label` should be found" );

      $label = "ok ".++$at." testing `$assert` should succeed";
      $found = strpos($output,TAP_EOL.$label) !== false;
      $t->ok( $found, "`$label` should be found" );
      
      $label = "ok ".++$at." testing `$assert` should succeed";
      $found = strpos($output,TAP_EOL.$label) !== false;
      $t->ok( $found, "`$label` should be found" );

      $label = "not ok ".++$at." testing not `$assert` should failed";
      $found = strpos($output,TAP_EOL.$label) !== false;
      $t->ok( $found, "`$label` should be found" );

      $label = "ok ".++$at." testing `$assert` should succeed";
      $found = strpos($output,TAP_EOL.$label) !== false;
      $t->ok( $found, "`$label` should be found" );
      
      $label = "ok ".++$at." testing `$assert` should succeed";
      $found = strpos($output,TAP_EOL.$label) !== false;
      $t->ok( $found, "`$label` should be found" );
   }

   foreach ( ['notLooseEqual','notLooseEquals','not_loose_equal'] as $assert )
   {
      $t->comment("-- testing $assert");

      $label = "not ok ".++$at." testing not `$assert` should failed";
      $found = strpos($output,TAP_EOL.$label) !== false;
      $t->ok( $found, "`$label` should be found" );   

      $label = "not ok ".++$at." testing not `$assert` should failed";
      $found = strpos($output,TAP_EOL.$label) !== false;
      $t->ok( $found, "`$label` should be found" );   

      $label = "not ok ".++$at." testing not `$assert` should failed";
      $found = strpos($output,TAP_EOL.$label) !== false;
      $t->ok( $found, "`$label` should be found" );

      $label = "ok ".++$at." testing `$assert` should succeed";
      $found = strpos($output,TAP_EOL.$label) !== false;
      $t->ok( $found, "`$label` should be found" );

      $label = "not ok ".++$at." testing not `$assert` should failed";
      $found = strpos($output,TAP_EOL.$label) !== false;
      $t->ok( $found, "`$label` should be found" );   

      $label = "not ok ".++$at." testing not `$assert` should failed";
      $found = strpos($output,TAP_EOL.$label) !== false;
      $t->ok( $found, "`$label` should be found" );
   }
   
   $t->end();
});