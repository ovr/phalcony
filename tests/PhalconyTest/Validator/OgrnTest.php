<?php

/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 */

namespace PhalconyTest\Validator;

use Phalcony\Validator\Ogrn;

class OgrnTest extends \PHPUnit_Framework_TestCase
{
    public function testSuccess()
    {
        $validator = new Ogrn();
        $this->assertTrue($validator->isValid('1022401359452'));
        $this->assertTrue($validator->isValid('1064910045254'));
        $this->assertTrue($validator->isValid('1082721447270'));
        $this->assertTrue($validator->isValid('1077758445786'));
    }

    public function testNumericFail()
    {
        $validator = new Ogrn();
        $this->assertFalse($validator->isValid('test'));
    }
}
