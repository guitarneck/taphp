<?php
require __DIR__.'/../taphp.lib.php';

define('DEBUG',include 'taphp-debug.php');

define('NO_END','test exited without ending: ');
define('BAD_PLAN','plan != count: ');

test('testing test exit', function ($t) {
   
   $output = Helper::TrapOutput('test-exit');
   if (DEBUG) echo $output,TAP_EOL;

   $t->ok( strpos($output,'TAP version 13') !== false, 'TAPHP has runned' );

   $t->comment('=== test suite');

   $label = NO_END.'test suite with no plan nor end';
   $found = strpos($output,$label) !== false;
   $t->ok( $found, '`'.$label.'` should be found' );
   $label = BAD_PLAN.'test suite with no plan nor end';
   $found = strpos($output,BAD_PLAN.$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );

   $label = NO_END.'test suite with plan';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
   $label = BAD_PLAN.'test suite with plan';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );

   $label = NO_END.'test suite with end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
   $label = BAD_PLAN.'test suite with end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );

   $label = NO_END.'test suite with plan and end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
   $label = BAD_PLAN.'test suite with plan and end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );

   $t->comment('=== test & subtest');

   $label = NO_END.'test with end & subtest with no plan nor end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
   $label = BAD_PLAN.'test with end & subtest with no plan nor end';
   $found = strpos($output,BAD_PLAN.$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );

   $label = NO_END.'the subtest with no plan nor end';
   $found = strpos($output,$label) !== false;
   $t->ok( $found, '`'.$label.'` should be found' );
   $label = BAD_PLAN.'the subtest with no plan nor end';
   $found = strpos($output,BAD_PLAN.$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
//--
   $label = NO_END.'test with end & subtest with plan';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
   $label = BAD_PLAN.'test with end & subtest with plan';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );

   $label = NO_END.'the subtest with plan';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
   $label = BAD_PLAN.'the subtest with plan';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
//--
   $label = NO_END.'test with end & subtest with end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
   $label = BAD_PLAN.'test with end & subtest with end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );

   $label = NO_END.'the subtest with end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
   $label = BAD_PLAN.'the subtest with end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
//--
   $label = NO_END.'test with end & subtest with plan and end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
   $label = BAD_PLAN.'test with end & subtest with plan and end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );

   $label = NO_END.'the subtest with plan and end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
   $label = BAD_PLAN.'the subtest with plan and end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );

   $t->comment('=== test & planed subtest');

   $label = NO_END.'test with plan & subtest with no plan nor end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
   $label = BAD_PLAN.'test with plan & subtest with no plan nor end';
   $found = strpos($output,BAD_PLAN.$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );

   $label = NO_END.'the planed subtest with no plan nor end';
   $found = strpos($output,$label) !== false;
   $t->ok( $found, '`'.$label.'` should be found' );
   $label = BAD_PLAN.'the planed subtest with no plan nor end';
   $found = strpos($output,BAD_PLAN.$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
//--
   $label = NO_END.'test with plan & subtest with plan';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
   $label = BAD_PLAN.'test with plan & subtest with plan';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );

   $label = NO_END.'the planed subtest with plan';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
   $label = BAD_PLAN.'the planed subtest with plan';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
//--
   $label = NO_END.'test with plan & subtest with end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
   $label = BAD_PLAN.'test with plan & subtest with end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );

   $label = NO_END.'the planed subtest with end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
   $label = BAD_PLAN.'the planed subtest with end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
//--
   $label = NO_END.'test with plan & subtest with plan and end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
   $label = BAD_PLAN.'test with plan & subtest with plan and end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );

   $label = NO_END.'the planed subtest with plan and end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );
   $label = BAD_PLAN.'the planed subtest with plan and end';
   $found = strpos($output,$label) !== false;
   $t->notok( $found, '`'.$label.'` should not be found' );

   $t->comment('=== end with error');

   $t->ok( strpos($output,'not ok 16 end with error') !== false );

   $t->end();
});