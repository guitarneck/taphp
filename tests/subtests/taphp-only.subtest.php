<?php

require __DIR__.'/../../taphp.lib.php';

test("testing only",function($t){
   $t->fail('you must not see this');
   $t->end();
});

only('testing only',function($t){
   $t->pass('you must see only this');
   $t->end();
});

test("testing only",function($t){
   $t->fail('you must not see this');
   $t->end();
});