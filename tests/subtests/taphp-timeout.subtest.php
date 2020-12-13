<?php

require __DIR__.'/../../taphp.lib.php';

test("testing timeout success",['timeout'=>2000],function($t){
   $t->comment('this should succeed');
   sleep(1);
   $t->end();
});

test('testing timeout failure',['timeout'=>1000],function($t){
   $t->comment('this should failed');
   sleep(2);
   $t->end();
});

test("testing timeout subtest success",function($t){
   $t->timeout(function($t){
      sleep(1);
   },2000,'this timeout subtest succeed');
   $t->end();
});

test('testing timeout subtest failure',function($t){
   $t->timeout(function($t){
      sleep(2);
   },1000,'this timeout subtest failed');
   $t->end();
});