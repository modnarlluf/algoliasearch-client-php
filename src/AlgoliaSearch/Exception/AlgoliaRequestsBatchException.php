<?php

namespace AlgoliaSearch\Exception;

use AlgoliaSearch\AlgoliaException;

class AlgoliaRequestsBatchException extends AlgoliaException
{
    /**
     * @var array
     */
    private $requests;

    /**
     * @return array
     */
    public function getRequests()
    {
        return $this->requests;
    }

    /**
     * @param array $requests
     *
     * @return AlgoliaRequestsBatchException
     */
    public function setRequests($requests)
    {
        $this->requests = $requests;

        return $this;
    }
}
