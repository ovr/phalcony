<?php
/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 * @date: 11.03.14 21:54
 */

namespace Phalcony\Stdlib\Hydrator;

interface HydratorInterface
{
    /**
     * @param array $data
     * @param $object
     * @return mixed
     */
    public static function hydrate(array $data, $object);
}
