<?php

namespace Series\Show\Status;

use Series\Show\Mine\ShowInterface;

class StatusRegistry implements StatusInterface
{

    private $status;

    public function __construct(array $status = array())
    {
        $this->status = $status;
    }

    public function addStatus(StatusInterface $status)
    {
        $this->status[] = $status;
    }

    public function isAlreadyDownloaded(ShowInterface $show)
    {
        foreach ($this->status as $status) {
            if ($status->isAlreadyDownloaded($show)) {
                return true;
            }
        }

        return false;
    }

    public function setMarkAsDownloaded(ShowInterface $show)
    {
        foreach ($this->status as $status) {
            $status->setMarkAsDownloaded($show);
        }
    }

}
