<?php

namespace WPSL\LearnDash;

use PHPUnit\Framework\TestCase;
use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Brain\Monkey;
use Brain\Monkey\Actions;
use Brain\Monkey\Filters;
use Brain\Monkey\Functions;
use wpCloud\StatelessMedia\WPStatelessStub;

/**
 * Class ClassLearnDashTest
 */
class ClassLearnDashTest extends TestCase {

  // Adds Mockery expectations to the PHPUnit assertions count.
  use MockeryPHPUnitIntegration;

  const TEST_LD_FILE = 'sfwd-file.ext';
  const TEST_FILE = 'file.ext';

  private static $debugBacktrace;

  public static function getDebugBacktrace() {
    return self::$debugBacktrace;
  }

  public function setUp(): void {
		parent::setUp();
		Monkey\setUp();

    self::$debugBacktrace = [];
  }

  public function tearDown(): void {
		Monkey\tearDown();
		parent::tearDown();
	}

  public function testShouldInitModule() {
    $learndash = new LearnDash();

    $learndash->module_init([]);
    
    self::assertNotFalse( has_filter('stateless_skip_cache_busting', [ $learndash, 'skip_cache_busting' ]) );
  }

  public function testShouldSkipCacheBusting() {
    $learndash = new LearnDash();

    $this->assertEquals(
      self::TEST_LD_FILE, 
      $learndash->skip_cache_busting('test', self::TEST_LD_FILE)
    );

    self::$debugBacktrace = [
      '6' => [
        'function' => 'sanitize_file_name',
        'file' => 'dir/class-ld-semper-fi-module.php',
      ]
    ];

    $this->assertEquals(
      self::TEST_FILE, 
      $learndash->skip_cache_busting('test', self::TEST_FILE)
    );
  }

  public function testShouldNotSkipCacheBusting() {
    $learndash = new LearnDash();

    $this->assertEquals(
      'test', 
      $learndash->skip_cache_busting('test', self::TEST_FILE)
    );
  }
}

function debug_backtrace() {
  return ClassLearnDashTest::getDebugBacktrace();
}