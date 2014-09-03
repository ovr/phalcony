<?php
/**
 * @author: Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
 */

namespace Phalcony\Validator;

class Ogrn extends AbstractValidator
{
    const WRONG_LENGTH = 'wrong_length';
    const NOT_NUMERIC = 'not_numeric';

    /**
     * Validate ogrn number
     *
     * @param $value
     * @return mixed|void
     * @throws Exception
     */
    public function validate($value)
    {
        if (!is_numeric($value)) {
            throw new Exception(self::NOT_NUMERIC);
        }

        switch(strlen($value)) {
            case 13:
                $mod = 12;
                break;
            case 15:
                $mod = 14;
                break;
            default:
                throw new Exception(self::WRONG_LENGTH);
                break;
        }

        $check        = substr($value, 0, $mod);
        $checkValue   = $check - (floor($check / 11)) * 11;
        $controlValue = substr($value, $mod);

        if ($checkValue == 10) {
            $checkValue = 0;
        }

        if ($checkValue != $controlValue) {
            throw new Exception(self::NOT_VALID);
        }
    }
}
