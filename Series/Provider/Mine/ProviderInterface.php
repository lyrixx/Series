<?php

namespace Series\Provider\Mine;

use Series\Show\Mine\ShowCollection;

interface ProviderInterface
{
    public function fetch();

    public function getShowCollection();
    public function setShowCollection(ShowCollection $showCollection);
    public function getShowClass();
    public function setShowClass($showClass);
}
