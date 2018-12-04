<?php

namespace Dakalab\CardParser\Tests;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;

/**
 * Initialize unit test case
 */
abstract class TestCase extends PHPUnitTestCase
{
    protected $token;

    protected function setUp()
    {
        error_reporting(E_ALL);
        mb_internal_encoding('UTF-8');
        ini_set('display_errors', true);
        ini_set('html_errors', false);
    }
}
