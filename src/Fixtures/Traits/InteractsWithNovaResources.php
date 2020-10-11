<?php

namespace RomegaDigital\TestSuite\Fixtures\Traits;

trait InteractsWithNovaResources
{
    use MakesNovaHttpRequests;

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

    /**
     * Expected status code.
     *
     * @var int
     */
    private $expectedStatusCode;

    /**
     * Get a resource via get request.
     *
     * @param array                               $data
     * @param \Illuminate\Database\Eloquent\Model $user
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function getResource($key = '', $user = null)
    {
        $expectedCode = $this->resetExpectedCode(200);

        $response = $this->actingAs($user ?? $this->user)
            ->novaGet($this->resourceClass::uriKey(), $key);

        $this->dumpErrors($response, $expectedCode);

        return $response->assertStatus($expectedCode ?? 200);
    }

    /**
     * Store a resource via post request.
     *
     * @param array                               $data
     * @param \Illuminate\Database\Eloquent\Model $user
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function storeResource($data = [], $user = null)
    {
        $resource = $this->mergeData($data);
        $expectedCode = $this->resetExpectedCode(201);

        $response = $this->actingAs($user ?? $this->user, 'api')
            ->novaStore($this->resourceClass::uriKey(), $resource->toArray());

        $this->dumpErrors($response, $expectedCode, $resource);

        return $response->assertStatus($expectedCode ?? 201);
    }

    /**
     * Update a resource via post request.
     *
     * @param array                               $data
     * @param \Illuminate\Database\Eloquent\Model $user
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function updateResource($data = [], $user = null)
    {
        $resource = $this->mergeData($data);
        $expectedCode = $this->resetExpectedCode(200);

        $response = $this->actingAs($user ?? $this->user, 'api')
            ->novaUpdate($this->resourceClass::uriKey() . '/' . $resource['id'], $resource->toArray());

        $this->dumpErrors($response, $expectedCode, $resource);

        return $response->assertStatus($expectedCode ?? 200);
    }

    /**
     * Delete a resource via delete request.
     *
     * @param array                               $data
     * @param \Illuminate\Database\Eloquent\Model $user
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function deleteResource($data = [], $user = null)
    {
        return $this->actingAs($user ?? $this->user)
            ->novaDelete($this->resourceClass::uriKey(), $data);
    }

    /**
     * Remap field to what Nova is expecting.
     *
     * @param \Illuminate\Database\Eloquent\Model $resource
     * @param array                               $data
     *
     * @return array
     */
    protected function remapResource($resource, $data = [])
    {
        return [];
    }

    /**
     * Change the expected status code for the next request.
     *
     * @param int $code
     *
     * @return self
     */
    protected function expectStatusCode($code)
    {
        $this->expectedStatusCode = $code;

        return $this;
    }

    /**
     * Resets expectation code.
     *
     * @param int $standard
     *
     * @return int
     */
    protected function resetExpectedCode($standard)
    {
        return tap($this->expectedStatusCode, function () use ($standard) {
            $this->expectedStatusCode = $standard;
        });
    }

    /**
     * Dumps errors to easier debug responses from nova.
     *
     * @param $response
     * @param $resource
     */
    protected function dumpErrors($response, $expectedCode, $resource = null)
    {
        if (app('session.store')->has('errors') && ! in_array($expectedCode, [302])) {
            dump(app('session.store')->get('errors')->getBag('default'));
        }

        if (! $response->isSuccessful() && ($expectedCode ?? 200) !== $response->status()) {
            dump($resource);
            dump($response->json());
        }
    }

    /**
     * Assert json actions.
     *
     * @param string $resourceKey
     * @param array  $actions
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function assertHasActions($resourceKey, $actions)
    {
        return $this->novaRequest('get', $resourceKey . '/actions')
            ->assertJson([
                'actions' => $this->mapIndexToName($actions),
            ]);
    }

    /**
     * Assert json filters.
     *
     * @param string $resourceKey
     * @param array  $filters
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function assertHasFilters($resourceKey, $filters)
    {
        return $this->novaRequest('get', $resourceKey . '/filters')
            ->assertJson(
                $this->mapIndexToName($filters)
            );
    }

    /**
     * Assert json lenses.
     *
     * @param string $resourceKey
     * @param array  $lenses
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function assertHasLenses($resourceKey, $lenses)
    {
        return $this->novaRequest('get', $resourceKey . '/lenses')
            ->assertJson(
                $this->mapIndexToName($lenses)
            );
    }

    /**
     * Maps array list to name.
     *
     * @param array $list
     *
     * @return array
     */
    private function mapIndexToName($list)
    {
        return collect($list)->mapWithKeys(function ($item, $index) {
            return [$index => ['name' => $item]];
        })->toArray();
    }

    /**
     * Merge resource data.
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    private function mergeData($data = [])
    {
        $resource = $this->modelClass->factory()->make();

        return collect($resource)
            ->merge($this->remapResource($resource, $data))
            ->merge($data);
    }
}
