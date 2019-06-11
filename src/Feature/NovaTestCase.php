<?php

namespace RomegaDigital\TestSuite\Feature;

use Tests\TestCase;
use RomegaDigital\TestSuite\SetupRomegaTestSuite;
use Illuminate\Foundation\Testing\RefreshDatabase;

abstract class NovaTestCase extends TestCase
{
    use SetupRomegaTestSuite, RefreshDatabase;

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

    public function setUpRelationshipTestCase($resourceName, $resource, $relatedName, $related, $relationshipType)
    {
        $resource->$relatedName()->save($related);

        $response = $this->get("/nova-api/$relatedName?viaResource=$resourceName&viaResourceId=$resource->id&viaRelationship=$relatedName&relationshipType=$relationshipType");

        $response->assertJson([
            'resources' => [
                [
                    'id' => [
                        'value' => $related->id,
                    ],
                ],
            ],
        ]);

        return $response;
    }
}
