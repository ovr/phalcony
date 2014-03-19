<?php
/**
 * @author: Patsura Dmitry <zaets28rus@gmail.com>
 * @date: 01.03.14 1:21
 */

namespace Phalcony;

use Phalcon\Events\Manager as EventsManager;
use Phalcony\Stdlib\Hydrator\ClassMethods;

class Application extends \Phalcon\Mvc\Application
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
     * @var string
     */
    protected $env;

    /**
     * @param string $env
     * @param array $configuration
     * @param \Phalcon\DiInterface $di
     */
    public function __construct($env, array $configuration, \Phalcon\DiInterface $di = null)
    {
        $this->env = strtolower($env);
        $this->configuration = $configuration;

        switch ($this->env) {
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
     * Register loader
     */
    protected function _registerLoader()
    {
        $config = &$this->configuration;

        $loader = new \Phalcon\Loader();
        $loadNamespaces = $config['application']['registerNamespaces'];

        foreach ($config['application']['modules'] as $module => $enabled) {
            $moduleName = ucfirst($module);
            $loadNamespaces[$moduleName.'\Model'] = APPLICATION_PATH . '/modules/'.$module.'/models/';
            $loadNamespaces[$moduleName.'\Service'] = APPLICATION_PATH . '/modules/'.$module.'/services/';
        }

        $loader->registerDirs($config['application']['registerDirs'])
            ->registerNamespaces($loadNamespaces)
            ->register();

    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function bootstrap()
    {
        $di = $this->getDI();

        $this->_registerLoader();
        $this->registerModules($this->configuration['application']['modules']);

        $eventsManager = new EventsManager();
        $this->setEventsManager($eventsManager);

        if (isset($this->configuration['services'])) {
            if (!is_array($this->configuration['services'])) {
                throw new \Exception('Config[services] must be an array');
            }

            if (count($this->configuration['services']) > 0) {
                foreach ($this->configuration['services'] as $diServiceName => $serviceParameters) {
                    $class = $serviceParameters['class'];

                    if (gettype($class) != 'object') {
                        if (isset($serviceParameters['__construct'])) {
                            $service = new $class($serviceParameters['__construct']);
                        } else {
                            $service = new $class();
                        }
                    } else {
                        $service = $class;
                    }

                    if (isset($serviceParameters['parameters'])) {
                        $service = ClassMethods::hydrate($serviceParameters['parameters'], $service);
                    }

                    $di->set($diServiceName, $service);
                }
            }
        }

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
     * @return string
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
}
