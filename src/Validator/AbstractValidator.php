<?php
/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 * @date: 19.03.14 0:39
 */

namespace Phalcony\Validator;

abstract class AbstractValidator
{
    /**
     * @param $value
     * @return mixed
     */
    abstract public function isValid($value);
}
