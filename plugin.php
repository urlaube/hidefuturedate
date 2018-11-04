<?php

  /**
    This is the HideFutureDate plugin.

    This file contains the HideFutureDate plugin. It hides content that has a
    set date field that lies in the future.

    @package urlaube\hidefuturedate
    @version 0.1a0
    @author  Yahe <hello@yahe.sh>
    @since   0.1a0
  */

  // ===== DO NOT EDIT HERE =====

  // prevent script from getting called directly
  if (!defined("URLAUBE")) { die(""); }

  class HideFutureDate extends BaseSingleton implements Plugin {

    // HELPER FUNCTIONS

    protected static function isHidden($content, $time) {
      $result = false;

      $date = value($content, DATE);
      if (null !== $date) {
        $date = strtotime($date);

        // only proceed if DATE is parsable
        if (false !== $date) {
          // check if DATE lies in the future
          $result = ($date > $time);
        }
      }

      return $result;
    }

    // RUNTIME FUNCTIONS

    public static function run($content) {
      $result = $content;

      // get the current time
      $time = time();

      if ($content instanceof Content) {
        if (static::isHidden($result, $time)) {
          $result = null;
        }
      } else {
        if (is_array($result)) {
          // iterate through all content items
          foreach ($result as $key => $value) {
            if ($value instanceof Content) {
              if (static::isHidden($value, $time)) {
                unset($result[$key]);
              }
            }
          }
        }
      }

      return $result;
    }

  }

  // register plugin
  Plugins::register(HideFutureDate::class, "run", FILTER_CONTENT);
