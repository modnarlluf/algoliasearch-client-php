<?php

namespace AlgoliaSearch\Tests;

use AlgoliaSearch\AlgoliaException;
use AlgoliaSearch\Client;
use AlgoliaSearch\Exception\AlgoliaIndexNotFoundException;
use AlgoliaSearch\Exception\AlgoliaRecordTooBigException;
use AlgoliaSearch\Index;

class AlgoliaExceptionsTest extends AlgoliaSearchTestCase
{
    /** @var Client */
    private $client;

    /** @var Index */
    private $index;

    protected function setUp()
    {
        $this->client = new Client(getenv('ALGOLIA_APPLICATION_ID'), getenv('ALGOLIA_API_KEY'), null, array('cainfo' => (__DIR__.'/../../../resources/ca-bundle.crt')));
        $this->client->setConnectTimeout(1);
        $this->index = $this->client->initIndex($this->safe_name('àlgol?à-php'));
        $this->tearDown();
    }

    protected function tearDown()
    {
        try {
            $this->client->deleteIndex($this->safe_name('àlgol?à-php'));
        } catch (AlgoliaException $e) {
            // not fatal
        }
    }

    public function testRecordTooBig()
    {
        $this->setExpectedException('AlgoliaSearch\Exception\AlgoliaRecordTooBigException');

        $contacts = file_get_contents(__DIR__.'/../../../contacts.json');
        $object = array('contacts' => $contacts);

        try {
            $this->index->addObject($object);
        } catch (AlgoliaRecordTooBigException $e) {
            $this->assertEquals($object, $e->getRecord());

            throw $e;
        }
    }

    public function testIndexNotFoundWithoutName()
    {
        $this->setExpectedException('AlgoliaSearch\Exception\AlgoliaIndexNotFoundException');

        try {
            $this->index->getSettings();
        } catch (AlgoliaIndexNotFoundException $e) {
            $this->assertNull($e->getIndexName());

            throw $e;
        }
    }

    public function testIndexNotFoundWithName()
    {
        $this->setExpectedException('AlgoliaSearch\Exception\AlgoliaIndexNotFoundException');

        try {
            $this->index->search('foo');
        } catch (AlgoliaIndexNotFoundException $e) {
            $this->assertEquals($this->index->indexName, $e->getIndexName());

            throw $e;
        }
    }

    public function testDisjunctiveFacetsInvalidException()
    {
        $this->setExpectedException('AlgoliaSearch\Exception\AlgoliaDisjunctiveFacetsInvalidException');

        $this->index->searchDisjunctiveFaceting('whatever', 1);
    }

    public function testRefinementsInvalidException()
    {
        $this->setExpectedException('AlgoliaSearch\Exception\AlgoliaRefinementsInvalidException');

        $this->index->searchDisjunctiveFaceting('whatever', 'bis', array(), false);
    }
}
