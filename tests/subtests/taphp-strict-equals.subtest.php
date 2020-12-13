<?php

require __DIR__.'/../../taphp.lib.php';

foreach ( ['strictEqual','equal','equals','isEqual','strictEquals','is','strict_equal','is_equal'] as $assert )
{
   test("testing `$assert`",function($t) use ($assert) {
		$t->$assert(true,true,"testing `$assert` should succeed");
		$t->$assert(1,true,"testing `$assert` should failed");

		$t->$assert([true,1],[true,1],"testing deep `$assert` should succeed");
		$t->$assert([true,1],[1,true],"testing deep `$assert` should failed");

		$t->end();
   });
}

foreach ( ['notStrictEqual','notEqual','notEquals','isNotEqual','doesNotEqual','isInequal','notStrictEquals','isNot','not','not_strict_equal','not_equal','is_not_equal','is_not'] as $assert )
{
	test("testing `$assert`",function($t) use ($assert) {
		$t->$assert(1,true,"testing `$assert` should succeed");
		$t->$assert(true,true,"testing `$assert` should failed");
		
		$t->$assert([1,true],[true,true],"testing deep `$assert` should succeed");
		$t->$assert([true,1],[true,1],"testing deep `$assert` should failed");

		$t->end();
   });
}