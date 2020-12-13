<?php

require __DIR__.'/../../taphp.lib.php';

class MyTypeError extends Exception {}

/* exit on error: trigger_error */
test('hashes', function ($t) {
    $err = new MyTypeError('Wrong value',404);
 
    $t->throws(
        function () use ($err) { throw $err; },
        array(),
        'property is empty'
    );
 
    $t->end();
});