<?php

require __DIR__.'/../../taphp.lib.php';

/*
   I'm testing here only the correct mapping to methods.

   functionnals testing are in :
      - taphp-strict-equals.tdd.php
      - taphp-loose-equals.tdd.php
*/

$methods = [
// ok when equal
   'deepEqual'              => [[true,true],[true,true]],
   'deepEquals'             => [[true,true],[true,true]],
   'isEquivalent'           => [[true,true],[true,true]],
   'same'                   => [[true,true],[true,true]],
   'deep_equal'             => [[true,true],[true,true]],

// ok when loose equal
   'deepLooseEqual'         => [[true,1],[1,true]],
   'deep_loose_equal'       => [[true,1],[1,true]],

// ok when not equal
   'notDeepEqual'           => [[true,true],true],
   'notDeepEquals'          => [[true,true],true],
   'notEquivalent'          => [[true,true],true],
   'notDeeply'              => [[true,true],true],
   'notSame'                => [[true,true],true],
   'not_deep_equal'         => [[true,true],true],
   'not_same'               => [[true,true],true],
   'isNotDeepEqual'         => [[true,true],true],
   'isNotDeeply'            => [[true,true],true],
   'isNotEquivalent'        => [[true,true],true],
   'isInequivalent'         => [[true,true],true],

// ok when not loose equal
   'notDeepLooseEqual'      => [[true,1],[0,true]],
   'not_deep_loose_equal'   => [[true,1],[0,true]],
];

foreach ( $methods as $assert => $value )
{
   test("testing `$assert`",function($t) use ($assert,$value) {
      $t->$assert( $value[0], $value[1] );
      $t->end();
   });
}