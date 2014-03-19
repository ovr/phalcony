<?php

/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 */

namespace PhalconyTest\Validator;

use Phalcony\Validator\Inn;

class InnTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccess()
    {
        $validator = new Inn();
        $this->assertTrue($validator->isValid('5036032527'));
        $this->assertTrue($validator->isValid('7830002293'));

        $this->assertTrue($validator->isValid('500100732259'));
    }

    public function testNumericFail()
    {
        $validator = new Inn();
        $this->assertFalse($validator->isValid('test'));
    }
}
