<?php
/**
 * @author: Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
 */

namespace Phalcony;

/**
 * Class HeadScript
 * @package Phalcony
 */
class HeadScript
{
    protected $vars = array();

    /**
     * @param $key
     * @param $value
     */
    public function addVar($key, $value)
    {
        $this->vars[$key] = $value;
    }

    public function __toString()
    {
        if (count($this->vars) == 0) {
            return '';
        }

        $html = '<script type="text/javascript">' . "\n";

        foreach ($this->vars as $key => $value) {
            $html .= "\t\tvar " . $key . ' = ' . json_encode($value) . ";\n";
        }

        return $html . "\t" . '</script>';
    }
}
