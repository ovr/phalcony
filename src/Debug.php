<?php
/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 * @date: 07.03.14 15:19
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
    static public function dump($variable, $echo = true)
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
