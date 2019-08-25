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
        $expectedCode = $this->expectedStatusCode;
        $this->expectStatusCode(200);

        return $this->actingAs($user ?? $this->user)
            ->novaGet($this->resourceClass::uriKey(), $key);
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

        $expectedCode = $this->expectedStatusCode;
        $this->expectStatusCode(201);

        return $this->actingAs($user ?? $this->user)
            ->novaStore($this->resourceClass::uriKey(), $resource->toArray());
    }

    /**
     * Update a resource via put request.
     *
     * @param array                               $data
     * @param \Illuminate\Database\Eloquent\Model $user
     *
     * @return \Illuminate\Foundation\Testing\TestResponse
     */
    protected function updateResource($data = [], $user = null)
    {
        $resource = $this->mergeData($data);

        $expectedCode = $this->expectedStatusCode;
        $this->expectStatusCode(200);

        return $this->actingAs($user ?? $this->user)
            ->novaUpdate($this->resourceClass::uriKey() . '/' . $resource['id'], $resource->toArray());
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
        $resource = factory($this->modelClass)->make();

        return collect($resource)
            ->merge($this->remapResource($resource, $data))
            ->merge($data);
    }
}
