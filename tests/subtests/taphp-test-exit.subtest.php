<?php

require __DIR__.'/../../taphp.lib.php';

test('test suite with no plan nor end',function($t){
   $t->ok(true);
});

test('test suite with plan',function($t){
   $t->plan(1);
   $t->ok(true);
});

test('test suite with end',function($t){
   $t->ok(true);
   $t->end();
});

test('test suite with plan and end',function($t){
   $t->plan(1);
   $t->ok(true);
   $t->end();
});

// subtests, tests with end

test('test with end & subtest with no plan nor end',function($t){
   $t->test('the subtest with no plan nor end',function($t){
      $t->ok(true);
   });
   $t->end();
});

test('test with end & subtest with plan',function($t){
   $t->test('the subtest with plan',function($t){
      $t->plan(1);
      $t->ok(true);
   });
   $t->end();
});

test('test with end & subtest with end',function($t){
   $t->test('the subtest with end',function($t){
      $t->ok(true);
      $t->end();
   });
   $t->end();
});

test('test with end & subtest with plan and end',function($t){
   $t->test('the subtest with plan and end',function($t){
      $t->plan(1);
      $t->ok(true);
      $t->end();
   });
   $t->end();
});

// subtests, tests with plan

test('test with plan & subtest with no plan nor end',function($t){
   $t->plan(1);
   $t->test('the planed subtest with no plan nor end',function($t){
      $t->ok(true);
   });
});

test('test with plan & subtest with plan',function($t){
   $t->plan(1);
   $t->test('the planed subtest with plan',function($t){
      $t->plan(1);
      $t->ok(true);
   });
});

test('test with plan & subtest with end',function($t){
   $t->plan(1);
   $t->test('the planed subtest with end',function($t){
      $t->ok(true);
      $t->end();
   });
});

test('test with plan & subtest with plan and end',function($t){
   $t->plan(1);
   $t->test('the planed subtest with plan and end',function($t){
      $t->plan(1);
      $t->ok(true);
      $t->end();
   });
});

test('test that end with error',function($t){
   $t->end('end with error');
});