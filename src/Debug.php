<?php
/**
 * @author: Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
 */

namespace Phalcony;

/**
 * Class Debug
 * @package Phalcony
 */
class Debug
{
    /**
     * @param $variable
     * @param bool $echo
     * @return string
     */
    public static function dump($variable, $echo = true)
    {
        ob_start();
        var_dump($variable);
        $output = ob_get_clean();

        if (PHP_SAPI != 'cli') {
            $output = '<pre>' . $output . '</pre>';
        }

        if ($echo) {
            echo $output;
        }

        return $output;
    }
}
