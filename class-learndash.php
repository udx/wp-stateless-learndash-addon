<?php

namespace WPSL\LearnDash;

use wpCloud\StatelessMedia\Compatibility;
use wpCloud\StatelessMedia\Utility;

/**
 * @todo make testable and test
 */
class LearnDash extends Compatibility {
  protected $id = 'sfwd-lms';
  protected $title = 'LearnDash LMS';
  protected $constant = 'WP_STATELESS_COMPATIBILITY_LEARNDASH_LMS';
  protected $description = 'Ensures compatibility with LearnDash.';
  protected $plugin_file = ['sfwd-lms/sfwd_lms.php'];

  /**
   * @param $sm
   */
  public function module_init($sm) {
    // exclude randomize_filename from LearnDash page
    add_filter('stateless_skip_cache_busting', array($this, 'skip_cache_busting'), 10, 2);
  }

  /**
   * Whether skip cache busting or not.
   *
   * @param $return
   * @param $filename
   * @return mixed
   */
  public function skip_cache_busting($return, $filename) {
    if (strpos($filename, 'sfwd-') === 0 || $this->hook_from_learndash()) {
      return $filename;
    }
    return $return;
  }

  /**
   * Determine where we hook from
   * We need to do this only for something specific in LearnDash plugin
   *
   * @return bool
   */
  private function hook_from_learndash() {
    $call_stack = debug_backtrace();
    if (
      !empty($call_stack[6]['function']) &&
      $call_stack[6]['function'] == 'sanitize_file_name' &&
      (strpos($call_stack[6]['file'], 'class-ld-semper-fi-module.php') ||
        strpos($call_stack[6]['file'], 'class-ld-cpt-instance.php'))
    ) {
      return true;
    }

    return false;
  }
}
