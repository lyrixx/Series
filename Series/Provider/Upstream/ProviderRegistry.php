<?php

namespace Series\Provider\Upstream;

use Series\Show\Upstream\ShowCollection;

class ProviderRegistry implements ProviderInterface
{
    private $providers;
    private $showCollection;

    public function __construct(array $providers = array(), ShowCollection $showCollection = null)
    {
        $this->providers = array();

        foreach ($providers as $provider) {
            $this->addProvider($provider);
        }

        $this->showCollection = $showCollection ?: new ShowCollection();
    }

    public function fetch()
    {
        $showCollection = $this->getShowCollection();

        foreach ($this->providers as $provider) {
            $provider->setShowCollection($showCollection);
            $showCollection = $provider->fetch();
        }

        return $showCollection;
    }

    public function getProviders()
    {
        return $this->providers;
    }

    public function setProviders($providers)
    {
        $this->providers = $providers;

        return $this;
    }

    public function addProvider(ProviderInterface $provider)
    {
        $this->providers[] = $provider;
    }

    public function getShowCollection()
    {
        return $this->showCollection;
    }

    public function setShowCollection(ShowCollection $newShowCollection)
    {
        $this->showCollection = $newShowCollection;

        return $this;
    }

    public function getShowClass()
    {
        throw new \RuntimeException('You can not call getShowClass on a ProviderRegistry');
    }

    public function setShowClass($newShowClass)
    {
        throw new \RuntimeException('You can not call setShowClass on a ProviderRegistry');
    }

}
