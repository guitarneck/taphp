<?php

require __DIR__.'/../../taphp.lib.php';

only('testing only',function($t){
   $t->pass('you must see only this');
   $t->end();
});

only('testing only failure',function($t){
   $t->pass('you must not see this');
   $t->end();
});