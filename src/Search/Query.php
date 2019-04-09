<?php
declare(strict_types = 1);

namespace SimplyAdmire\Koop\Search;

use SimplyAdmire\Koop\Search\Query\Constraint;
use SimplyAdmire\Koop\Search\Query\ConstraintInterface;
use SimplyAdmire\Koop\Search\Query\LogicalAnd;
use SimplyAdmire\Koop\Search\Query\LogicalOr;

final class Query
{

    const VERSION = 1.2;

    const OPERATION = 'searchRetrieve';

    const XCONNECTION = 'oep';

    private $offset = 0;

    private $limit = 10;

    private $pageSize = 4200;

    /**
     * @var ConstraintInterface
     */
    private $constraint;

    public function matching(ConstraintInterface $constraint): self
    {
        $this->constraint = $constraint;
        return $this;
    }

    public function partialMatch(string $propertyName, string $value)
    {
        return new Constraint($propertyName, '=', $value);
    }

    public function exactMatch(string $propertyName, string $value)
    {
        return new Constraint($propertyName, '==', $value);
    }

    public function fullText(string $keyword)
    {
        return new Constraint('keyword all', '', $keyword);
    }

    public function since(\DateTime $date)
    {
        return new Constraint('available', '>=', $date->format('Y-m-d'));
    }

    public function till(\DateTime $date)
    {
        return new Constraint('available', '<=', $date->format('Y-m-d'));
    }

    public function logicalAnd()
    {
        return new LogicalAnd(\func_get_args());
    }

    public function logicalOr()
    {
        return new LogicalOr(\func_get_args());
    }

    public function setPageSize(int $pageSize): self
    {
        if ($pageSize > 4200) {
            throw new \Exception('Maximum allowed pagesize is 4200'); // @see API documentation on http://koop.overheid.nl/
        }

        $this->pageSize = $pageSize;
        return $this;
    }

    public function getPageSize(): int
    {
        return $this->pageSize;
    }

    public function setOffset(int $offset): self
    {
        $this->offset = $offset;
        return $this;
    }

    public function setLimit(int $limit): self
    {
        $this->limit = $limit;
        return $this;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }

    public function __toString()
    {
        $parameters = [
            'version' => self::VERSION,
            'operation' => self::OPERATION,
            'x-connection' => self::XCONNECTION,
            'startRecord' => $this->offset + 1,
            'query' => (string)$this->constraint . ' sortby available/sort.descending',
            'maximumRecords' => $this->limit
        ];

        return \http_build_query($parameters);
    }

}
