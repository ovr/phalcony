<?php
/**
 * @author Patsura Dmitry <zaets28rus@gmail.com>
 * @date: 01.03.14 1:21
 */

namespace Phalcony;

use Phalcon\Events\Manager as EventsManager;

class Application
    extends \Phalcon\Mvc\Application
{
    const ENV_PRODUCTION = 'production';
    const ENV_STAGING = 'staging';
    const ENV_TESTING = 'testing	';
    const ENV_DEVELOPMENT = 'development';

    /**
     * @var array
     */
    protected $configuration;

    /**
     * @param string $env
     * @param array $configuration
     * @param \Phalcon\DiInterface $di
     */
    public function __construct($env, array $configuration, \Phalcon\DiInterface $di = null)
    {
        $this->configuration = $configuration;

        switch ($env) {
            case self::ENV_PRODUCTION:
            case self::ENV_STAGING:
                ini_set('display_errors', 0);
                ini_set('display_startup_errors', 0);
                error_reporting(0);
                break;
            case self::ENV_TESTING:
            case self::ENV_DEVELOPMENT:
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(-1);
                break;
            default:
                ini_set('display_errors', 0);
                ini_set('display_startup_errors', 0);
                error_reporting(0);
        }

        if (is_null($di)) {
            $di = new \Phalcon\DI\FactoryDefault();
        }

        parent::__construct($di);
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * Register loader
     */
    protected function _registerLoader()
    {
        $config = &$this->configuration;

        $loader = new \Phalcon\Loader();
        $loadNamespaces = array();

        foreach ($config['modules'] as $module => $enabled) {
            $moduleName = ucfirst($module);
            $loadNamespaces[$moduleName.'\Model'] = APPLICATION_PATH . '/modules/'.$module.'/models/';
            $loadNamespaces[$moduleName.'\Service'] = APPLICATION_PATH . '/modules/'.$module.'/services/';
        }

        $loadNamespaces['Service'] = APPLICATION_PATH . '/services/';

        $loader->registerDirs($config['registerDirs'])
            ->registerNamespaces($loadNamespaces)
            ->register();

    }

    /**
     * @return $this
     */
    public function bootstrap()
    {
        $di = $this->getDI();

        $this->_registerLoader();
        $this->registerModules($this->configuration['modules']);

        $eventsManager = new EventsManager();
        $this->setEventsManager($eventsManager);

        $methods = $this->getInitServices();
        foreach($methods as $name => $method) {
            $returnValue = $this->{$method->getName()}();
            $di->set($name, function() use($returnValue) {return $returnValue;});
        }

        iconv_set_encoding('internal_encoding', 'UTF-8');
        setlocale(LC_ALL, 'ru_RU.UTF-8');

        return $this;
    }

    /**
     * Run app
     */
    public function run()
    {
        $this->handle($_SERVER['REQUEST_URI'])->send();
    }

    /**
     * Get class init methods
     *
     * @return array
     */
    protected function getInitServices()
    {
        $class        = new \ReflectionObject($this);
        $classMethods = $class->getMethods();

        $classResources = array();
        foreach ($classMethods as $method) {
            if (5 < strlen($method->getName()) && 'init' === substr($method->getName(), 0, 4)) {
                $classResources[lcfirst(substr($method->getName(), 4))] = $method;
            }
        }

        return $classResources;
    }
}