<?php
/**
 * @author Patsura Dmitry <zaets28rus@gmail.com>
 * @date: 01.03.14 1:21
 */

namespace Phalcony;

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
}