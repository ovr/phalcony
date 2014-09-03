<?php
/**
 * @author: Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
 */

namespace Phalcony;

/**
 * Class HeadMeta
 * @package Phalcony
 */
class HeadMeta
{
    /**
     * Types of attributes
     * @var array
     */
    protected $_typeKeys = array('name', 'http-equiv', 'charset', 'property');
    protected $_requiredKeys = array('content');
    protected $_modifierKeys = array('lang', 'scheme');

    protected $_register = [];

    /**
     * @param null $content
     * @param null $keyValue
     * @param string $keyType
     */
    public function append($content = null, $keyValue = null, $keyType = 'name')
    {
        $this->_register[] = [
            strtolower($keyType) => $keyValue,
            'content' => $content
        ];
    }

    /**
     * @param null $content
     * @param null $keyValue
     * @param string $keyType
     */
    public function prepend($content = null, $keyValue = null, $keyType = 'name')
    {
        /**
         * @todo Сделать
         */
    }

    /**
     * @param $name
     * @param $content
     */
    public function appendName($name, $content)
    {
        $this->_register[] = [
            'name' => $name,
            'content' => $content
        ];
    }

    protected function _normalizeType($type)
    {
        switch ($type) {
            case 'Name':
            case 'name':
                return 'name';
            case 'HttpEquiv':
                return 'http-equiv';
            case 'Property':
                return 'property';
            default:
                exit();
        }
    }

    public function __toString()
    {
        $html = '';

        if (count($this->_register) == 0) {
            return $html;
        }

        foreach ($this->_register as $tag) {
            $html .= "\t" . \Phalcon\Tag::tagHtml('meta', $tag) . "\n";
        }

        return $html;
    }
}
