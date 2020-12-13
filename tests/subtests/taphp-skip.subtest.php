<?php

require __DIR__.'/../../taphp.lib.php';

test('skip with option', [ 'skip'=> true ], function ($t) {
   $t->fail('this should not even run 00');
   $t->end();
});

skip('skip function', function ($t) {
   $t->fail('this should not even run 01');
   $t->end();
});

test('skip subtest', function ($t) {
   $t->test('the subtest to skip', [ 'skip'=> true ], function ($t) {
       $t->fail('this should not even run 02');
       $t->end();
   });
   $t->pass('this runs');
   $t->end();
});

test('skip in a test', function ($t) {
   $t->pass('this runs 03');
   $t->fail('failing assert is skipped', [ 'skip'=> true ]);
   $t->pass('this runs too 03');
   $t->end();
});

test('skip with explanation', function ($t) {
   $platform = 'whatever';
   $t->fail('run only if `whatever` platform',
       [ 'skip'=> $platform === 'whatever' ? "Because, this is a long explenation why it's skiped" : false ]
   );
   $t->end();
});

test('tests might skip in the middle', function ($t) {
   $t->pass('this runs 05');
   $t->skip('stop here, no test anymore');
   $t->fail('failing assert must be skipped 05');
   $t->end();
});

test('canceling skip in the middle', function ($t) {
   $t->pass('this runs 06');
   $t->skip('skip canceled',[ 'skip'=> false ]);
   $t->pass('this should runs too 06');
   $t->end();
});