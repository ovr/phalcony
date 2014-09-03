<?php
/**
 * @author: Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
 */

namespace Phalcony\Stdlib\Hydrator;

use BadMethodCallException;

class ClassProperties implements HydratorInterface
{
    public static function hydrate(array $data, $object)
    {
        if (!is_object($object)) {
            throw new BadMethodCallException(sprintf(
                '%s expects the provided $object to be a PHP object)',
                __METHOD__
            ));
        }

        foreach ($data as $property => $value) {
            if (property_exists($object, $property)) {
                $object->{$property} = $value;
            }
        }

        return $object;
    }
}
