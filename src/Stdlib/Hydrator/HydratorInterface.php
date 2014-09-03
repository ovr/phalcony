<?php
/**
 * @author: Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
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
