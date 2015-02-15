<?php
/**
 * @author: Patsura Dmitry http://github.com/ovr <talk@dmtry.me>
 */

namespace Phalcony;

use Phalcon\Events\Manager as EventsManager;
use Phalcony\Stdlib\Hydrator\ClassMethods;
use InvalidArgumentException;

class Application extends \Phalcon\Mvc\Application
{
    const ENV_PRODUCTION = 'production';
    const ENV_STAGING = 'staging';
    const ENV_TESTING = 'testing';
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
     * @throws \Exception
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
                throw new \Exception('Wrong application $env passed: ' . $env);
        }

        if (is_null($di)) {
            $di = new \Phalcon\DI\FactoryDefault();
        }

        parent::__construct($di);
    }

    /**
     * Register loader
     */
    protected function registerLoader()
    {
        $config = &$this->configuration;

        $loader = new \Phalcon\Loader();

        if (isset($config['application']['registerNamespaces'])) {
            $loadNamespaces = $config['application']['registerNamespaces'];
        } else {
            $loadNamespaces = array();
        }

        foreach ($config['application']['modules'] as $module => $enabled) {
            $moduleName = ucfirst($module);
            $loadNamespaces[$moduleName . '\Model'] = APPLICATION_PATH . '/modules/' . $module . '/models/';
            $loadNamespaces[$moduleName . '\Service'] = APPLICATION_PATH . '/modules/' . $module . '/services/';
        }

        if (isset($config['application']['registerDirs'])) {
            $loader->registerDirs($config['application']['registerDirs']);
        }

        $loader->registerNamespaces($loadNamespaces)
            ->register();

    }

    /**
     * Register di services
     *
     * @throws \Exception
     */
    public function registerServices()
    {
        $di = $this->getDI();

        if (isset($this->configuration['services'])) {
            if (!is_array($this->configuration['services'])) {
                throw new \Exception('Config[services] must be an array');
            }

            if (count($this->configuration['services']) > 0) {
                foreach ($this->configuration['services'] as $serviceName => $serviceParameters) {
                    $class = $serviceParameters['class'];

                    $shared = false;
                    $service = false;

                    if (isset($serviceParameters['shared'])) {
                        $shared = (boolean) $serviceParameters['shared'];
                    }

                    if (is_callable($class)) {
                        $shared = true;
                        $service = $class($this);
                    } else if (is_object($class)) {
                        $shared = true;
                        $service = $class;
                    } else if (isset($serviceParameters['__construct'])) {
                        $shared = true;

                        if (!is_array($serviceParameters)) {
                            throw new \Exception('Parameters for service : "' . $serviceName . '" must be array');
                        }

                        $reflector = new \ReflectionClass($class);
                        $service = $reflector->newInstanceArgs($serviceParameters['__construct']);
                    } else {
                        if ($shared) {
                            $service = new $class();
                        } else {
                            $service = $class;
                        }
                    }

                    if (isset($serviceParameters['parameters'])) {
                        if ($shared === false) {
                            throw new \Exception('Service: "' . $serviceName . '" with parameters must be shared');
                        }

                        $service = ClassMethods::hydrate($serviceParameters['parameters'], $service);

                        $di->set($serviceName, $service, $shared);
                    }

                    $di->set($serviceName, $service, $shared);
                }
            }
        }
    }

    /**
     * @return $this
     * @throws \Exception
     */
    public function bootstrap()
    {
        $this->registerLoader();
        $this->registerModules($this->configuration['application']['modules']);

        $eventsManager = new EventsManager();
        $this->setEventsManager($eventsManager);

        $this->registerServices();
        $this->di->set('application', $this, true);

        return $this;
    }

    /**
     * Run app
     *
     * @param null $uri
     */
    public function run($uri = null)
    {
        $this->handle($uri)->send();
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

    /**
     * Get custom service parameters
     *
     * @param $serviceName
     * @return mixed
     */
    public function getParameters($serviceName)
    {
        if (isset($this->configuration['parameters'][$serviceName])) {
            return $this->configuration['parameters'][$serviceName];
        }

        throw new InvalidArgumentException('Wrong service : '.$serviceName.' passed to fetch from parameters');
    }
}
