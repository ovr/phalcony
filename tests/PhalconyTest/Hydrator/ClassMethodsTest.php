<?php

/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 */

namespace Phalcony\Test\Hydrator;

use Phalcony\Stdlib\Hydrator\ClassMethods;
use Phalcony\Test\Object;

class ClassMethodsTest extends \PHPUnit_Framework_TestCase
{
    public function testHydrateNotObjectExcepton()
    {
        $this->setExpectedException('BadMethodCallException', 'hydrate expects the provided $object to be a PHP object');
        ClassMethods::hydrate(array(), 1);
    }

    public function testHydrateSuccess()
    {
        $object = new Object();

        $this->assertEquals(null, $object->property1);
        $this->assertEquals(null, $object->property2);
        $this->assertEquals(null, $object->property3);

        $object = ClassMethods::hydrate(
            array(
                'property1' => 1,
                'property2' => 2,
                'property3' => 3
            ),
            $object
        );

        $this->assertEquals(1, $object->property1);
        $this->assertEquals(2, $object->property2);
        $this->assertEquals(3, $object->property3);
    }
}
