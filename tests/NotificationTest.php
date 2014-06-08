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
class NotificationTest extends Unittest_TestCase {

    /**
     * Tests adding and consuming notifications.
     */
    public function testNotifications() {

        $this->assertCount(0, Notification::instance()->notifications);

        Notification::instance()->add(Notification::DANGER, 'crap :toto', array(':toto' => 'crap'));

        // There should be only one element
        $this->assertCount(1, Notification::instance()->notifications);

        Notification::instance()->notifications(); // consume notifications

        $this->assertCount(0, Notification::instance()->notifications);
    }

    /**
     * Test adding and consuming errors.
     */
    public function testAddError() {

        $this->assertCount(0, Notification::instance()->errors());

        $validation = Validation::factory(array('foo' => 'sdsd@foo.com'))
                ->rule('foo', 'not_empty')
                ->rule('foo', 'email')
                ->rule('foo', 'equals', array(':value', 'sds'));

        $this->assertFalse($validation->check());

        Notification::instance()->errors($validation, 'validation');

        $this->assertCount(1, Notification::instance()->errors);

        $this->assertArrayHasKey('foo', Notification::instance()->errors);

        Notification::instance()->errors(); // consume errors

        $this->assertCount(0, Notification::instance()->errors);
    }

}
