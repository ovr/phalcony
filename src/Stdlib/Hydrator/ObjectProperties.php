<?php
/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 * @date: 08.03.14 22:07
 */

namespace Phalcony\Stdlib\Hydrator;

use BadMethodCallException;

class ObjectProperties implements HydratorInterface
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
            $object->{$property} = $value;
        }

        return $object;
    }
}
