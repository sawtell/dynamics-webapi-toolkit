<?php

namespace AlexaCRM\Xrm\Query;

class LambdaFilter
{
    const TYPE = 'e';
    public function __construct(public string $relationship, public Filter|FilterSet $filter, public string $lambdaOperator = 'any')
    {
    }

    /**
     * Set the logical operator to use for combining filters.
     *
     * @param string $lambdaOperator
     * @return $this
     */
    public function setLambdaOperator(string $lambdaOperator): static
    {
        $this->lambdaOperator = $lambdaOperator;
        return $this;
    }

    public function toString()
    {
        if (in_array($this->filter->operator, Filter::ODATA_QUERY_FUNCTIONS)) {
            return $this->relationship . '/' . $this->lambdaOperator . '(' . self::TYPE . ': ' . $this->filter->toString() . ')';
        }

        if (in_array($this->filter->operator, Filter::QUERY_FUNCTIONS)) {
            return $this->relationship . '/' . $this->lambdaOperator . '(' . self::TYPE . ': ' . $this->filter->toString() . ')';
        }

        return $this->relationship . '/' . $this->lambdaOperator . '(' . self::TYPE . ': ' . self::TYPE . '/ ' . $this->filter->toString() .  ')';
    }
}
