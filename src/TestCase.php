<?php

namespace RomegaDigital\TestSuite;

use RomegaDigital\TestSuite\SetupRomegaTestSuite;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, SetupRomegaTestSuite;

    /**
     * Boot the testing helper traits.
     *
     * @return array
     */
    protected function setUpTraits()
    {
        $uses = parent::setUpTraits();

        $this->setupRomegaTestSuite($uses);

        return $uses;
    }
}
