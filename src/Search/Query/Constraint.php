<?php
declare(strict_types = 1);

namespace SimplyAdmire\Koop\Search\Query;

final class Constraint implements ConstraintInterface
{

    private $propertyName;

    private $operator;

    private $value;

    public function __construct(string $propertyName, string $operator, string $value)
    {
        $this->propertyName = $propertyName;
        $this->operator = $operator;
        $this->value = $value;
    }

    public function __toString(): string
    {
        return \implode('', [$this->propertyName, $this->operator, \urlencode((string)$this->value)]);
    }

}
