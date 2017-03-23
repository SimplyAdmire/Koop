<?php
declare(strict_types = 1);

namespace SimplyAdmire\Koop\Search\Query;

final class LogicalAnd implements ConstraintInterface
{

    private $constraints;

    public function __construct(array $constraints)
    {
        $this->constraints = $constraints;
    }

    public function __toString(): string
    {
        if (empty($this->constraints)) {
            return '';
        }

        if (count($this->constraints) === 1) {
            return (string)\current($this->constraints);
        }

        return \sprintf(
            '(%s)',
            \implode(
                ' and ',
                \array_map('strval', $this->constraints)
            )
        );
    }

}
