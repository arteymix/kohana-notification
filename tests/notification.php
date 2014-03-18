<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * Tests for Notification module.
 * 
 * @package Notification
 * @category Tests
 * @author Guillaume Poirier-Morency <john.doe@example.com>
 * @copyright (c) 2012, Hète.ca Inc.
 */
class Notification_Test extends Unittest_TestCase {

    /**
     * Test for adding notification
     */
    public function test_add_notification() {

        $this->assertCount(0, Notification::instance()->notifications);

        Notification::instance()->add(Notification::ERROR, 'crap :toto', array(':toto' => 'crap'));

        // There should be only one element
        $this->assertCount(1, Notification::instance()->notifications());
    }

    /**
     * Test for adding errors
     */
    public function test_add_error() {

        $this->assertCount(0, Notification::instance()->errors());

        $validation = Validation::factory(array('foo' => 'sdsd@foo.com'))
                ->rule('foo', 'not_empty')
                ->rule('foo', 'email')
                ->rule('foo', 'equals', array(':value', 'sds'));

        $this->assertFalse($validation->check());

        Notification::instance()->errors($validation);

        $this->assertCount(1, Notification::instance()->errors);

        $this->assertArrayHasKey('foo', Notification::instance()->errors);
    }

}
