<?php

/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 */

namespace Phalcony\Test\Hydrator;

use Phalcony\Stdlib\Hydrator\ClassMethods;

class ClassMethodsTest extends \PHPUnit_Framework_TestCase
{
    public function testHydrateNotObjectExcepton()
    {
        $this->setExpectedException('BadMethodCallException', 'hydrate expects the provided $object to be a PHP object');
        ClassMethods::hydrate(array(), 1);
    }
}
