<?php

namespace AlexaCRM\Xrm\Query;

class LambdaFilter
{
    const COMPARISON_OPERATORS = ['eq', 'ne', 'gt', 'ge', 'lt', 'le'];
    const ODATA_QUERY_FUNCTIONS = ['contains', 'endswith', 'startswith'];
    const QUERY_FUNCTIONS = ['In', 'NotIn'];

    /**
     * @param  string  $name
     * @param  string  $column
     * @param $value
     * @param  string  $operator
     * @param  bool  $negate
     * @throws \Exception
     */
    public function __construct(
        public string $name,
        public string $column,
        public $value,
        public string $operator = 'eq',
        public bool $negate = false
    ) {

        if (in_array($this->operator, self::QUERY_FUNCTIONS) && !is_array($this->value)) {
            throw new \Exception('Filter value must be an array when using a query function operator');
        }
        if (in_array($this->operator, self::ODATA_QUERY_FUNCTIONS) && !is_string($this->value)) {
            throw new \Exception('Filter value must be a string when using OData query function operators');
        }
        if (in_array($this->operator, self::COMPARISON_OPERATORS) && (!is_string($this->value) && !is_numeric($this->value))) {
            throw new \Exception('Filter value must be a string or number when using comparison operators');
        }
    }

    /**
     * @return string
     */
    public function toString()
    {
        if (in_array($this->operator, self::ODATA_QUERY_FUNCTIONS)) {
            return $this->name . '/any(e:' . $this->operator . '(e/' . $this->column . ', \'' . $this->value . '\'))';
        }

        if (in_array($this->operator, self::QUERY_FUNCTIONS)) {
            $propertyValues = json_encode($this->value);
            return $this->name . '/any(e: Microsoft.Dynamics.CRM.' . $this->operator . '(PropertyName=\'' . $this->column . '\',PropertyValues=' . $propertyValues . '))';
        }

        return $this->name . '/any(e: e/' . $this->column . ' ' . $this->operator . ' ' . $this->value . ')';
    }
}
