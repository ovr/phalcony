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
}