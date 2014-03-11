<?php
/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 * @date: 07.03.14 14:05
 */

namespace Phalcony\Stdlib\Hydrator;

use BadMethodCallException;

class ClassMethods
    implements HydratorInterface
{
    public static function hydrate(array $data, $object)
    {
        if (!is_object($object)) {
            throw new BadMethodCallException(sprintf(
                '%s expects the provided $object to be a PHP object)',
                __METHOD__
            ));
        }

        $transform = function ($letters) {
            $letter = substr(array_shift($letters), 1, 1);
            return ucfirst($letter);
        };

        foreach ($data as $property => $value) {
            $method = 'set' . ucfirst($property);

            if (method_exists($object, $method)) {
                $object->$method($value);
            }
        }

        return $object;
    }
}
