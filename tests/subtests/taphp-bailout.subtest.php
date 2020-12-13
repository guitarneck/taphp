<?php

require __DIR__.'/../../taphp.lib.php';

test("testing bailout",function($t){
   $t->ok(true,'it should be ok');
   $t->bailout('critical error appears');
   $t->comment('you must not see this');
   $t->end();
});

test('testing bailout',function($t){
   $t->comment('you must not see this too');
   $t->end();
});