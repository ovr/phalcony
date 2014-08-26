<?php
/**
 * @author Patsura Dmitry @ovr <talk@dmtry.me>
 */

namespace Phalcony\Rest;

class Dispatcher extends \Phalcon\Mvc\Dispatcher
{
    /**
     * @var array
     */
    protected $exceptionPath;

    /**
     * @return array
     */
    public function getExceptionPath()
    {
        return $this->exceptionPath;
    }

    /**
     * @param array $exceptionPath
     */
    public function setExceptionPath($exceptionPath)
    {
        $this->exceptionPath = $exceptionPath;
    }

    public function dispatch()
    {
        try {
            $controller = parent::dispatch();
        } catch (\Exception $e) {

            $this->forward($this->errorPath);
            $this->setModuleName($this->errorPath['module']);
            $this->setControllerName($this->errorPath['controller']);
            $this->setActionName($this->errorPath['action']);
            $this->setParam('exception', $e);

            return $this->dispatch();
        }

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
