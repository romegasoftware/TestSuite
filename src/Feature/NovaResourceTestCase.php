<?php

namespace RomegaDigital\TestSuite\Feature;

use RomegaDigital\TestSuite\Fixtures\Traits\InteractsWithNovaResources;

class NovaResourceTestCase extends NovaTestCase
{
    use InteractsWithNovaResources;

    /**
     * Model Class of the resource.
     *
     * @var string
     */
    protected $modelClass = '';

    /**
     * Resource class.
     *
     * @var string
     */
    protected $resourceClass = '';

    public function setUp(): void
    {
        parent::setUp();

        if ('' === $this->modelClass || '' === $this->resourceClass) {
            throw new \Exception('No model or resourceClass defined!');
        }
    }
}
