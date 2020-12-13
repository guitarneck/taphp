<?php

require __DIR__.'/../../taphp.lib.php';

foreach ( ['assert','ok','true'] as $assert )
{
   test("testing $assert",function($t) use ($assert) {
      $t->$assert(true,"testing `$assert` should succeed");
      $t->$assert(false,"testing `$assert` should failed");
      $t->end();
   });
}

foreach ( ['notOK','false','notok','not_ok'] as $assert )
{
   test("testing $assert",function($t) use ($assert) {
      $t->$assert(false,"testing `$assert` should succeed");
      $t->$assert(true,"testing `$assert` should failed");
      $t->end();
   });
}

foreach ( ['pass','fail'] as $assert )
{
   test("testing $assert",function($t) use ($assert) {
      $t->$assert('');
      $t->end();
   });
}