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

    const INFO = 'info', WARNING = 'warning', ERROR = 'error', SUCCESS = 'success';

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

        $session = Session::instance()->get(Notification::$session_key, array('notifications' => array(), 'errors' => array()));

        $this->errors = $session['errors'];
        $this->notifications = $session['notifications'];

        /**
         * Remove old notifications, we assume it has already been dislayed
         * since the singleton has been called.
         */
        Session::instance()->delete(Notification::$session_key);
    }

    /**
     * Captured errors.
     * 
     * @var type 
     */
    private $_errors = array();

    /**
     * Captured notifications.
     * 
     * @var type 
     */
    private $_notifications = array();

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
     * @param array  $values values used for substitution
     */
    public function add($level, $message, array $values = NULL) {

        // For this request
        $this->notifications[] = array(
            'level' => $level,
            'message' => __($message, $values),
        );

        // To be stored
        $this->_notifications[] = array(
            'level' => $level,
            'message' => __($message, $values),
        );

        $session = Session::instance()->get(Notification::$session_key);

        $session['notifications'] = $this->_notifications;

        Session::instance()->set(Notification::$session_key, $session);
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
    public function errors($errors, $file = NULL, $translate = TRUE) {

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

        // To be stored
        $this->_errors = Arr::merge($this->_errors, $errors);

        $session = Session::instance()->get(Notification::$session_key);

        $session['errors'] = $this->_errors;

        Session::instance()->set(Notification::$session_key, $session);
    }

}
