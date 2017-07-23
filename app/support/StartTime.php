<?php

namespace TestPhalconApi\Support;

class StartTime
{
    /**
     * Application start time.
     *
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
     * Method to retrieve application start time.
     *
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }
}
