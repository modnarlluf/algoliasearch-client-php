<!--NO_HTML-->

# Algolia Search API Client for PHP

<!--/NO_HTML-->





<!--NO_HTML-->

[Algolia Search](https://www.algolia.com) is a hosted full-text, numerical, and faceted search engine capable of delivering realtime results from the first keystroke.

<!--/NO_HTML-->

Our PHP client lets you easily use the [Algolia Search API](https://www.algolia.com/doc/rest) from your backend. It wraps the [Algolia Search REST API](https://www.algolia.com/doc/rest).



[![Build Status](https://travis-ci.org/algolia/algoliasearch-client-php.svg?branch=master)](https://travis-ci.org/algolia/algoliasearch-client-php) [![Latest Stable Version](https://poser.pugx.org/algolia/algoliasearch-client-php/v/stable.svg)](https://packagist.org/packages/algolia/algoliasearch-client-php) [![Coverage Status](https://coveralls.io/repos/algolia/algoliasearch-client-php/badge.svg)](https://coveralls.io/r/algolia/algoliasearch-client-php)





<!--NO_HTML-->

Table of Contents
-----------------
**Getting Started**

1. [Setup](#setup)
1. [Quick Start](#quick-start)
1. [Guides & Tutorials](#guides-tutorials)


**Commands Reference**

1. [Add a new object](#add-a-new-object-to-the-index)
1. [Update an object](#update-an-existing-object-in-the-index)
1. [Search](#search)
1. [Multiple queries](#multiple-queries)
1. [Get an object](#get-an-object)
1. [Delete an object](#delete-an-object)
1. [Delete by query](#delete-by-query)
1. [Index settings](#index-settings)
1. [List indices](#list-indices)
1. [Delete an index](#delete-an-index)
1. [Clear an index](#clear-an-index)
1. [Wait indexing](#wait-indexing)
1. [Batch writes](#batch-writes)
1. [Copy / Move an index](#copy--move-an-index)
1. [Backup / Export an index](#backup--export-an-index)
1. [API Keys](#api-keys)
1. [Logs](#logs)


<!--/NO_HTML-->



<!--NO_HTML-->

Guides & Tutorials
================
Check our [online guides](https://www.algolia.com/doc):
 * [Data Formatting](https://www.algolia.com/doc/indexing/formatting-your-data)
 * [Import and Synchronize data](https://www.algolia.com/doc/indexing/import-synchronize-data/php)
 * [Autocomplete](https://www.algolia.com/doc/search/auto-complete)
 * [Instant search page](https://www.algolia.com/doc/search/instant-search)
 * [Filtering and Faceting](https://www.algolia.com/doc/search/filtering-faceting)
 * [Sorting](https://www.algolia.com/doc/relevance/sorting)
 * [Ranking Formula](https://www.algolia.com/doc/relevance/ranking)
 * [Typo-Tolerance](https://www.algolia.com/doc/relevance/typo-tolerance)
 * [Geo-Search](https://www.algolia.com/doc/geo-search/geo-search-overview)
 * [Security](https://www.algolia.com/doc/security/best-security-practices)
 * [API-Keys](https://www.algolia.com/doc/security/api-keys)
 * [REST API](https://www.algolia.com/doc/rest)


<!--/NO_HTML-->








## Getting Started



### Install and init `initIndex`

To setup your project, follow these steps:




1. Install the package via [Composer](https://getcomposer.org/doc/00-intro.md):
```bash
composer require algolia/algoliasearch-client-php
```
If you don't use Composer, you can copy the `algoliasearch.php` file and the `src` and `resources` directories to your project).

2. Next you'll have to initialize the client with your ApplicationID and API-Key. You can find all of them on [your Algolia account](http://www.algolia.com/users/edit).


```php
// composer autoload
require __DIR__ . '/vendor/autoload.php';
// if you are not using composer: require_once 'path/to/algoliasearch.php';

$client = new \AlgoliaSearch\Client('YourApplicationID', 'YourAPIKey');
```

#### Framework Integrations

If you're a Symfony or Laravel user; you're probably looking for the following integrations:

 - **Laravel**: [algolia/algoliasearch-laravel](https://github.com/algolia/algoliasearch-laravel)
 - **Symfony**: [algolia/AlgoliaSearchBundle](https://github.com/algolia/AlgoliaSearchBundle)




### Quick Start


In 30 seconds, this quick start tutorial will show you how to index and search objects.

Without any prior configuration, you can start indexing [500 contacts](https://github.com/algolia/algoliasearch-client-csharp/blob/master/contacts.json) in the ```contacts``` index using the following code:
```php
$index = $client->initIndex('contacts');
$batch = json_decode(file_get_contents('contacts.json'), true);
$index->addObjects($batch);
```

You can now search for contacts using firstname, lastname, company, etc. (even with typos):
```php
// search by firstname
var_dump($index->search('jimmie'));

// search a firstname with typo
var_dump($index->search('jimie'));

// search for a company
var_dump($index->search('california paint'));

// search for a firstname & company
var_dump($index->search('jimmie paint'));
```

Settings can be customized to tune the search behavior. For example, you can add a custom sort by number of followers to the already great built-in relevance:
```php
$index->setSettings(['customRanking' => ['desc(followers)']]);
```

You can also configure the list of attributes you want to index by order of importance (first = most important):
```php
$index->setSettings(
    [
        'attributesToIndex' => [
            'lastname',
            'firstname',
            'company',
            'email',
            'city',
            'address'
        ]
    ]
);
```

Since the engine is designed to suggest results as you type, you'll generally search by prefix. In this case the order of attributes is very important to decide which hit is the best:
```php
var_dump($index->search('or'));
var_dump($index->search('jim'));
```


**Note:** If you are building a web application, you may be more interested in using our [JavaScript client](https://github.com/algolia/algoliasearch-client-js) to perform queries. It brings two benefits:
  * Your users get a better response time by not going through your servers
  * It will offload unnecessary tasks from your servers

```html
<script src="//cdn.jsdelivr.net/algoliasearch/3/algoliasearch.min.js"></script>
<script>
var client = algoliasearch('ApplicationID', 'apiKey');
var index = client.initIndex('indexName');

// perform query "jim"
index.search('jim', searchCallback);

// the last optional argument can be used to add search parameters
index.search(
  'jim', {
    hitsPerPage: 5,
    facets: '*',
    maxValuesPerFacet: 10
  },
  searchCallback
);

function searchCallback(err, content) {
  if (err) {
    console.error(err);
    return;
  }

  console.log(content);
}
</script>
```






## Querying

### Search in an index - `search`


**Notes:** If you are building a web application, you may be more interested in using our [JavaScript client](https://github.com/algolia/algoliasearch-client-js) to perform queries. It brings two benefits:
  * Your users get a better response time by not going through your servers
  * It will offload unnecessary tasks from your servers.


To perform a search, you only need to initialize the index and perform a call to the search function.

The search query allows only to retrieve 1000 hits, if you need to retrieve more than 1000 hits for seo, you can use [Backup / Retrieve all index content](#backup--export-an-index)

```php
$index = $client->initIndex('contacts');
$res = $index->search('query string');
$res = $index->search('query string', ['attributesToRetrieve' => 'fistname,lastname', 'hitsPerPage' => 50]);
```

The server response will look like:

```json
{
  "hits": [
    {
      "firstname": "Jimmie",
      "lastname": "Barninger",
      "objectID": "433",
      "_highlightResult": {
        "firstname": {
          "value": "<em>Jimmie</em>",
          "matchLevel": "partial"
        },
        "lastname": {
          "value": "Barninger",
          "matchLevel": "none"
        },
        "company": {
          "value": "California <em>Paint</em> & Wlpaper Str",
          "matchLevel": "partial"
        }
      }
    }
  ],
  "page": 0,
  "nbHits": 1,
  "nbPages": 1,
  "hitsPerPage": 20,
  "processingTimeMS": 1,
  "query": "jimmie paint",
  "params": "query=jimmie+paint&attributesToRetrieve=firstname,lastname&hitsPerPage=50"
}
```


### Multiple queries - `multipleQueries`

You can send multiple queries with a single API call using a batch of queries:

```php
// perform 3 queries in a single API call:
//  - 1st query targets index `categories`
//  - 2nd and 3rd queries target index `products`
$queries = [
    ['indexName' => 'categories', 'query' => $myQueryString, 'hitsPerPage' => 3],
    ['indexName' => 'products', 'query' => $myQueryString, 'hitsPerPage' => 3, 'facetFilters' => 'promotion'],
    ['indexName' => 'products', 'query' => $myQueryString, 'hitsPerPage' => 10]
];

$results = $client->multipleQueries($queries);

var_dump(results['results']):
```

The resulting JSON answer contains a ```results``` array storing the underlying queries answers. The answers order is the same than the requests order.

You can specify a `strategy` parameter to optimize your multiple queries:
- `none`: Execute the sequence of queries until the end.
- `stopIfEnoughMatches`: Execute the sequence of queries until the number of hits is reached by the sum of hits.







### Find by ids - `getObject(s)`

You can easily retrieve an object using its `objectID` and optionally specify a comma separated list of attributes you want:

```php
// Retrieves all attributes
$index->getObject('myID');

// Retrieves firstname and lastname attributes
$index->getObject('myID', 'firstname,lastname');

// Retrieves only the firstname attribute
$index->getObject('myID', 'firstname');
```

You can also retrieve a set of objects:

```php
$index->getObjects(['myID1', 'myID2']);
```









## Indexing

### Add objects - `addObject(s)`

Each entry in an index has a unique identifier called `objectID`. There are two ways to add an entry to the index:

 1. Using automatic `objectID` assignment. You will be able to access it in the answer.
 2. Supplying your own `objectID`.

You don't need to explicitly create an index, it will be automatically created the first time you add an object.
Objects are schema less so you don't need any configuration to start indexing. If you wish to configure things, the settings section provides details about advanced settings.

Example with automatic `objectID` assignment:

```php
$res = $index->addObject([
	'firstname' => 'Jimmie',
	'lastname' => 'Barninger'
]);
echo 'objectID=' . $res['objectID'] . "\n";
```

Example with manual `objectID` assignment:

```php
$res = $index->addObject(
    [
        'firstname' => 'Jimmie',
        'lastname'  => 'Barninger'
    ],
    'myID'
);
echo 'objectID=' . $res['objectID'] . "\n";
```


You may want to perform multiple operations with one API call to reduce latency.
Example using automatic `objectID` assignment:
```php
$res = $index->addObjects(
    [
        [
            'firstname' => 'Jimmie',
            'lastname'  => 'Barninger'
        ],
        [
            'firstname' => 'Warren',
            'lastname'  => 'myID1'
        ]
    ]
);
```


### Update objects - `saveObject(s)`

You have three options when updating an existing object:

 1. Replace all its attributes.
 2. Replace only some attributes.
 3. Apply an operation to some attributes.

Example on how to replace all attributes of an existing object:

```php
$index->saveObject(
    [
        'firstname' => 'Jimmie',
        'lastname'  => 'Barninger',
        'city'      => 'New York',
        'objectID'  => 'myID'
    ]
);
```

You may want to perform multiple operations with one API call to reduce latency.
Example with user defined `objectID` (add or update):
```php
$res = $index->saveObjects(
    [
        [
            'firstname' => 'Jimmie',
            'lastname'  => 'Barninger',
            'objectID'  => 'SFO'
        ],
        [
            'firstname' => 'Warren',
            'lastname'  => 'Speach',
            'objectID'  => 'myID2'
        ]
    ]
);
```


### Partial update objects - `partialUpdateObject(s)`

You have many ways to update an object's attributes:

 1. Set the attribute value
 2. Add a string or number element to an array
 3. Remove an element from an array
 4. Add a string or number element to an array if it doesn't exist
 5. Increment an attribute
 6. Decrement an attribute

Example to update only the city attribute of an existing object:

```php
$index->partialUpdateObject(
    [
        'city'     => 'San Francisco',
        'objectID' => 'myID'
    ]
);
```

Example to add a tag:

```php
$index->partialUpdateObject(
    [
        '_tags'    => ['value' => 'MyTag', '_operation' => 'Add'],
        'objectID' => 'myID'
    ]
);
```

Example to remove a tag:

```php
$index->partialUpdateObject(
    [
        '_tags'    => ['value' => 'MyTag', '_operation' => 'Remove'],
        'objectID' => 'myID'
    ]
);
```

Example to add a tag if it doesn't exist:

```php
$index->partialUpdateObject(
    [
        '_tags'    => ['value' => 'MyTag', '_operation' => 'AddUnique'],
        'objectID' => 'myID'
    ]
);
```

Example to increment a numeric value:

```php
$index->partialUpdateObject(
    [
        'price'    => ['value' => 42, '_operation' => 'Increment'],
        'objectID' => 'myID'
    ]
);
```

Note: Here we are incrementing the value by `42`. To increment just by one, put
`value:1`.

Example to decrement a numeric value:

```php
$index->partialUpdateObject(
    [
        'price'    => ['value' => 42, '_operation' => 'Decrement'],
        'objectID' => 'myID'
    ]
);
```

Note: Here we are decrementing the value by `42`. To decrement just by one, put
`value:1`.

You may want to perform multiple operations with one API call to reduce latency.
Example that updates only the `firstname` attribute:
```php
$res = $index->partialUpdateObjects(
    [
        [
            'firstname' => 'Jimmie',
            'objectID'  => 'SFO'
        ],
        [
            'firstname' => 'Warren',
            'objectID'  => 'myID2'
        ]
    ]
);
```


### Delete objects - `deleteObject(s)`

You can delete an object using its `objectID`:

```php
$index->deleteObject('myID');
```

You may want to perform multiple operations with one API call to reduce latency.
Example that deletes a set of records:
```php
$res = $index->deleteObjects(["myID1", "myID2"]);
```

### Delete by query - `deleteByQuery`

You can delete all objects matching a single query with the following code. Internally, the API client performs the query, deletes all matching hits, and waits until the deletions have been applied.


```php
$params = [];
$index->deleteByQuery('John', $params);
```





### Wait for an indexing operation - `waitTask`

All write operations in Algolia are asynchronous by design.

It means that when you add or update an object to your index, our servers will
reply to your request with a `taskID` as soon as they understood the write
operation.

The actual insert and indexing will be done after replying to your code.

You can wait for a task to complete using the `waitTask` method on the `taskID` returned by a write operation.

For example, to wait for indexing of a new object:
```php
$res = $index->addObject(
    [
        'firstname' => 'Jimmie',
        'lastname'  => 'Barninger'
    ]
);
$index->waitTask($res['taskID']);
```

If you want to ensure multiple objects have been indexed, you only need to check
the biggest `taskID`.

### Custom batch - `batch`

If you have one index per user, you may want to perform a batch operations across severals indexes.
We expose a method to perform this type of batch:
```php
$res = $index->batch(
    [
        [
            'action'    => 'addObject',
            'indexName' => 'index1',
            [
                'firstname' => 'Jimmie',
                'lastname'  => 'Barninger'
            ]
        ],
        [
            'action'    => 'addObject',
            'indexName' => 'index1',
            [
                'firstname' => 'Warren',
                'lastname'  => 'myID1'
            ]
        ]
    ]
);
```

The attribute **action** can have these values:
- addObject
- updateObject
- partialUpdateObject
- partialUpdateObjectNoCreate
- deleteObject

## Settings

### Get settings - `getSettings`

You can retrieve settings:

```php
$settings = $index->getSettings();
var_dump($settings);
```

### Set settings - `setSettings`

```php
$index->setSettings(array("customRanking" => array("desc(followers)")));
```

## Parameters

### Query

#### query

- scope: `search`
- type: `string`
- default: `""`

The instant search query string, used to set the string you want to search in your index. If no query parameter is set, the textual search will match with all the objects.

### Query Strategy

#### queryType

- scope: `indexing`, `search`
- default: `prefixLast`

Selects how the query words are interpreted. It can be one of the following values:
* `prefixAll`:
All query words are interpreted as prefixes. This option is not recommended.
* `prefixLast`:
Only the last word is interpreted as a prefix (default behavior).
* `prefixNone`:
No query word is interpreted as a prefix. This option is not recommended.

#### removeWordsIfNoResults

- scope: `indexing`, `search`
- type: `string`
- default: `none`

This option is used to select a strategy in order to avoid having an empty result page.
There are four different options:
- `lastWords`:
When a query does not return any results, the last word will be added as optional.
The process is repeated with n-1 word, n-2 word, ... until there are results.
- `firstWords`:
When a query does not return any results, the first word will be added as optional.
The process is repeated with second word, third word, ... until there are results.
- `allOptional`:
When a query does not return any results, a second trial will be made with all words as optional.
This is equivalent to transforming the AND operand between query terms to an OR operand.
- `none`:
No specific processing is done when a query does not return any results (default behavior).


#### advancedSyntax

- scope: `indexing`, `search`
- default: `0 (false)`

Enables the advanced query syntax.

This syntax allow to do two things:
* **Phrase query**: A phrase query defines a particular sequence of terms. A phrase query is built by Algolia's query parser for words surrounded by `"`. For example, `"search engine"` will retrieve records having `search` next to `engine` only. Typo tolerance is _disabled_ on phrase queries.
* **Prohibit operator**: The prohibit operator excludes records that contain the term after the `-` symbol. For example, `search -engine` will retrieve records containing `search` but not `engine`.


#### optionalWords

- scope: `indexing`, `search`
- default: `[]`

A string that contains the comma separated list of words that should be considered as optional when found in the query.



#### removeStopWords

- scope: `indexing`, `search`
- default: `false`

Remove the stop words from query before executing it.
Defaults to false. Contains a list of stop words from 41 languages (Arabic, Armenian, Basque, Bengali, Brazilian, Bulgarian, Catalan, Chinese, Czech, Danish, Dutch, English, Finnish, French, Galician, German, Greek, Hindi, Hungarian, Indonesian, Irish, Italian, Japanese, Korean, Kurdish, Latvian, Lithuanian, Marathi, Norwegian, Persian, Polish, Portugese, Romanian, Russian, Slovak, Spanish, Swedish, Thai, Turkish, Ukranian, Urdu).
In most use-cases, **we don't recommend enabling this option**.

### Attributes

#### attributesToIndex

- scope: `indexing`
- type: `array of strings`

The list of attributes you want index (i.e. to make searchable).

If set to null, all textual and numerical attributes of your objects are indexed.
Make sure you updated this setting to get optimal results.

This parameter has two important uses:
* **Limit the attributes to index**.
<br/>For example, if you store the URL of a picture, you want to store it and be able to retrieve it,
but you probably don't want to search in the URL.
* **Control part of the ranking**.
<br/> Matches in attributes at the beginning of the list will be considered more important than matches in attributes
further down the list.
In one attribute, matching text at the beginning of the attribute will be considered more important than text after.
You can disable this behavior if you add your attribute inside `unordered(AttributeName)`.
For example, `attributesToIndex: ["title", "unordered(text)"]`.
You can decide to have the same priority for two attributes
by passing them in the same string using a comma as a separator.
For example `title` and `alternative_title` have the same priority in this example,
which is different than text priority: `attributesToIndex:["title,alternative_title", "text"]`.
To get a full description of how the Ranking works, you can have a look at our
[Ranking guide](https://www.algolia.com/doc/relevance/ranking).

#### numericAttributesToIndex

- scope: `indexing`
- type: `array of strings`

All numerical attributes are automatically indexed as numerical filters
(allowing filtering operations like `<` and `<=`).
If you don't need filtering on some of your numerical attributes,
you can specify this list to speed up the indexing.
<br/> If you only need to filter on a numeric value with the operator '=',
you can speed up the indexing by specifying the attribute with `equalOnly(AttributeName)`.
The other operators will be disabled.

#### attributesForFaceting

- scope: `indexing`
- type: `array of strings`

The list of fields you want to use for faceting.
All strings in the attribute selected for faceting are extracted and added as a facet.
If set to null, no attribute is used for faceting.


#### unretrievableAttributes

- scope: `indexing`
- type: `array of strings`
- default: `null`

The list of attributes that cannot be retrieved at query time.
This feature allows you to have attributes that are used for indexing
and/or ranking but cannot be retrieved

**Warning**: for testing purposes, this setting is ignored when you're using the ADMIN API Key.


#### restrictSearchableAttributes

- scope: `search`
- type: `array of strings`
- default: `attributesToIndex`

List of attributes you want to use for textual search (must be a subset of the `attributesToIndex` index setting).
Attributes are separated with a comma such as `"name,address"`.
You can also use JSON string array encoding such as `encodeURIComponent("[\"name\",\"address\"]")`.
By default, all attributes specified in the `attributesToIndex` settings are used to search.

#### attributesToRetrieve

- scope: `indexing`, `search`
- type: `array of strings`

Default list of attributes to retrieve in objects. If set to null, all attributes are retrieved.

### Ranking

#### ranking

- scope: `indexing`
- type: `array of strings`
- default: `['typo', 'geo', 'words', 'filters', 'proximity', 'attribute', 'exact', 'custom‘]`

Controls the way results are sorted.

We have nine available criterion:

* `typo`: Sort according to number of typos.
* `geo`: Sort according to decreasing distance when performing a geo location based search.
* `words`: Sort according to the number of query words matched by decreasing order. This parameter is useful when you use the `optionalWords` query parameter to have results with the most matched words first.
* `proximity`: Sort according to the proximity of the query words in hits.
* `attribute`: Sort according to the order of attributes defined by attributesToIndex.
* `exact`:
  * If the user query contains one word: sort objects having an attribute that is exactly the query word before others. For example, if you search for the TV show "V", you want to find it with the "V" query and avoid getting all popular TV shows starting by the letter V before it.
  * If the user query contains multiple words: sort according to the number of words that matched exactly (not as a prefix).
* `custom`: Sort according to a user defined formula set in the `customRanking` attribute.
* `asc(attributeName)`: Sort according to a numeric attribute using ascending order. `attributeName` can be the name of any numeric attribute in your records (integer, double or boolean).
* `desc(attributeName)`: Sort according to a numeric attribute using descending order. `attributeName` can be the name of any numeric attribute in your records (integer, double or boolean).

<br/>To get a full description of how the Ranking works,
you can have a look at our [Ranking guide](https://www.algolia.com/doc/relevance/ranking).

#### customRanking

- scope: `indexing`
- type: `array of strings`
- default: `[]`

Lets you specify part of the ranking.

The syntax of this condition is an array of strings containing attributes
prefixed by the asc (ascending order) or desc (descending order) operator.

For example, `"customRanking" => ["desc(population)", "asc(name)"]`.

To get a full description of how the Custom Ranking works,
you can have a look at our [Ranking guide](https://www.algolia.com/doc/relevance/ranking).

#### slaves

- scope: `indexing`
- type: `array of strings`
- default: `[]`

The list of indices on which you want to replicate all write operations.

In order to get response times in milliseconds, we pre-compute part of the ranking during indexing.

If you want to use different ranking configurations depending of the use case,
you need to create one index per ranking configuration.

This option enables you to perform write operations only on this index and automatically
update slave indices with the same operations.

### Typos

#### minWordSizefor1Typo

- scope: `indexing`, `search`
- type: `integer`
- default: `4`

The minimum number of characters needed to accept one typo.

#### minWordSizefor2Typos

- scope: `indexing`, `search`
- type: `integer`
- default: `8`

The minimum number of characters needed to accept two typos.


#### typoTolerance

- scope: `indexing`, `search`
- type: `boolean`
- default: `true`

This option allows you to control the number of typos allowed in the result set:

* `true`: The typo tolerance is enabled and all matching hits are retrieved (default behavior).
* `false`: The typo tolerance is disabled. All results with typos will be hidden.
* `min`: Only keep results with the minimum number of typos. For example, if one result matches without typos, then all results with typos will be hidden.
* `strict`: Hits matching with 2 typos are not retrieved if there are some matching without typos.


#### allowTyposOnNumericTokens

- scope: `indexing`, `search`
- type: `boolean`
- default: `true`

If set to false, disables typo tolerance on numeric tokens (numbers).

#### ignorePlural

- scope: `indexing`, `search`
- type: `boolean`
- default: `false`

If set to true, plural won't be considered as a typo. For example, car and cars, or foot and feet will be considered as equivalent. Defaults to false.

#### disableTypoToleranceOnAttributes

- scope: `indexing`, `search`
- type: `string`
- default: ``

List of attributes on which you want to disable typo tolerance
(must be a subset of the `attributesToIndex` index setting).

Attributes are separated with a comma such as `"name,address"`.
You can also use JSON string array encoding such as `encodeURIComponent("[\"name\",\"address\"]")`.

#### altCorrections

- scope: `indexing`
- type: `array of objects`
- defaults: `[]`

Specify alternative corrections that you want to consider.

Each alternative correction is described by an object containing three attributes:
* **word**: The word to correct.
* **correction**: The corrected word.
* **nbTypos** The number of typos (1 or 2) that will be considered for the ranking algorithm (1 typo is better than 2 typos).

For example:

`"altCorrections": [ { "word" : "foot", "correction": "feet", "nbTypos": 1 }, { "word": "feet", "correction": "foot", "nbTypos": 1 } ]`.


#### disablePrefixOnAttributes

- scope: `indexing`
- type: `string array`
- default: `[]`

List of attributes on which you want to disable prefix matching
(must be a subset of the `attributesToIndex` index setting).

This setting is useful on attributes that contain string that should not be matched as a prefix
(for example a product SKU).


#### disableExactOnAttributes

- scope: `indexing`
- type: `string array`
- default: `[]`

List of attributes on which you want to disable the computation of `exact` criteria
(must be a subset of the `attributesToIndex` index setting).

### Synonyms

#### synonyms

- scope: `search`
- type: `boolean`
- default: `true`

If set to false, this query will not use synonyms defined in the configuration.

#### replaceSynonymsInHighlight

- scope: `indexing`, `search`
- type: `boolean`
- default: true

If set to false, words matched via synonym expansion will not be replaced by the matched synonym
in the highlight results.

### Pagination

#### page

- scope: `search`
- type: `integer`
- default: `0`

Pagination parameter used to select the page to retrieve.
<br>
Page is zero based and defaults to 0. Thus, to retrieve the 10th page you need to set `page=9`.

### puts({'C#' => 'SetNbHitsPerPage', 'Java' => 'setHitsPerPage', 'Android' => 'setHitsPerPage'}, "hitsPerPage")

- scope: `indexing`, `search`
- type: `integer`
- default: `20`

Pagination parameter used to select the number of hits per page. Defaults to 20.

### Geo-Search



#### aroundLatLng

- scope: `search`
- type: `string`

Search for entries around a given latitude/longitude (specified as two floats separated by a comma).

For example, `aroundLatLng=47.316669,5.016670`.

- By default the maximum distance is automatically guessed based on the density of the area
but you can specify it manually in meters with the **aroundRadius** parameter.
The precision for ranking can be set with **aroundPrecision** parameter.
- If you set aroundPrecision=100, the distances will be considered by ranges of 100m.
- For example all distances 0 and 100m will be considered as identical for the "geo" ranking parameter.

When **aroundRadius** is not set, the radius is computed automatically using the density of the area,
you can retrieve the computed radius in the **automaticRadius** attribute of the answer,
you can also use the **minimumAroundRadius** query parameter to specify a minimum radius in meters
for the automatic computation of **aroundRadius**.

At indexing, you should specify geoloc of an object with the _geoloc attribute
(in the form `"_geoloc":{"lat":48.853409, "lng":2.348800}`
or `"_geoloc":[{"lat":48.853409, "lng":2.348800},{"lat":48.547456, "lng":2.972075}]`
if you have several geo-locations in your record).




#### aroundLatLngViaIP

- scope: `search`
- type: `boolean`
- default: `false`

Search for entries around a given latitude/longitude automatically computed from user IP address.

To enable it, use `aroundLatLngViaIP=true`.

You can specify the maximum distance in meters with the `aroundRadius` parameter
and the precision for ranking with `aroundPrecision`.

For example:
- if you set aroundPrecision=100,
two objects that are in the range 0-99m
will be considered as identical in the ranking for the "geo" ranking parameter (same for 100-199, 200-299, ... ranges).

When indexing, you should specify the geo location of an object with the `_geoloc` attribute
in the form `{"_geoloc":{"lat":48.853409, "lng":2.348800}}`.



#### insideBoundingBox

- scope: `search`
- type: `boolean`
- default: `false`

Search entries inside a given area defined by the two extreme points of a rectangle
(defined by 4 floats: p1Lat,p1Lng,p2Lat,p2Lng).
For example:
- `insideBoundingBox=47.3165,4.9665,47.3424,5.0201`


At indexing, you should specify geoloc of an object with the _geoloc attribute
(in the form `"_geoloc":{"lat":48.853409, "lng":2.348800}`
or `"_geoloc":[{"lat":48.853409, "lng":2.348800},{"lat":48.547456, "lng":2.972075}]`
if you have several geo-locations in your record).


You can use several bounding boxes (OR) by passing more than 4 values.
For example: instead of having 4 values you can pass 8 to search inside the UNION of two bounding boxes.

#### insidePolygon

Search entries inside a given area defined by a set of points
(defined by a minimum of 6 floats: p1Lat,p1Lng,p2Lat,p2Lng,p3Lat,p3Long).

For example:
`InsidePolygon=47.3165,4.9665,47.3424,5.0201,47.32,4.98`).


At indexing, you should specify geoloc of an object with the _geoloc attribute
(in the form `"_geoloc":{"lat":48.853409, "lng":2.348800}`
or `"_geoloc":[{"lat":48.853409, "lng":2.348800},{"lat":48.547456, "lng":2.972075}]`
if you have several geo-locations in your record).

### Highlighting / Snippeting

#### attributesToHighlight

- scope: `indexing`, `search`
- type: `array of strings`
- default: `null`

Default list of attributes to highlight.
If set to null, all indexed attributes are highlighted.

#### attributesToSnippet

- scope: `indexing`, `search`
- type: `array of strings`
- default: `null`

Default list of attributes to snippet alongside the number of words to return (syntax is `attributeName:nbWords`).
If set to null, no snippet is computed.


#### highlightPreTag

- scope: `indexing`, `search`
- type: `string`
- default: `<em>`

Specify the string that is inserted before the highlighted parts in the query result (defaults to `<em>`).


#### highlightPostTag

- scope: `indexing`, `search`
- type: `string`
- default: `<em>`

Specify the string that is inserted after the highlighted parts in the query result (defaults to `</em>`).

### Filtering / Faceting

#### numericFilters

- scope: `search`
- type: `array of strings`
- default: `[]`

A string that contains the comma separated list of numeric filters you want to apply.
The filter syntax is `attributeName` followed by `operand` followed by `value`.
Supported operands are `<`, `<=`, `=`, `>` and `>=`.

You can easily perform range queries via the `:` operator.
This is equivalent to combining a `>=` and `<=` operand.

For example, `numericFilters=price:10 to 1000`.

You can also mix OR and AND operators.
The OR operator is defined with a parenthesis syntax.

For example, `(code=1 AND (price:[0-100] OR price:[1000-2000]))`
translates to `encodeURIComponent("code=1,(price:0 to 100,price:1000 to 2000)")`.

You can also use a string array encoding (for example `numericFilters: ["price>100","price<1000"]`).

#### tagFilters

- scope: `search`
- type: `string`
- default: `""`

Filter the query by a set of tags.

You can AND tags by separating them with commas.
To OR tags, you must add parentheses.

For example, `tagFilters=tag1,(tag2,tag3)` means *tag1 AND (tag2 OR tag3)*.

You can also use a string array encoding.

For example, `tagFilters: ["tag1",["tag2","tag3"]]` means *tag1 AND (tag2 OR tag3)*.

Negations are supported via the `-` operator, prefixing the value.

For example: `tagFilters=tag1,-tag2`.

At indexing, tags should be added in the **_tags** attribute of objects.

For example `{"_tags":["tag1","tag2"]}`.


#### facetFilters

- scope: `search`
- type: `string`
- default: `""`

Filter the query with a list of facets. Facets are separated by commas and is encoded as `attributeName:value`.
To OR facets, you must add parentheses.

For example: `facetFilters=(category:Book,category:Movie),author:John%20Doe`.

You can also use a string array encoding.

For example, `[["category:Book","category:Movie"],"author:John%20Doe"]`.

#### 

- scope: `search`
- type: `string`
- default: `""`

List of object attributes that you want to use for faceting.

For each of the declared attributes, you'll be able to retrieve a list of the most relevant facet values,
and their associated count for the current query.

Attributes are separated by a comma.

For example, `"category,author"`.

You can also use JSON string array encoding.

For example, `["category","author"]`.

Only the attributes that have been added in **attributesForFaceting** index setting can be used in this parameter.
You can also use `*` to perform faceting on all attributes specified in `attributesForFaceting`.
If the number of results is important, the count can be approximate,
the attribute `exhaustiveFacetsCount` in the response is true when the count is exact.

#### maxValuesPerFacet

- scope: `indexing`, `search`
- type: `integer`
- default: `""`

Limit the number of facet values returned for each facet.

For example, `maxValuesPerFacet=10` will retrieve a maximum of 10 values per facet.

####  -  SQL like filters

Filter the query with numeric, facet or/and tag filters.

The syntax is a SQL like syntax, you can use the OR and AND keywords.
The syntax for the underlying numeric, facet and tag filters is the same than in the other filters:

`available=1 AND (category:Book OR NOT category:Ebook) AND _tags:public`
`date: 1441745506 TO 1441755506 AND inStock > 0 AND author:"John Doe"`

If no attribute name is specified,
the filter applies to `_tags`.

For example: `public OR user_42` will translate to `_tags:public OR _tags:user_42`.

The list of keywords is:
* `OR`: create a disjunctive filter between two filters.
* `AND`: create a conjunctive filter between two filters.
* `TO`: used to specify a range for a numeric filter.
* `NOT`: used to negate a filter. The syntax with the `-` isn’t allowed.

*Note*: To specify a value with spaces or with a value equal to a keyword, it's possible to add quotes.

**Warning:**

* Like for the other filters (for performance reasons), it's not possible to have FILTER1 OR (FILTER2 AND FILTER3).
* It's not possible to mix different categories of filters inside an OR like: num=3 OR tag1 OR facet:value
* It's not possible to negate a group, it's only possible to negate a filter:  NOT(FILTER1 OR (FILTER2) is not allowed.

### Advanced

#### 

- scope: `search`
- type: `boolean`
- default: `false`

If set to true,
the result hits will contain ranking information in the **_rankingInfo** attribute.

#### 

- scope: `indexing`, `search`
- type: `boolean`
- default: `false`

If set to 1,
enables the distinct feature, disabled by default, if the `attributeForDistinct` index setting is set.

This feature is similar to the SQL "distinct" keyword.
When enabled in a query with the `distinct=1` parameter,
all hits containing a duplicate value for the attributeForDistinct attribute are removed from results.

For example, if the chosen attribute is `show_name` and several hits have the same value for `show_name`,
then only the best one is kept and the others are removed.

To get a full understanding of how `Distinct` works,
you can have a look at our [guide on distinct](https://www.algolia.com/doc/search/distinct).

#### attributeForDistinct

- scope: `indexing`
- type: `string`

The name of the attribute used for the `Distinct` feature.

This feature is similar to the SQL "distinct" keyword.
When enabled in queries with the `distinct=1` parameter,
all hits containing a duplicate value for this attribute are removed from the results.

For example, if the chosen attribute is `show_name` and several hits have the same value for `show_name`,
then only the first one is kept and the others are removed from the results.

To get a full understanding of how `Distinct` works,
you can have a look at our [guide on distinct](https://www.algolia.com/doc/search/distinct).

#### 

- scope: `indexing`
- type: `boolean`
- default: `true`

If set to false, this query will not be taken into account in the analytics feature.

#### separatorsToIndex

- scope: `indexing`
- type: `string`
- default: `empty`

Specify the separators (punctuation characters) to index.

By default, separators are not indexed.

Use `+#` to be able to search Google+ or C#.

#### allowCompressionOfIntegerArray

- scope: `indexing`
- type: `boolean`
- default: `false`

Allows compression of big integer arrays.

In data-intensive use-cases,
we recommended enabling this feature and then storing the list of user IDs or rights as an integer array.
When enabled, the integer array is reordered to reach a better compression ratio.


#### placeholders

- scope: `indexing`
- type: `hash of array of words`

This is an advanced use-case to define a token substitutable by a list of words
without having the original token searchable.

It is defined by a hash associating placeholders to lists of substitutable words.

For example, `"placeholders": { "<streetnumber>": ["1", "2", "3", ..., "9999"]}`
would allow it to be able to match all street numbers. We use the `< >` tag syntax
to define placeholders in an attribute.

For example:
* Push a record with the placeholder:
`{ "name" : "Apple Store", "address" : "&lt;streetnumber&gt; Opera street, Paris" }`.
* Configure the placeholder in your index settings:
`"placeholders": { "<streetnumber>" : ["1", "2", "3", "4", "5", ... ], ... }`.

## Manage Indices

### List indices - `listIndexes`

You can list all your indices along with their associated information (number of entries, disk size, etc.)

```php
var_dump($client->listIndexes());
```






### Delete index - `deleteIndex` `deleteIndex`

You can delete an index using its name:

```php
$client->deleteIndex('contacts');
```




### Clear index - `clearIndex`

You can delete the index contents without removing settings and index specific API keys by using the clearIndex command:

```php
$index->clearIndex();
```

### Copy index - `copyIndex`
==================

You can copy using the `copy` command.
**Note**: Move and copy commands overwrite the destination index.

```php
// Copy MyIndex in MyIndexCopy
$res = $client->copyIndex('MyIndex', 'MyIndexCopy');
```


### Move index - `moveIndex`

You can move using the `move` command.

The move command is particularly useful if you want to update a big index atomically from one version to another. For example, if you recreate your index `MyIndex` each night from a database by batch, you only need to:
 1. Import your database into a new index using [batches](#batch-writes). Let's call this new index `MyNewIndex`.
 1. Rename `MyNewIndex` to `MyIndex` using the move command. This will automatically override the old index and new queries will be served on the new one.

```php
// Rename MyNewIndex in MyIndex (and overwrite it)
$res = $client->moveIndex('MyNewIndex', 'MyIndex');
```


### Get Logs - `getLogs`

You can retrieve the latest logs via this API. Each log entry contains:
 * Timestamp in ISO-8601 format
 * Client IP
 * Request Headers (API Key is obfuscated)
 * Request URL
 * Request method
 * Request body
 * Answer HTTP code
 * Answer body
 * SHA1 ID of entry

You can retrieve the logs of your last 1,000 API calls and browse them using the offset/length parameters:

<table><tbody>
  
    <tr>
      <td valign='top'>
        <div class='client-readme-param-container'>
          <div class='client-readme-param-container-inner'>
            <div class='client-readme-param-name'><code>offset</code></div>
            
          </div>
        </div>
      </td>
      <td class='client-readme-param-content'>
        <p>Specify the first entry to retrieve (0-based, 0 is the most recent log entry). Defaults to 0.</p>

      </td>
    </tr>
    
  
    <tr>
      <td valign='top'>
        <div class='client-readme-param-container'>
          <div class='client-readme-param-container-inner'>
            <div class='client-readme-param-name'><code>length</code></div>
            
          </div>
        </div>
      </td>
      <td class='client-readme-param-content'>
        <p>Specify the maximum number of entries to retrieve starting at the offset. Defaults to 10. Maximum allowed value: 1,000.</p>

      </td>
    </tr>
    
  
    <tr>
      <td valign='top'>
        <div class='client-readme-param-container'>
          <div class='client-readme-param-container-inner'>
            <div class='client-readme-param-name'><code>onlyErrors</code></div>
            
          </div>
        </div>
      </td>
      <td class='client-readme-param-content'>
        <p>Retrieve only logs with an HTTP code different than 200 or 201. (deprecated)</p>

      </td>
    </tr>
    
  
    <tr>
      <td valign='top'>
        <div class='client-readme-param-container'>
          <div class='client-readme-param-container-inner'>
            <div class='client-readme-param-name'><code>type</code></div>
            
          </div>
        </div>
      </td>
      <td class='client-readme-param-content'>
        <p>Specify the type of logs to retrieve:</p>

<ul>
<li><code>query</code>: Retrieve only the queries.</li>
<li><code>build</code>: Retrieve only the build operations.</li>
<li><code>error</code>: Retrieve only the errors (same as <code>onlyErrors</code> parameters).</li>
</ul>

      </td>
    </tr>
    
</tbody></table>

```php
// Get last 10 log entries
$res = $client->getLogs();

// Get last 100 log entries
$res = $client->getLogs(0, 100);
```

browse`

The `search` method cannot return more than 1,000 results. If you need to
retrieve all the content of your index (for backup, SEO purposes or for running
a script on it), you should use the `browse` method instead. This method lets
you retrieve objects beyond the 1,000 limit.

This method is optimized for speed. To make it fast, distinct, typo-tolerance,
word proximity, geo distance and number of matched words are disabled. Results
are still returned ranked by attributes and custom ranking.


It will return a `cursor` alongside your data, that you can then use to retrieve
the next chunk of your records.

You can specify custom parameters (like `page` or `hitsPerPage`) on your first
`browse` call, and these parameters will then be included in the `cursor`. Note
that it is not possible to access records beyond the 1,000th on the first call.

Example:

```php
// Iterate with a filter over the index
foreach ($this->index->browse('', ['filters' => 'i<42']) as $hit) {
    print_r($hit);
}

$next_cursor = $this->index->browseFrom('', ['numericFilters' => 'i<42'])['cursor'];
```









## Api Keys

The **admin** API key provides full control of all your indices. *The admin API key should always be kept secure; do NOT use it from outside your back-end.*

You can also generate user API keys to control security.
These API keys can be restricted to a set of operations or/and restricted to a given index.



### Secured API keys - `generateSecuredApiKey`

You may have a single index containing **per user** data. In that case, all records should be tagged with their associated `user_id` in order to add a `tagFilters=user_42` filter at query time to retrieve only what a user has access to. If you're using the [JavaScript client](http://github.com/algolia/algoliasearch-client-js), it will result in a security breach since the user is able to modify the `tagFilters` you've set by modifying the code from the browser. To keep using the JavaScript client (recommended for optimal latency) and target secured records, you can generate a secured API key from your backend:

```php
// generate a public API key for user 42. Here, records are tagged with:
//  - 'user_XXXX' if they are visible by user XXXX
$public_key = $client->generateSecuredApiKey('YourSearchOnlyApiKey', ['filters' => '_tags:user_42']);
```

This public API key can then be used in your JavaScript code as follow:

```js
var client = algoliasearch('YourApplicationID', '<%= public_api_key %>');

var index = client.initIndex('indexName')

index.search('something', function(err, content) {
  if (err) {
    console.error(err);
    return;
  }

  console.log(content);
});
```

You can mix rate limits and secured API keys by setting a `userToken` query parameter at API key generation time. When set, a unique user will be identified by her `IP + user_token` instead of only by her `IP`. This allows you to restrict a single user to performing a maximum of `N` API calls per hour, even if she shares her `IP` with another user.

```php
// generate a public API key for user 42. Here, records are tagged with:
//  - 'user_XXXX' if they are visible by user XXXX
$public_key = $client->generateSecuredApiKey(
    'YourSearchOnlyApiKey',
    ['filters' => 'user_42', 'userToken' => 'user_42']
);
```

This public API key can then be used in your JavaScript code as follow:

```js
var client = algoliasearch('YourApplicationID', '<%= public_api_key %>');

var index = client.initIndex('indexName')

index.search('another query', function(err, content) {
  if (err) {
    console.error(err);
    return;
  }

  console.log(content);
});
```




### List api keys - `listApiKeys`

To list existing keys, you can use:

```php
// Lists global API Keys
$client->listUserKeys();

// Lists API Keys that can access only to this index
$index->listUserKeys();
```

Each key is defined by a set of permissions that specify the authorized actions. The different permissions are:
 * **search**: Allowed to search.
 * **browse**: Allowed to retrieve all index contents via the browse API.
 * **addObject**: Allowed to add/update an object in the index.
 * **deleteObject**: Allowed to delete an existing object.
 * **deleteIndex**: Allowed to delete index content.
 * **settings**: allows to get index settings.
 * **editSettings**: Allowed to change index settings.
 * **analytics**: Allowed to retrieve analytics through the analytics API.
 * **listIndexes**: Allowed to list all accessible indexes.


### Add user key - `addUserKey`

To create API keys:

```php
// Creates a new global API key that can only perform search actions
$res = $client->addUserKey(['search']);
echo 'key=' . $res['key'] . "\n";

// Creates a new API key that can only perform search action on this index
$res = $index->addUserKey(['search']);
echo 'key=' . $res['key'] . "\n";
```

You can also create an API Key with advanced settings:

<table><tbody>
  
    <tr>
      <td valign='top'>
        <div class='client-readme-param-container'>
          <div class='client-readme-param-container-inner'>
            <div class='client-readme-param-name'><code>validity</code></div>
            
          </div>
        </div>
      </td>
      <td class='client-readme-param-content'>
        <p>Add a validity period. The key will be valid for a specific period of time (in seconds).</p>

      </td>
    </tr>
    
  
    <tr>
      <td valign='top'>
        <div class='client-readme-param-container'>
          <div class='client-readme-param-container-inner'>
            <div class='client-readme-param-name'><code>maxQueriesPerIPPerHour</code></div>
            
          </div>
        </div>
      </td>
      <td class='client-readme-param-content'>
        <p>Specify the maximum number of API calls allowed from an IP address per hour. Each time an API call is performed with this key, a check is performed. If the IP at the source of the call did more than this number of calls in the last hour, a 403 code is returned. Defaults to 0 (no rate limit). This parameter can be used to protect you from attempts at retrieving your entire index contents by massively querying the index.</p>

<p>Note: If you are sending the query through your servers, you must use the <code>enableRateLimitForward(&quot;TheAdminAPIKey&quot;, &quot;EndUserIP&quot;, &quot;APIKeyWithRateLimit&quot;)</code> function to enable rate-limit.</p>

      </td>
    </tr>
    
  
    <tr>
      <td valign='top'>
        <div class='client-readme-param-container'>
          <div class='client-readme-param-container-inner'>
            <div class='client-readme-param-name'><code>maxHitsPerQuery</code></div>
            
          </div>
        </div>
      </td>
      <td class='client-readme-param-content'>
        <p>Specify the maximum number of hits this API key can retrieve in one call. Defaults to 0 (unlimited). This parameter can be used to protect you from attempts at retrieving your entire index contents by massively querying the index.</p>

      </td>
    </tr>
    
  
    <tr>
      <td valign='top'>
        <div class='client-readme-param-container'>
          <div class='client-readme-param-container-inner'>
            <div class='client-readme-param-name'><code>indexes</code></div>
            
          </div>
        </div>
      </td>
      <td class='client-readme-param-content'>
        <p>Specify the list of targeted indices. You can target all indices starting with a prefix or ending with a suffix using the &#39;*&#39; character. For example, &quot;dev_*&quot; matches all indices starting with &quot;dev_&quot; and &quot;*_dev&quot; matches all indices ending with &quot;_dev&quot;. Defaults to all indices if empty or blank.</p>

      </td>
    </tr>
    
  
    <tr>
      <td valign='top'>
        <div class='client-readme-param-container'>
          <div class='client-readme-param-container-inner'>
            <div class='client-readme-param-name'><code>referers</code></div>
            
          </div>
        </div>
      </td>
      <td class='client-readme-param-content'>
        <p>Specify the list of referers. You can target all referers starting with a prefix or ending with a suffix using the &#39;*&#39; character. For example, &quot;algolia.com/*&quot; matches all referers starting with &quot;algolia.com/&quot; and &quot;*.algolia.com&quot; matches all referers ending with &quot;.algolia.com&quot;. Defaults to all referers if empty or blank.</p>

      </td>
    </tr>
    
  
    <tr>
      <td valign='top'>
        <div class='client-readme-param-container'>
          <div class='client-readme-param-container-inner'>
            <div class='client-readme-param-name'><code>queryParameters</code></div>
            
          </div>
        </div>
      </td>
      <td class='client-readme-param-content'>
        <p>Specify the list of query parameters. You can force the query parameters for a query using the url string format (param1=X&amp;param2=Y...).</p>

      </td>
    </tr>
    
  
    <tr>
      <td valign='top'>
        <div class='client-readme-param-container'>
          <div class='client-readme-param-container-inner'>
            <div class='client-readme-param-name'><code>description</code></div>
            
          </div>
        </div>
      </td>
      <td class='client-readme-param-content'>
        <p>Specify a description to describe where the key is used.</p>

      </td>
    </tr>
    

</tbody></table>

 ```php
// Creates a new index specific API key valid for 300 seconds, with a rate limit of 100 calls per hour per IP and a maximum of 20 hits

$params = [
    'validity'               => 300,
    'maxQueriesPerIPPerHour' => 100,
    'maxHitsPerQuery'        => 20,
    'indexes'                => ['dev_*'],
    'referers'               => ['algolia.com/*'],
    'queryParameters'        => 'typoTolerance=strict&ignorePlurals=false',
    'description'            => 'Limited search only API key for algolia.com'
];

$res = $client->addUserKey(params);
echo 'key=' . $res['key'] . "\n";
```

### Update user key - `updateUserKey`

To update the permissions of an existing key:
 ```php
// Update an existing global API key that is valid for 300 seconds
$res = $client->updateUserKey('myAPIKey', ['search'], 300);
echo 'key=' . $res['key'] . "\n";

// Update an existing index specific API key valid for 300 seconds, with a rate limit of 100 calls per hour per IP and a maximum of 20 hits
$res = $index->updateUserKey('myAPIKey', ['search'], 300, 100, 20);
echo 'key=' . $res['key'] . "\n";
```

### Delete user key - `deleteUserKey`

To delete an existing key:
```php
// Deletes a global key
$res = $client->deleteUserKey('f420238212c54dcfad07ea0aa6d5c45f');

// Deletes an index specific key
$res = $index->deleteUserKey('71671c38001bf3ac857bc82052485107');
```

### Get key permissions - `getUserKeyACL`

To get the permissions of a given key:
```php
// Gets the rights of a global key
$res = $client->getUserKeyACL('f420238212c54dcfad07ea0aa6d5c45f');

// Gets the rights of an index specific key
$res = $index->getUserKeyACL('71671c38001bf3ac857bc82052485107');
```



