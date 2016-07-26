<?php

namespace AlgoliaSearch\Exception;

use AlgoliaSearch\AlgoliaException;

class AlgoliaBatchException extends AlgoliaException
{
    private $exceptions = array();

    /**
     * @return array
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }

    /**
     * @param array $exceptions
     *
     * @return AlgoliaBatchException
     */
    public function setExceptions($exceptions)
    {
        $this->exceptions = $exceptions;

        return $this;
    }

    /**
     * @param AlgoliaException $exception
     *
     * @return AlgoliaBatchException
     */
    public function addException(AlgoliaException $exception)
    {
        $this->exceptions[] = $exception;

        return $this;
    }
}
