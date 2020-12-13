<?php

require __DIR__.'/../../taphp.lib.php';

foreach ( ['error','ifError','ifErr','iferror','if_error'] as $assert )
{
   test("testing `$assert`",function($t) use ($assert) {
      try { throw new Exception("should reports `$assert`"); }
      catch ( Exception $e ) { $t->$assert($e); }

      try { throw new Exception("should reports `$assert`"); }
      catch ( Exception $e ) { $t->$assert($e,"should reports `$assert` with a better message"); }

      try { $t->$assert('not an exception'); }
      catch (Exception $e) { $t->ok( strpos($e,'first argument must be an exception')!==false,"`$assert` raised an exception" ); }

      $t->end();
   });
}

foreach ( ['exception','ifException','ifExcept','ifExpt','ifExp','ifexception','if_exception'] as $assert )
{
   test("testing `$assert`",function($t) use ($assert) {
      try { throw new Exception("should reports `$assert`"); }
      catch ( Exception $e ) { $t->$assert($e); }

      try { throw new Exception("should reports `$assert`"); }
      catch ( Exception $e ) { $t->$assert($e,"should reports `$assert` with a better message"); }

      try { $t->$assert('not an exception'); }
      catch (Exception $e) { $t->ok( strpos($e,'first argument must be an exception')!==false,"`$assert` raised an exception" ); }

      $t->end();
   });
}