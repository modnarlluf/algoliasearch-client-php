<?php

namespace AlgoliaSearch\Exception;

use AlgoliaSearch\AlgoliaException;

class AlgoliaRecordException extends AlgoliaException
{
    /**
     * @var array
     */
    private $record;

    /**
     * @return array
     */
    public function getRecord()
    {
        return $this->record;
    }

    /**
     * @param array $record
     * @return AlgoliaRecordException
     */
    public function setRecord($record)
    {
        $this->record = $record;

        return $this;
    }
}
