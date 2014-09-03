<?php
/**
 * @author: Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
 */

namespace Phalcony\Stdlib\Hydrator;

use BadMethodCallException;

class ClassMethods implements HydratorInterface
{
    public static function hydrate(array $data, $object)
    {
        if (!is_object($object)) {
            throw new BadMethodCallException(sprintf(
                '%s expects the provided $object to be a PHP object',
                __METHOD__
            ));
        }

        foreach ($data as $property => $value) {
            $method = 'set' . ucfirst($property);

            if (method_exists($object, $method)) {
                $object->$method($value);
            }
        }

        return $object;
    }
}
