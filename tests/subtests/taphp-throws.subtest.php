<?php

require __DIR__.'/../../taphp.lib.php';

// Not ok, nothing to throw
test('function', function ($t) {
    $t->plan(1);
    $t->throws(function () {});
});

// Not ok, differents exceptions
test('wrong type of error', function ($t) {
    $t->plan(1);
    $t->throws(function () { throw new RangeException('actual!'); }, new RuntimeException(), 'throws actual');
    $t->end();
});

// ok, regexp should match
test('validate with regex', function ($t) {
    $t->plan(1);
    $t->throws(
        function () { throw new Exception('Wrong value'); },
        '/Wrong value$/',
        'regex against message'
    );
    $t->end();
});

// ok, but noticed
test('similar error object', function ($t) {
    $t->plan(1);
    $otherErr = new Exception('Not found');
    $err = $otherErr;
    $t->throws(
        function () use(&$otherErr) {
            throw $otherErr;
        },
        $err,
        'throwing a similar error'
    );
    $t->end();
});

class MyTypeError extends Exception {}

test('hashes', function ($t) {
   $err = new MyTypeError('Wrong value',404);

   $t->throws(
       function () use ($err) { throw $err; },
       array(
            'name'      => 'MyTypeError',
            'message'   => 'Wrong value',
            'code'      =>  404
       ),
       'properties are validated'
   );

   $t->end();
});
 
test('hashes', function ($t) {
    $err = new MyTypeError('Wrong value',404);
 
    $t->throws(
        function () use ($err) { throw $err; },
        array(
            'toto'      => 'MyTypeError',
            'message'   => 'Wrong value',
            'code'      =>  404
        ),
        'properties are false'
    );
 
    $t->end();
 });

test('hashes', function ($t) {
    $err = new MyTypeError('Wrong value',404);

    $t->throws(
        function () use ($err) { throw $err; },
        array(
            'name'      => '/TypeError$/',
            'message'   => '/ong val/'
        ),
        'properties with regex'
    );

    $t->end();
});

test('hashes', function ($t) {
    $err = new MyTypeError('Wrong value',404);
 
    $t->throws(
        function () use ($err) { throw $err; },
        array(
            'name'      => 'BadTypeError'
        ),
        'properties with bad value'
    );
 
    $t->end();
});