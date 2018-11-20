<?php

namespace Romega\TestSuite;

trait NovaResourceTestTrait
{
    protected $variables = [];
    protected $novaResources = [
        'viaResource',
        'viaResourceId',
        'viaRelationship',
        'relationshipType',
    ];
    protected $resource;

    public function resource($resource)
    {
        $this->resource = $resource;

        return $this;
    }

    public function __call($name, $arguments)
    {
        if (collect($this->novaResources)->contains($name)) {
            $this->variables[$name] = $arguments[0];

            return $this;
        }
    }

    public function nova($method)
    {
        return $this->json($method, 'nova-api/'.$this->resource, $this->variables);
    }

    public function novaPost($variables = [])
    {
        $this->variables = array_merge($this->variables, $variables);

        return $this->post('nova-api/'.$this->resource, $this->variables);
    }
}
