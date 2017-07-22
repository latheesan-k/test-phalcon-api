<?php

namespace TestPhalconApi\Support;

class StartTime
{
    /**
     * @var float
     */
    private $value = 0.00;

    /**
     * StartTime constructor.
     * @param $value
     */
    function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
