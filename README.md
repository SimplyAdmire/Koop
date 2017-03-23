# Koop Client

PHP Client for the Koop publications API. Allows doing combined queries and
returns an iteratable result object.

Currently only a simple publication object with a title, publication date
and original url is returned. But this is perfectly suitable for a website
search client pointing to the official source on officielebekendmakingen.nl.

The query result will fetch a single document

Official API document can be found at http://koop.overheid.nl/producten/gvop/documentatie

## Code examples

Find the latest 10 (default limit) items for Barneveld
```php
    $client = new \SimplyAdmire\Koop\Client(
        new \SimplyAdmire\Koop\Configuration()
    );

    $query = new \SimplyAdmire\Koop\Search\Query();
    $query
        ->matching(
            $query->exactMatch('creator', 'Barneveld')
        );

    $result = $client->execute($query);
```

Find the latest 10 items for either Barneveld or Ede
```php
    $query
        ->matching(
            $query->logicalOr(
                $query->exactMatch('creator', 'Barneveld'),
                $query->exactMatch('creator', 'Ede')
            )
        );
```

Find max 50 items about the term 'paspoort' published by Barneveld or Ede
where:
* Barneveld published since 01-01-2014
* Ede published since 01-01-2015

```php
    $query
        ->setLimit(50)
        ->matching(
            $query->logicalOr(
                $query->logicalAnd(
                    $query->exactMatch('creator', 'Barneveld'),
                    $query->since(new \DateTime('2014-01-01')),
                    $query->fullText('paspoort')
                ),
                $query->logicalAnd(
                    $query->exactMatch('creator', 'Ede'),
                    $query->since(new \DateTime('2015-01-01')),
                    $query->fullText('paspoort')
                )
            )
        );
```

Iterating over the resut:

```php
    ...
    $result = $client->execute($query);

    /** @var \SimplyAdmire\Koop\Model\Publication $publication */
    foreach ($result as $publication)
    {
        echo $publication->getPublicationDate()->format('d-m-Y') . ' ' . $publication->getTitle() . PHP_EOL;
    }
```
