# taphp

TAP producer library for PHP. Based on tape for node. 

This library is not psr-0/4 compliant. Indeed, its contains functions to organize the running 
tests that make it unfriendly with composer's autoload way. 

> Tested on PHP versions : 5.6.9, 7.4.13 ,8.0.0

# example

with composer :

``` php
require dirname(__DIR__).'/vendor/guitarneck/taphp/taphp.lib.php';
```

without composer :

``` php
require_once 'taphp.lib.php';
```

``` php
test('timing test', function ($t) {
   $t->plan(2);

   $t->equal(get_class($t), 'TAPHP');
   $start = time();

   sleep(1);
   $t->equal(time() - $start, 0);
});
```

```
$ php .\test.php
TAP version 13
# timing test
ok 1 it should strictly be equal
not ok 2 it should strictly be equal
  ---
  actual  : 1
  expected: 0
  ...
1..2
# tests 2 (1.0868s)
# pass  1
# fail  1
```
# TAPHP

This class is a singleton.

## instance()

Returns the instance for this class. If realy you need it.

# functions

The functions and methods are nearly the same than in [tape](https://github.com/substack/tape), with some differents due to PHP language.

The so called `deep` methods are here for compliance with `tape`. PHP does it nativly.

## only ( [$name], [$options], $callback )

Declare a test that will be the only one to be runned.

## skip ( [$name], [$options], $callback )

Declare a test that will be skipped. It won't run.

## test ( [$name], [$options], $callback )

Declare a new test. `$name` and `$options` are optional. `$callback` will be fired after preceding one's,
with a parameter giving access to `TAPHP` object's methods.

## todo ( [$name], [$options], $callback )

Declare a test that still needs to do. Failure will be allowed.

## options

Available `options` are:
- 'todo' => true|false. See function [todo](##todo-(-[$name],-[$options],-$callback-)) and method [todo](##todo-(-[$text],-[$options]-)).
- 'skip' => true|false. See function [skip](##skip-(-[$name],-[$options],-$callback-)) and method [skip](##skip-(-[$text],-[$options]-)).
- 'only' => true|false. See function [only](##only-(-[$name],-[$options],-$callback-)).
- 'timeout' => null|integer. Sets a timeout for the test, after which it will fail. See method [timeout](##timeout-(-$callback,-$ms,-[$text]-)).

# methods

## assert ( $condition, [$text] )

Assert that `condition` is truthy with an optional description of the assertion `$text`.

Aliases: `ok`, `true`

## bailout ( [$text] )

Generate an immediate exit and stop of all the tests with and optional `$text` as reason.

## comment ( $text )

Generate a comment with a message `$text`.

## deepEqual ( $any, $val, [$text] )

Assert that `$any === $val` with an optional description of the assertion `$text`.

Aliases: `deepEquals`, `isEquivalent`, `same`, `deep_equal`

## deepLooseEqual ( $any, $val, [$text] )

Assert that `$any == $val` with an optional description of the assertion `$text`.

Aliases: `deep_loose_equal`

## end ( [$error] )

Declare the end of a test, with an optional generated `$error`.

## error ( $error, [$text] )

Generate a failure for a given `$error`, as an object type of Exception, with an optional `$test` description.

Aliases: `ifError`, `ifErr`, `iferror`, `if_error`

## exception ( $exception, [$text] )

Generate a failure for a given `$exception`, as an object type of Exception, with an optional `$test` description.

Aliases: `ifException`, `ifExcept`, `ifExpt`, `ifExp`, `ifexception`, `if_exception`

## fail ( [$text], [$options] )

Generate a failing assertion with a message `$text`.

## looseEqual ( $any, $val, [$text] )

Assert that `$any == $val` with an optional description of the assertion `$text`.

Aliases: `looseEquals`, `loose_equal`

## no ( $condition, [$text] )

Assert that `condition` is falsy with an optional description of the assertion `$text`.

Aliases: `notOK`, `false`, `notok`, `not_ok`

## notDeepEqual ( $any, $val, [$text] )

Assert that `$any !== $val` with an optional description of the assertion `$text`.

Aliases: `notDeepEquals`, `notEquivalent`, `notDeeply`, `notSame`, `isNotDeepEqual`, `isNotDeeply`, `isNotEquivalent`, `isInequivalent`, `not_deep_equal`, `not_same`

## notDeepLooseEqual ( $any, $val, [$text] )

Assert that `$any != $val` with an optional description of the assertion `$text`.

Aliases: `not_deep_loose_equal`

## notLooseEqual ( $any, $val, [$text] )

Assert that `$any != $val` with an optional description of the assertion `$text`.

Aliases: `notLooseEquals`, `not_loose_equal`

## notStrictEqual ( $any, $val, [$text] )

Assert that `$any !== $val` with an optional description of the assertion `$text`.

Aliases: `notEqual`, `notEquals`, `isNotEqual`, `doesNotEqual`, `isInequal`, `notStrictEquals`, `isNot`, `not`, `not_strict_equal`, `not_equal`, `is_not_equal`, `is_not`

## pass ( [$text], [$options] )

Generate a passing assertion with a message `$text`.

## plan ( $n )

Declare the number of assertions that's about to be runned. `end()` will be called
automatically after, or an error occurs if the number of assertions doesn't match.

## restoreTimeLimit ()

Restore PHP `max_execution_time`.

## setTimeLimit ( $seconds )

Change PHP `max_execution_time`.

## skip ( [$text], [$options] )

Generate a `skip` with and optional `$text` ane leaving the running test.

## strictEqual ( $any, $val, [$text] )

Assert that `$any === $val` with an optional description of the assertion `$text`.

Aliases: `equal`, `equals`, `isEqual`, `strictEquals`, `is`, `strict_equal`, `is_equal`

## test ( [$name], [$options], $callback )

Generate a new test at run time.

## throws ( $thrower, [$throwed], [$text] )

Assert that the function call `$thrower` throws an exception. `$throwed` can be a string, a regular expression, an exception, an array describing some exception properties, or null.

## timeout ( $callback, $ms, [$text] )

Generate an assertion that should run before `$ms` microseconds are elapse. Or it fails with an optional `$text`.

## todo ( [$text], [$options] )

Generate a `todo` with and optional `$text` inside a test, switching the next assertions to `todo` mode.

# License

[MIT © guitarneck](./LICENSE)