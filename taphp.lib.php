<?php
if ( !defined('__TAPHP_LIB__') ):
   define('__TAPHP_LIB__',1);

/**
 * @file taphp.lib.php
 * @class TAPHP
 * @author Laurent S.
 * @since 2020-11-06 
 * @see http://testanything.org/
 */

define('TAP_EOL',"\n");

/**
 * TAP PHP reporter interface
 * @abstract
 * @class AbstractTAPHPReporter
 */
abstract class AbstractTAPHPReporter
{
   protected $done   = 0;
   protected $fail   = 0;
   protected $todoed = 0;
   protected $skiped = 0;

   protected $plan   = null;
   protected $eval   = null;
   protected $ended  = null;

   protected $time   = null;

   protected $bailed = false;

   protected $todo   = false;
   protected $skip   = false;
   protected $only   = false;

   abstract protected function open();
   abstract protected function report ();
   abstract protected function header ( $opts );
   abstract protected function display ( $text );
   abstract protected function bailouting ( $text='' );
   abstract protected function diagnose ( $texts );
   abstract protected function success ( $opts );
   abstract protected function failure ( $opts );
   abstract protected function duration ( $ms );
   abstract protected function close();
}

/**
 * TAP PHP Reporter
 * @class TAPHPReporter
 */
class TAPHPReporter extends AbstractTAPHPReporter
{
   protected $reporter;
   public function setReporter ( $reporter ) { $this->reporter = $reporter;}

   protected function open () { $this->reporter->open(); }
   protected function report () { $this->reporter->report(); }
   protected function header ( $opts ) { $this->reporter->header($opts); }
   protected function display ( $text ) { $this->reporter->display($text); }
   protected function bailouting ( $text='' ) { $this->reporter->bailouting($text); }
   protected function diagnose ( $texts ) { $this->reporter->diagnose($texts); }
   protected function success ( $opts ) { $this->reporter->success($opts); }
   protected function failure ( $opts ) { $this->reporter->failure($opts); }
   protected function duration ( $ms ) { $this->reporter->duration($ms); }
   protected function close () { $this->reporter->close(); }
}

/**
 * Basic reporter : TAP version 13
 * @class TAPHPBasicReporter
 * @todo stream output, step by step, not all in once.
 */
class TAPHPBasicReporter extends AbstractTAPHPReporter
{
   private
   function out ( $lines )
   {
      $output = is_array($lines) ? implode(TAP_EOL, $lines) : $lines;
      file_put_contents('php://output', $output . TAP_EOL);
   }

   protected
   function open ()
   {
      $this->out('TAP version 13');
   }

   protected
   function report ()
   {
      if ( ! $this->bailed )
      {
         $this->out(
            array(
               sprintf('1..%d',$this->rank()),
               sprintf('# tests %d (%0.4fs)',$this->rank(),$this->time),
               sprintf('# pass  %d',$this->done + $this->todoed)
            )
         );

         if ( $this->fail > 0 )
            $this->out(array(sprintf('# fail  %d',$this->fail)));

         if ( $this->todoed > 0 )
            $this->out(array(sprintf('# todo  %d',$this->todoed)));

         if ( $this->skiped > 0 )
            $this->out(array(sprintf('# skip  %d',$this->skiped)));
      }
   }

   private
   function strize ( $s )
   {
      return is_string($s)?$s:'';
   }

   private
   function directive ( $opts )
   {
      return $opts['skip'] ? ' # SKIP '.$this->strize($opts['skip']) : 
            ($opts['todo'] ? ' # TODO '.$this->strize($opts['todo']) : ' ');
   }

   protected
   function header ( $opts )
   {
      $this->out(
         trim(
            sprintf(
               '# %s%s',
               $opts['name'],
               $this->directive($opts['opts'])
            )
         )
      );
   }

   protected
   function display ( $text )
   {
      $this->out(
         trim(
            sprintf('# %s',$text)
         )
      );
   }

   protected
   function bailouting ( $text='' )
   {
      $this->out(
         trim(
            sprintf('Bail out! %s',$text)
         )
      );
      $this->bailed = true;
   }

   protected
   function diagnose ( $texts )
   {
      $this->out('  ---');
      $m = 0;
      foreach( array_keys($texts) as $k ) $m = max($m,strlen($k));
      foreach ( $texts as $k => $v )
      {
         if ( ! is_string($v) ) $v = print_r($v,true);
         $this->out(
            sprintf(
               '  %s: %s',
               str_pad($k,$m,' ',STR_PAD_RIGHT),
               trim($this->linewrap($v,65,array('pad'=>$m+4)))
            )
         );
      }
      $this->out('  ...');
   }

   private
   function rank ()
   {
      return $this->done + $this->fail + $this->todoed + $this->skiped;
   }

   protected
   function success ( $opts )
   {
      if ($opts['todo']) $this->todoed++;
      elseif ($opts['skip']) $this->skiped++;
      else $this->done++;

      $mess = trim(sprintf('%s%s',$opts['mess'],$this->directive($opts)));

      $this->out(
         sprintf(
            'ok %d %s',
            $this->rank(),
            $mess
         )
      );
   }

   protected
   function failure ( $opts )
   {
      if ($opts['todo']) $this->todoed++;
      elseif ($opts['skip']) $this->skiped++;
      else $this->fail++;
      
      $mess = trim(sprintf('%s%s',$opts['mess'],$this->directive($opts)));

      $this->out(
         sprintf(
            'not ok %d %s',
            $this->rank(),
            $mess
         )
      );
   }

   protected
   function close ()
   {
      if ( $this->fail + $this->todoed === 0 && ! $this->bailed ) $this->out('# ok');
   }

   protected
   function duration ( $ms )
   {
      $this->time = $ms;
   }

   private
   function linewrap ( $string, $width, $extra=array() )
   {
      $break   = key_exists('break',$extra) ? $extra['break'] : TAP_EOL;
      $cut     = key_exists('cut',$extra) ? $extra['cut'] : false;
      $pad     = key_exists('pad',$extra) ? $extra['pad'] : 0;

      $array   = explode($break, trim($string));
      if ( $width > 0 )
      {
         $strings = array();
         foreach ( $array as $val )
            $strings[] = wordwrap($val, $width, $break, $cut);
      }
      else
         $strings = $array;

      return implode($break, array_map(function ($v) use ($pad) {
         return str_repeat(' ', $pad) . $v;
      }, $strings));
   }

}

/**
 * TAPHP Main Class
 *
 * @method void assert(boolean $succeed, string $description=null)   A must be true assertion with optional description.
 * @method void ok(boolean $succeed, string $description=null)   A must be true assertion with optional description.
 * @method void true(boolean $succeed, string $description=null)   A must be true assertion with optional description.
 * @method void no(boolean $failed, string $description=null)   A must be false assertion with optional description.
 * @method void notOK(boolean $failed, string $description=null)   A must be false assertion with optional description.
 * @method void false(boolean $failed, string $description=null)   A must be false assertion with optional description.
 * @method void notok(boolean $failed, string $description=null)   A must be false assertion with optional description.
 * @method void not_ok(boolean $failed, string $description=null)   A must be false assertion with optional description.
 * @method void error(\Exception $error, string $description=null)   A must be an \Exception with optional description.
 * @method void ifError(\Exception $error, string $description=null)   A must be an \Exception with optional description.
 * @method void ifErr(\Exception $error, string $description=null)   A must be an \Exception with optional description.
 * @method void iferror(\Exception $error, string $description=null)   A must be an \Exception with optional description.
 * @method void if_error(\Exception $error, string $description=null)   A must be an \Exception with optional description.
 * @method void exception(\Exception $error, string $description=null)   A must be an \Exception with optional description.
 * @method void ifException(\Exception $error, string $description=null)   A must be an \Exception with optional description.
 * @method void ifExcept(\Exception $error, string $description=null)   A must be an \Exception with optional description.
 * @method void ifExpt(\Exception $error, string $description=null)   A must be an \Exception with optional description.
 * @method void ifExp(\Exception $error, string $description=null)   A must be an \Exception with optional description.
 * @method void ifexception(\Exception $error, string $description=null)   A must be an \Exception with optional description.
 * @method void if_exception(\Exception $error, string $description=null)   A must be an \Exception with optional description.
 * @method void strictEqual(mixed $actual, mixed $expected, string $description=null)   A must be strict equal assertion between $actual and $expected with optional description.
 * @method void equal(mixed $actual, mixed $expected, string $description=null)   A must be strict equal assertion between $actual and $expected with optional description.
 * @method void equals(mixed $actual, mixed $expected, string $description=null)   A must be strict equal assertion between $actual and $expected with optional description.
 * @method void isEqual(mixed $actual, mixed $expected, string $description=null)   A must be strict equal assertion between $actual and $expected with optional description.
 * @method void strictEquals(mixed $actual, mixed $expected, string $description=null)   A must be strict equal assertion between $actual and $expected with optional description.
 * @method void is(mixed $actual, mixed $expected, string $description=null)   A must be strict equal assertion between $actual and $expected with optional description.
 * @method void strict_equal(mixed $actual, mixed $expected, string $description=null)   A must be strict equal assertion between $actual and $expected with optional description.
 * @method void is_equal(mixed $actual, mixed $expected, string $description=null)   A must be strict equal assertion between $actual and $expected with optional description.
 * @method void notStrictEqual(mixed $actual, mixed $expected, string $description=null)   A must not be strict equal assertion between $actual and $expected with optional description.
 * @method void notEqual(mixed $actual, mixed $expected, string $description=null)   A must not be strict equal assertion between $actual and $expected with optional description.
 * @method void notEquals(mixed $actual, mixed $expected, string $description=null)   A must not be strict equal assertion between $actual and $expected with optional description.
 * @method void isNotEqual(mixed $actual, mixed $expected, string $description=null)   A must not be strict equal assertion between $actual and $expected with optional description.
 * @method void doesNotEqual(mixed $actual, mixed $expected, string $description=null)   A must not be strict equal assertion between $actual and $expected with optional description.
 * @method void isInequal(mixed $actual, mixed $expected, string $description=null)   A must not be strict equal assertion between $actual and $expected with optional description.
 * @method void notStrictEquals(mixed $actual, mixed $expected, string $description=null)   A must not be strict equal assertion between $actual and $expected with optional description.
 * @method void isNot(mixed $actual, mixed $expected, string $description=null)   A must not be strict equal assertion between $actual and $expected with optional description.
 * @method void not(mixed $actual, mixed $expected, string $description=null)   A must not be strict equal assertion between $actual and $expected with optional description.
 * @method void not_strict_equal(mixed $actual, mixed $expected, string $description=null)   A must not be strict equal assertion between $actual and $expected with optional description.
 * @method void not_equal(mixed $actual, mixed $expected, string $description=null)   A must not be strict equal assertion between $actual and $expected with optional description.
 * @method void is_not_equal(mixed $actual, mixed $expected, string $description=null)   A must not be strict equal assertion between $actual and $expected with optional description.
 * @method void is_not(mixed $actual, mixed $expected, string $description=null)   A must not be strict equal assertion between $actual and $expected with optional description.
 * @method void looseEqual(mixed $actual, mixed $expected, string $description=null)   A must be loose equal assertion between $actual and $expected with optional description.
 * @method void looseEquals(mixed $actual, mixed $expected, string $description=null)   A must be loose equal assertion between $actual and $expected with optional description.
 * @method void loose_equal(mixed $actual, mixed $expected, string $description=null)   A must be loose equal assertion between $actual and $expected with optional description.
 * @method void notLooseEqual(mixed $actual, mixed $expected, string $description=null)   A must not be loose equal assertion between $actual and $expected with optional description.
 * @method void notLooseEquals(mixed $actual, mixed $expected, string $description=null)   A must not be loose equal assertion between $actual and $expected with optional description.
 * @method void not_loose_equal(mixed $actual, mixed $expected, string $description=null)   A must not be loose equal assertion between $actual and $expected with optional description.
 * @method void deepEqual(mixed $actual, mixed $expected, string $description=null)   A must be deep strict equal assertion between $actual and $expected with optional description.
 * @method void deepEquals(mixed $actual, mixed $expected, string $description=null)   A must be deep strict equal assertion between $actual and $expected with optional description.
 * @method void isEquivalent(mixed $actual, mixed $expected, string $description=null)   A must be deep strict equal assertion between $actual and $expected with optional description.
 * @method void same(mixed $actual, mixed $expected, string $description=null)   A must be deep strict equal assertion between $actual and $expected with optional description.
 * @method void deep_equal(mixed $actual, mixed $expected, string $description=null)   A must be deep strict equal assertion between $actual and $expected with optional description.
 * @method void notDeepEqual(mixed $actual, mixed $expected, string $description=null)   A must not be deep strict equal assertion between $actual and $expected with optional description.
 * @method void notDeepEquals(mixed $actual, mixed $expected, string $description=null)   A must not be deep strict equal assertion between $actual and $expected with optional description.
 * @method void notEquivalent(mixed $actual, mixed $expected, string $description=null)   A must not be deep strict equal assertion between $actual and $expected with optional description.
 * @method void notDeeply(mixed $actual, mixed $expected, string $description=null)   A must not be deep strict equal assertion between $actual and $expected with optional description.
 * @method void notSame(mixed $actual, mixed $expected, string $description=null)   A must not be deep strict equal assertion between $actual and $expected with optional description.
 * @method void isNotDeepEqual(mixed $actual, mixed $expected, string $description=null)   A must not be deep strict equal assertion between $actual and $expected with optional description.
 * @method void isNotDeeply(mixed $actual, mixed $expected, string $description=null)   A must not be deep strict equal assertion between $actual and $expected with optional description.
 * @method void isNotEquivalent(mixed $actual, mixed $expected, string $description=null)   A must not be deep strict equal assertion between $actual and $expected with optional description.
 * @method void isInequivalent(mixed $actual, mixed $expected, string $description=null)   A must not be deep strict equal assertion between $actual and $expected with optional description.
 * @method void not_deep_equal(mixed $actual, mixed $expected, string $description=null)   A must not be deep strict equal assertion between $actual and $expected with optional description.
 * @method void not_same(mixed $actual, mixed $expected, string $description=null)   A must not be deep strict equal assertion between $actual and $expected with optional description.
 * @method void deepLooseEqual(mixed $actual, mixed $expected, string $description=null)   A must be deep loose equal assertion between $actual and $expected with optional description.
 * @method void deep_loose_equal(mixed $actual, mixed $expected, string $description=null)   A must be deep loose equal assertion between $actual and $expected with optional description.
 * @method void notDeepLooseEqual(mixed $actual, mixed $expected, string $description=null)   A must not be deep loose equal assertion between $actual and $expected with optional description.
 * @method void not_deep_loose_equal(mixed $actual, mixed $expected, string $description=null)   A must not be deep loose equal assertion between $actual and $expected with optional description.
 */
class TAPHP extends TAPHPReporter
{
   protected $tests;

   private  $running,
            $exit = true;

   /**
    * Retrieve the TAPHP object.
    * @param void
    * @return TAPHP  The TAPHP object.
    */
   static public
   function instance ()
   {
      static $instance = null;
      if ( $instance == null ) $instance = new TAPHP();
      return $instance; 
   }

   static public
   function parse_arguments ()
   {
      $name    = '<unknown>';
      $options = array(
         'todo'   => false,
         'skip'   => false,
         'only'   => false,
         'timeout'=> null
      );
      $func    = function (){};

      foreach ( func_get_args() as $arg )
      {
         if ( is_string($arg) ) $name = !empty($arg)?$arg:$name;
         elseif ( is_array($arg) ) $options = array_merge($options,$arg);
         elseif ( is_callable($arg) ) $func = $arg;
         else trigger_error('bad argument type',E_USER_ERROR);
      }
      return array($name,$options,$func);
   }

   static public
   function parse_defaults ( $defaults )
   {
      $args       = func_get_args();
      $defaults   = array_shift($args);
      $args       = array_pop($args);
      list($name, $opts, $call) = call_user_func_array('TAPHP::parse_arguments',$args);
      return array($name, array_merge($opts,$defaults), $call);
   }

   private
   function __construct ()
   {
      $this->tests   = array();
      $this->bailed  = false;
      $this->running = false;

      register_shutdown_function(function(TAPHP $taphp){
         $taphp->execute();
      },$this);
   }

   public
   function __destruct ()
   {
      // $this->execute();
      if ( $this->exit )
         exit(($this->fail > 0 || $this->bailed) ? 255 : 0);
   }

   private
   function execute ()
   {
      $this->running = true;
      ob_start();
      $this->open();
      $start = $this->mstime();
      $this->run( $this->tests );
      $error = error_get_last();
      if ( $error !== null && $error['type'] === E_USER_WARNING ) 
      {
         ob_end_clean();
         return;
      }
      $this->duration( $this->mstime() - $start );
      $this->report();
      $this->close();
      ob_end_flush();
   }   

   private
   function run ( $tests )
   {
      $stack = array();

      foreach ( $tests as $test )
      {
         if ( $this->bailed ) break;
         if ( $this->only && ! $test['opts']['only'] ) continue;

         $this->skip = false;
         $this->todo = $test['opts']['todo'];

         $this->header($test);
         if ( $test['opts']['skip'] ) continue;

         $test['plan'] = null;
         $test['eval'] = 0;
         $test['exit'] = false;

         if ( $this->plan != null )
         {
            $this->eval++;
            $stack[] = array($this->plan,$this->eval,$this->ended);
         }

         $this->plan = & $test['plan'];
         $this->eval = & $test['eval'];
         $this->ended= & $test['exit'];

         if ( $test['opts']['timeout'] != null )
            $this->timeout($test['func'],$test['opts']['timeout'],$test['name']);
         else
            call_user_func($test['func'],$this);

         //if ( error_get_last() !== null ) break;

         if ( $test['plan'] !== null )
         {
            if ( $test['plan'] != $test['eval'] )
               $this->fail('plan != count: '.$test['name']);
         }
         elseif ( ! $this->ended )
            $this->fail('test exited without ending: '.$test['name']);

         if ( count($stack) > 0 )
            list($this->plan,$this->eval,$this->ended) = array_pop($stack);
      }
   }

   /**
    * Discard termination of TAPHP with exit.
    * @return void
    */
   public
   function discardExit ()
   {
      $this->exit = false;
   }

   /**
    * Change the time limits execution for the duration of the tests.
    *
    * @param int $seconds  The seconds to be sets.
    * @return void
    */
   public
   function setTimeLimit ( $seconds )
   {
      ini_set('max_execution_time',$seconds);
   }

   /**
    * Restore the time limits to its default.
    *
    * @return void
    */
   public
   function restoreTimeLimit ()
   {
      ini_restore('max_execution_time');
   }

   private
   function mstime ()
   {
      list($usec,$sec) = explode(' ',microtime());
      return ((float)$usec + (float)$sec);
   }

   /**
    * Create a test for assertions. Immediate running inside a test.
    *
    * @param string $name  The name of the test. 
    * @param array $options   Optional options.
    * @param callable $func   The callback for the tests.
    * @return void
    */
   public
   function test ( $name, $options=array(), $func=null )
   {
      list($name,$options,$func) = call_user_func_array('TAPHP::parse_arguments',func_get_args());

      if ( $options['only'] )
         if ( $this->only )
            trigger_error('there can only be one `only test`',E_USER_WARNING);
         else
            $this->only = true;

      if ( ! $this->running )
         $this->tests[] = array(
            'name'   => $name,
            'opts'   => $options,
            'func'   => $func
         );
      else
         $this->run( array(
            array(
               'name'   => $name,
               'opts'   => $options,
               'func'   => $func
            )
         ));
   }

   /**
    * Immeditate stop and exit all the tests.
    *
    * @param string $text  Optional reason of the end of the tests.
    * @return void
    */
   public
   function bailout ( $text='' )
   {
      $this->bailouting($text);
      $this->bailed = true;
   }

   protected
   function result ( $parms )
   {
      if ( $this->bailed || $this->skip ) return;

      if ( $this->plan !== null ) $this->eval++;

      $parms = array_merge(
         array(
            'todo'   => $this->todo,
            'skip'   => false
         ),
         $parms
      );

      if( $parms['cond'] === true )//&& ! $parms['todo'] )
      {
         $this->success($parms);
         return;
      }
      
      $this->failure($parms);

      if ( $parms['todo'] || $parms['skip'] ) return;

      if ( key_exists('need',$parms) )
      {
         $this->diagnose(array(
            'actual'    => $parms['real'] === null ? 'null' : $parms['real'],
            'expected'  => $parms['need'] === null ? 'null' : $parms['need']
         ));
      }
   }

   /**
    * A todo consigment.
    *
    * @param string $text  Optional text to be displayed.
    * @param array $options   Optional options.
    * @return void
    */
   public
   function todo ( $text='', $options=array() )
   {
      $options = array_merge(
         array(
            'cond'   => false,
            'todo'   => true,
            'mess'   => $text
         ),
         $options
      );
      $this->result($options);
      $this->todo = $options['todo'];
   }

   /**
    * Skip the test.
    *
    * @param string $text  Optional text to be displayed.
    * @param array $options   Optional options.
    * @return void
    */
   public
   function skip ( $text='', $options=array() )
   {
      $options = array_merge(
         array(
            'cond'   => true,
            'skip'   => true,
            'mess'   => $text
         ),
         $options
      );
      $this->result($options);
      $this->skip = $options['skip'];
   }

   /**
    * Success of the test.
    *
    * @param string|null $text   Optional text to be displayed.
    * @param array $options   Optional options.
    * @return void
    */
   public
   function pass ( $text=null, $options=array() )
   {
      $this->result(array_merge(
         $options,
         array(
            'cond'   => true,
            'mess'   => $text ? $text : 'it has passed'
         )
      ));
   }

   /**
    * Failure of the test.
    *
    * @param string|null $text   Optional text to be displayed.
    * @param array $options   Optional options.
    * @return void
    */
    public
   function fail ( $text=null, $options=array() )
   {
      $this->result(array_merge(
         $options,
         array(
            'cond'   => false,
            'mess'   => $text ? $text : 'it has failed'
         )
      ));
   }

   /**
    * Display a comment.
    *
    * @param string $text  The comment to be displayed.
    * @return void
    */
   public
   function comment ( $text )
   {
      if ( $this->bailed || $this->skip ) return;
      $this->display($text);
   }

   private
   function ___ok ( $condition, $text=null )
   {
      $this->result(array(
         'cond'   => $condition == true,
         'mess'   => $text ? $text : 'it should has succeed'
      ));
   }

   private
   function ___no ( $condition, $text=null )
   {
      $this->result(array(
         'cond'   => $condition == false,
         'mess'   => $text ? $text : 'it should has failed'
      ));
   }

   private
   function ___looseEqual ( $any, $val, $text=null )
   {
      $this->result(array(
         'cond'   => $any == $val,
         'real'   => $any,
         'need'   => $val,
         'mess'   => $text ? $text : 'it should be equal'
      ));
   } 

   private
   function ___looseUnequal ( $any, $val, $text=null )
   {
      $this->result(array(
         'cond'   => $any != $val,
         'real'   => $any,
         'need'   => $val,
         'mess'   => $text ? $text : 'it should not be equal'
      ));
   }

   private
   function ___strictEqual ( $any, $val, $text=null )
   {
      $this->result(array(
         'cond'   => $any === $val,
         'real'   => $any,
         'need'   => $val,
         'mess'   => $text ? $text : 'it should strictly be equal'
      ));
   } 

   private
   function ___strictUnequal ( $any, $val, $text=null )
   {
      $this->result(array(
         'cond'   => $any !== $val,
         'real'   => $any,
         'need'   => $val,
         'mess'   => $text ? $text : 'it should strictly not be equal'
      ));
   }

   private
   function ___error ( $err, $text=null )
   {
      if ( ! (is_object($err) && is_a($err,'Exception')) )
         throw new Exception('first argument must be an exception');

      $this->result(array(
         'cond'   => !$err,
         'mess'   => $text ? $text : $err->getMessage()
      ));   
   }

   /**
    * Assert an \Exception is thrown.
    *
    * @param callable $thrower   A callback throwing an exception.
    * @param mixed $throwed   An expected exception.
    * @param string $text  Optional description of the assertion.
    * @return void
    */
   public
   function throws ( $thrower, $throwed=null, $text=null )
   {
      $exception_string = '<null>';
      $exception = null;
      if ( is_callable($thrower) )
      {
         try { call_user_func($thrower, $this); } catch ( Exception $e ) { $exception = $e; }
      }

      $same = false;

      do {
         if ( $exception == null ) break;

         $exception_string = sprintf(
            '<%s> "%s"',
            get_class($exception),
            $exception->getMessage()
         );

         if ( is_a($throwed,'Exception') )
         {
            $same = get_class($exception) === get_class($throwed);
         }
         elseif ( is_string($throwed) )
         {
            if ( $this->is_regexp($throwed) )
               $same = preg_match($throwed,$exception->getMessage()) != 0;
            else
               $same = stripos($exception->getMessage(),$throwed) !== false;
         }
         elseif ( is_array($throwed) )
         {
            $keys = array_keys($throwed);
            if ( count($keys) == 0 )
               trigger_error('`throws` validation array must not be empty',E_USER_ERROR);

            $funx =
            array(
            'name'      => function ($e) { return get_class($e); },
            'message'   => function ($e) { return $e->getMessage(); },
            'code'      => function ($e) { return $e->getCode(); },
            'file'      => function ($e) { return $e->getFile(); },
            'at'        => function ($e)
                           {
                              $t = $e->getTrace();
                              if ( count($t) == 0 ) return '';
                              $t = $t[0];
                              return   (key_exists('type',$t) ? $t['class'].$t['type'] : '')
                                       . $t['function'];
                           }
            );

            $passed = array_reduce($keys, function ($bool,$key) use ($exception,$throwed,$funx)
            {
               try
               {
                  if ( ! key_exists($key,$funx) ) return 0;
                  $res = $funx[$key]($exception);
                  if ( $this->is_regexp($throwed[$key]) && preg_match($throwed[$key],$res) ) return $bool & 1;
                  if ( $throwed[$key] === $res ) return $bool & 1;
               }
               catch (Exception $e) {}
               return 0;
            }, 1);
            $same = $passed > 0;
         }      
         elseif ( $throwed == null )
            $same = true;

      } while (0);

      $this->result(array(
         'cond'   => $same,
         'real'   => $exception_string,
         'need'   => is_string($throwed)
                     ? sprintf('"%s"',$throwed) : (is_object($throwed) 
                     ? sprintf('<%s>',get_class($throwed)) : print_r($throwed,true)),
         'mess'   => $text ? $text : 'it should throws'
      ));
   }

   /**
    * An assertion that must have run before timeout.
    *
    * @param callable $fn  A callback to run.
    * @param int $ms Microseconds expected.
    * @param string $text  Optional description of the assertion.
    * @return void
    */
   public
   function timeout ( $fn, $ms, $text=null )
   {
      $time_limit = ini_get('max_execution_time') * 1000.0;
      if ( $time_limit !== 0.0 && ($time_limit < $ms) )
         trigger_error("execution time limit is not enought : {$time_limit}ms (cf. `max_execution_time`)",E_USER_WARNING);

      $time_start = $this->mstime();
      call_user_func($fn, $this);
      $time = intval(($this->mstime() - $time_start) * 1000.0);

      $this->result(array(
         'cond'   => $time <= $ms,
         'real'   => $time,
         'need'   => $ms,
         'mess'   => $text ? $text : 'it should runned before timeout exceed'
      ));
   }

   /**
    * Declare a number of assertions to be runned.
    *
    * @param int $n  The number of assertions to be runned.
    * @return void
    */
   public
   function plan ( $n )
   {
      if ( $this->plan !== null ) $this->fail('plan() already called.');
      $this->plan = $n;
   }

   /**
    * Declare the end of the test.
    *
    * @param string $error Optinal text to be displayed.
    * @return void
    */
   public
   function end ( $error='' )
   {
      if ( $error !== '' ) $this->fail( $error );
      $this->ended = true;
   }

   private
   function is_regexp ( $patrn )
   {
      return @preg_match($patrn,'') !== false;
   }

   //===
   function __call ( /*string*/ $name , /*array*/ $arguments ) 
   {
      static $functions = null;
      if ( $functions === null ):
         $functions = array(
            'assert'                => array($this,'___ok'),
            'ok'                    => array($this,'___ok'),
            'true'                  => array($this,'___ok'),

            'no'                    => array($this,'___no'),
            'notOK'                 => array($this,'___no'),
            'false'                 => array($this,'___no'),
            'notok'                 => array($this,'___no'),
            'not_ok'                => array($this,'___no'),

            'error'                 => array($this,'___error'),
            'ifError'               => array($this,'___error'),
            'ifErr'                 => array($this,'___error'),
            'iferror'               => array($this,'___error'),
            'if_error'              => array($this,'___error'),

            // for php
            'exception'             => array($this,'___error'),
            'ifException'           => array($this,'___error'),
            'ifExcept'              => array($this,'___error'),
            'ifExpt'                => array($this,'___error'),
            'ifExp'                 => array($this,'___error'),
            'ifexception'           => array($this,'___error'),
            'if_exception'          => array($this,'___error'),

            'strictEqual'           => array($this,'___strictEqual'),
            'equal'                 => array($this,'___strictEqual'),
            'equals'                => array($this,'___strictEqual'),
            'isEqual'               => array($this,'___strictEqual'),
            'strictEquals'          => array($this,'___strictEqual'),
            'is'                    => array($this,'___strictEqual'),
            'strict_equal'          => array($this,'___strictEqual'),
            'is_equal'              => array($this,'___strictEqual'),

            'notStrictEqual'        => array($this,'___strictUnequal'),
            'notEqual'              => array($this,'___strictUnequal'),
            'notEquals'             => array($this,'___strictUnequal'),
            'isNotEqual'            => array($this,'___strictUnequal'),
            'doesNotEqual'          => array($this,'___strictUnequal'),
            'isInequal'             => array($this,'___strictUnequal'),
            'notStrictEquals'       => array($this,'___strictUnequal'),
            'isNot'                 => array($this,'___strictUnequal'),
            'not'                   => array($this,'___strictUnequal'),
            'not_strict_equal'      => array($this,'___strictUnequal'),
            'not_equal'             => array($this,'___strictUnequal'),
            'is_not_equal'          => array($this,'___strictUnequal'),
            'is_not'                => array($this,'___strictUnequal'),

            'looseEqual'            => array($this,'___looseEqual'),
            'looseEquals'           => array($this,'___looseEqual'),
            'loose_equal'           => array($this,'___looseEqual'),

            'notLooseEqual'         => array($this,'___looseUnequal'),
            'notLooseEquals'        => array($this,'___looseUnequal'),
            'not_loose_equal'       => array($this,'___looseUnequal'),

            'deepEqual'             => array($this,'___strictEqual'),
            'deepEquals'            => array($this,'___strictEqual'),
            'isEquivalent'          => array($this,'___strictEqual'),
            'same'                  => array($this,'___strictEqual'),
            'deep_equal'            => array($this,'___strictEqual'),

            'notDeepEqual'          => array($this,'___strictUnequal'),
            'notDeepEquals'         => array($this,'___strictUnequal'),
            'notEquivalent'         => array($this,'___strictUnequal'),
            'notDeeply'             => array($this,'___strictUnequal'),
            'notSame'               => array($this,'___strictUnequal'),
            'isNotDeepEqual'        => array($this,'___strictUnequal'),
            'isNotDeeply'           => array($this,'___strictUnequal'),
            'isNotEquivalent'       => array($this,'___strictUnequal'),
            'isInequivalent'        => array($this,'___strictUnequal'),
            'not_deep_equal'        => array($this,'___strictUnequal'),
            'not_same'              => array($this,'___strictUnequal'),

            'deepLooseEqual'        => array($this,'___looseEqual'),
            'deep_loose_equal'      => array($this,'___looseEqual'),

            'notDeepLooseEqual'     => array($this,'___looseUnequal'),
            'not_deep_loose_equal'  => array($this,'___looseUnequal'),
         );
      endif;

      if ( key_exists($name,$functions) )
         call_user_func_array($functions[$name], $arguments );
      else
         trigger_error("$name is not a known method",E_USER_ERROR);
   }   
   //===
}

function test ($name='<unknown>',$options=array(),$func=null)
{
   list($name,$options,$func) = call_user_func_array('TAPHP::parse_arguments',func_get_args());
   return TAPHP::instance()->test($name,$options,$func);
}

function todo ($name='<unknown>',$options=array('todo'=>true),$func=null)
{
   list($name,$options,$func) = call_user_func_array('TAPHP::parse_defaults',array(array('todo'=>true),func_get_args()));
   return TAPHP::instance()->test($name,$options,$func);
}

function skip ($name='<unknown>',$options=array('skip'=>true),$func=null)
{
   list($name,$options,$func) = call_user_func_array('TAPHP::parse_defaults',array(array('skip'=>true),func_get_args()));
   return TAPHP::instance()->test($name,$options,$func);
}

function only ($name='<unknown>',$options=array('only'=>true),$func=null)
{
   list($name,$options,$func) = call_user_func_array('TAPHP::parse_defaults',array(array('only'=>true),func_get_args()));
   return TAPHP::instance()->test($name,$options,$func);
}

TAPHP::instance()->setReporter( new TAPHPBasicReporter() );

endif;