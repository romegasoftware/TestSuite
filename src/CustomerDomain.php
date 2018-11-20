<?php

namespace Romega\TestSuite;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;

trait CustomerDomain
{
    use ActingAsUser;

    protected $customer = [];

    public function customerDomain()
    {
        $this->customer = factory(\App\Customer::class)->create();
        $this->customer->users()->save($this->actingAsUser());

        $url = Request::getScheme().'://'.$this->customer->domain.'.'.Request::getHost();
        Config::set('app.url', $url);
    }
}
