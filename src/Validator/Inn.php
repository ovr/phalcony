<?php
/**
 * @author: Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
 */

namespace Phalcony\Validator;

class Inn extends AbstractValidator
{
    const WRONG_LENGTH = 'wrong_length';
    const NOT_NUMERIC = 'not_numeric';

    /**
     * Validate inn number
     *
     * @param $value
     * @return bool|mixed
     * @throws Exception
     */
    public function validate($value)
    {
        if (!is_numeric($value)) {
            throw new Exception(self::NOT_NUMERIC);
        }

        switch(strlen($value)) {
            case 10:
                return $value[9] === (string) (((
                    2*$value[0] + 4*$value[1] + 10*$value[2] +
                    3*$value[3] + 5*$value[4] +  9*$value[5] +
                    4*$value[6] + 6*$value[7] +  8*$value[8]
                ) % 11) % 10);
                break;
            case 12:
                $num10 = (string) (((
                    7*$value[0] + 2*$value[1] + 4*$value[2] +
                    10*$value[3] + 3*$value[4] + 5*$value[5] +
                    9*$value[6] + 4*$value[7] + 6*$value[8] +
                    8*$value[9]
                ) % 11) % 10);

                $num11 = (string) (((
                    3*$value[0] +  7*$value[1] + 2*$value[2] +
                    4*$value[3] + 10*$value[4] + 3*$value[5] +
                    5*$value[6] +  9*$value[7] + 4*$value[8] +
                    6*$value[9] +  8*$value[10]
                ) % 11) % 10);

                return $value[11] === $num11 && $value[10] === $num10;
                break;
        }
    }
}
