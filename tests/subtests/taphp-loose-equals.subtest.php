<?php

require __DIR__.'/../../taphp.lib.php';

foreach ( ['looseEqual','looseEquals','loose_equal'] as $assert )
{
   test("testing `$assert`",function($t) use ($assert) {
      $t->$assert(1,true,"testing `$assert` should succeed");
      $t->$assert(1,'1',"testing `$assert` should succeed");
      $t->$assert('1','01',"testing `$assert` should succeed");

      $t->$assert([1,1],[true,true],"testing `$assert` should succeed");
      $t->$assert(['1',1],['01',1],"testing `$assert` should succeed");

      $t->end();
   });
}

foreach ( ['notLooseEqual','notLooseEquals','not_loose_equal'] as $assert )
{
   test("testing `$assert`",function($t) use ($assert) {
      $t->$assert(1,true,"testing `$assert` should failed");
      $t->$assert(1,'1',"testing `$assert` should failed");
      $t->$assert('1','01',"testing `$assert` should failed");

      $t->$assert('1','a',"testing `$assert` should succeed");

      $t->$assert([1,1],[true,true],"testing `$assert` should failed");
      $t->$assert(['1',1],['01',1],"testing `$assert` should failed");
      
      $t->end();
   });
}