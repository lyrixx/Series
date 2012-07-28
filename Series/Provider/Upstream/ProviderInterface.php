<?php

namespace Series\Provider\Upstream;

use Series\Show\Upstream\ShowCollection;

interface ProviderInterface
{
    public function fetch();

    public function getShowCollection();
    public function setShowCollection(ShowCollection $showCollection);
    public function getShowClass();
    public function setShowClass($showClass);
}
