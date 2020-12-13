<?php

require __DIR__.'/../../taphp.lib.php';

test('timeout suite with no plan nor end',['timeout'=>2500],function($t){
   sleep(1);
   $t->ok(true);
});

test('timeout suite with plan',['timeout'=>3000],function($t){
   $t->plan(1);
   sleep(1);
   $t->ok(true);
});

test('timeout suite with end',['timeout'=>3500],function($t){
   sleep(1);
   $t->ok(true);
   $t->end();
});

test('timeout suite with plan and end',['timeout'=>4000],function($t){
   $t->plan(1);
   sleep(1);
   $t->ok(true);
   $t->end();
});

test('timeout test with no plan nor end',function($t){
   $t->timeout(function($t){
      sleep(1);
      $t->ok(true);
   },2500,'timeout test with no plan nor end');
});

test('timeout test with plan',function($t){
   $t->plan(1);
   $t->timeout(function($t){
      sleep(1);
      $t->ok(true);
   },3000,'timeout test with plan');
});

test('timeout test with end',function($t){
   $t->timeout(function($t){
      sleep(1);
      $t->ok(true);
   },3500,'timeout test with end');
   $t->end();
});

test('timeout test with plan and end',function($t){
   $t->plan(1);
   $t->timeout(function($t){
      sleep(1);
      $t->ok(true);
   },4000,'timeout test with plan and end');
   $t->end();
});