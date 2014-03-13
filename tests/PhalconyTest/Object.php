<?php
/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 * @date: 13.03.14 21:27
 */

namespace Phalcony\Test;

class Object
{
    public $property1;
    public $property2;
    public $property3;

    /**
     * @param mixed $property1
     */
    public function setProperty1($property1)
    {
        $this->property1 = $property1;
    }

    /**
     * @param mixed $property2
     */
    public function setProperty2($property2)
    {
        $this->property2 = $property2;
    }

    /**
     * @param mixed $property3
     */
    public function setProperty3($property3)
    {
        $this->property3 = $property3;
    }
}
