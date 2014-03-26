<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Notification manager.
 * 
 * @package Notifications
 * @author Guillaume Poirier-Morency <guillaumepoiriermorency@gmail.com>
 * @copyright (c) 2012, Hète.ca Inc.
 */
class Kohana_Notification {

    /**
     * These constants match the Bootstrap framework.
     */
    const INFO = 'info', WARNING = 'warning', DANGER = 'danger', SUCCESS = 'success';

    /**
     * Key used in Session for storing notifications.
     * 
     * @var string 
     */
    public static $session_key = 'notification';

    /**
     * Singleton.
     * 
     * @var Notification
     */
    protected static $instance;

    /**
     * Singleton.
     * 
     * @return Notification
     */
    public static function instance() {

        if (Notification::$instance === NULL) {
            Notification::$instance = new Notification();
        }

        return Notification::$instance;
    }

    protected function __construct() {

        $session = &Session::instance()->as_array();

        if (!isset($session['errors'])) {
            $session['errors'] = array();
        }

        if (!isset($session['notifications'])) {
            $session['notifications'] = array();
        }

        $this->errors = &$session['errors'];
        $this->notifications = &$session['notifications'];
    }

    /**
     * Errors for display (include those from session).
     * 
     * @var array
     */
    public $errors;

    /**
     * Notifications for display (include those from session).
     * 
     * @var array 
     */
    public $notifications;

    /**
     * Add a notification.
     * 
     * @param string $level
     * @param string $message
     * @param array  $values     values used for substitution
     * @param array  $attributes attributes for the generated HTML
     */
    public function add($level, $message, array $values = NULL, array $attributes = NULL) {

        // add some useful HTML5 tags
        $attributes['data-level'] = $level;

        $this->notifications[] = array(
            'level' => $level,
            'message' => __($message, $values),
            'attributes' => $attributes
        );
    }

    public function notifications() {

        $notifications = $this->notifications;

        // Clear when read
        $this->notifications = array();

        return $notifications;
    }

    /**
     * Add errors.
     *
     * Errors can be:
     * <ul>
     *     <li>ORM_Validation_Exception</li>
     *     <li>Validation_Exception</li>
     *     <li>Validation</li>
     * </ul>
     * 
     * @param variant ORM_Validation_Exception, Validation, 
     * Validation_Exception or a simple array.
     * @param string  $file file or directory to use for field name.
     * @param boolean $translate
     */
    public function errors($errors = NULL, $file = NULL, $translate = TRUE) {

        if ($errors === NULL) {

            $errors = $this->errors;

            // Clear when read
            $this->errors = array();

            return $errors;
        }

        if ($errors instanceof Validation) {
            $errors = Arr::flatten($errors->errors($file, $translate));
        }

        if ($errors instanceof ORM_Validation_Exception) {
            $errors = Arr::flatten($errors->errors($file, $translate));
        }

        if ($errors instanceof Validation_Exception) {
            $errors = Arr::flatten($errors->array->errors($file, $translate));
        }

        // For this request
        $this->errors = Arr::merge($this->errors, $errors);
    }

}
