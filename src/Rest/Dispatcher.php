<?php
/**
 * @author Patsura Dmitry @ovr <talk@dmtry.me>
 */

namespace Phalcony\Rest;

class Dispatcher extends \Phalcon\Mvc\Dispatcher
{
    public function dispatch()
    {
        $controller = parent::dispatch();

        /**
         * @var $response \Phalcon\Http\Response
         */
        $response = $controller->getDI()->get('response');
        $response->setJsonContent($this->getReturnedValue());

        /**
         * @todo Need to fix
         */
        $response->send();
        exit();

        return $controller;
    }
}
