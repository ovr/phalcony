<?php
/**
 * @author: Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
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

            $this->forward($this->exceptionPath);
            
            if (isset($this->exceptionPath['module'])) {
                $this->setModuleName($this->exceptionPath['module']);
            }

            $this->setControllerName($this->exceptionPath['controller']);
            $this->setActionName($this->exceptionPath['action']);
            
            /**
             * @todo Change to setParam after fix of Zephir's bug
             */ 
            $this->setParams(array(
                'exception' => $e
            ));

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
