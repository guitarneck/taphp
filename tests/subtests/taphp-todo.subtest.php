<?php

require __DIR__.'/../../taphp.lib.php';

test('success', function ($t) {
   $t->equal(true, true, 'this test runs');
   $t->end();
});

test('failure with options', [ 'todo'=> true ], function ($t) {
   $t->fail('should never happens 1');
   $t->end();
});

todo('failure from function', function ($t) {
   $t->fail('should never happens 2');
   $t->end();
});

test('todo subtest', function ($t) {
   $t->test('the subtest todo', [ 'todo'=> true ], function ($t) {
       $t->fail('this should never happens 3');
       $t->end();
   });
   $t->pass('this runs', [ 'todo'=> true ]);
   $t->end();
});

test('todo in a test', function ($t) {
   $t->pass('this runs 4');
   $t->fail('failing assert but todo', [ 'todo'=> true ]);
   $t->pass('this runs too 4');
   $t->end();
});

test('todo inside', function ($t) {
   $t->todo();
   $t->pass('this pass in todo inside');
   $t->fail('this fail in todo inside');
   $t->end();
});

test('success after todo', function ($t) {
   $t->equal(true, true, 'this test runs after todo');
   $t->end();
});