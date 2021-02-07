<?php
require __DIR__.'/../taphp.lib.php';

function TDDTrapOutput ( $filename )
{
   $curdir = __DIR__;
   $nul = PHP_OS !== 'WINNT' ? '/dev/null' : 'nul';
   ob_start();
   system("php -f $curdir/taphp-$filename.tdd.php 2>$nul");
   return ob_get_clean();   
}

echo 'this should takes a couple of seconds...',TAP_EOL;

test('testing ...', function ($t) {
   
   $all = [
      'errors'          => 37,
      'timeout-exit'    => 9,
      'loose-equals'    => 37,
      'timeout'         => 5,
      'asserts'         => 17,
      'strict-equals'   => 85,
      'bailout'         => 3,
      'test-exit'       => 42,
      'deeps'           => 2,
      'throws'          => 9,
      'skip'            => 23,
      'todo'            => 12,
      'only'            => 3,
      'only-twice'      => 4,
      'throws-error'    => 2
   ];

   foreach ( $all as $tdd => $pass )
   {
      $output = TDDTrapOutput($tdd);
   
      $t->comment("--- testing tdd `$tdd`");
      $t->ok( strpos($output,'TAP version 13') !== false, 'TAPHP has runned' );
      $t->ok( strpos($output,"# pass  $pass") !== false, "`$tdd` should succeed" );
   }

   $t->end();
});