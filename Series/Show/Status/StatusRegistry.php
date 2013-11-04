<?php

namespace Series\Show\Status;

use Series\Show\Mine\ShowInterface;

class StatusRegistry implements StatusInterface
{

    private $statuses;

    public function __construct(array $statuses = array())
    {
        $this->statuses = $statuses;
    }

    public function addStatus(StatusInterface $status)
    {
        $this->statuses[] = $status;
    }

    public function setStatuses(array $statuses = array())
    {
        $this->statuses = $statuses;
    }

    public function isDownloaded(ShowInterface $show)
    {
        foreach ($this->statuses as $status) {
            if ($status->isDownloaded($show)) {
                return true;
            }
        }

        return false;
    }

    public function markAsDownloaded(ShowInterface $show)
    {
        foreach ($this->statuses as $status) {
            $status->markAsDownloaded($show);
        }
    }

}
