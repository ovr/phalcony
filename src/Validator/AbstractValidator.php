<?php
/**
 * @author: Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
 */

namespace Phalcony\Validator;

use SebastianBergmann\Exporter\Exception;

abstract class AbstractValidator
{
    const NOT_VALID = 'not_valid';

    protected $message;

    public function isValid($value)
    {
        try {
            $this->validate($value);
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            return false;
        }

        return true;
    }

    /**
     * @param $value
     * @return mixed
     */
    abstract public function validate($value);

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }
}
