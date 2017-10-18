<?php

namespace LightCms\Console;

use Psr\Container\ContainerInterface;
use LightCms\Base\AppTrait;

/**
 * Class ConsoleApp
 * @package slimExt\base
 */
class App extends \Inhere\Console\App
{
    use AppTrait, QuicklyGetServiceTrait;

    /**
     * @var array
     */
    protected static $bootstraps = [
        'commands' => [
            // CommandUpdateCommand::class,
        ],
        'controllers' => [
            // GeneratorController::class,
        ],
    ];

    /**
     * Constructor.
     *
     * @internal string $name The name of the application
     * @internal string $version The version of the application
     */
    public function __construct($container)
    {
        \Sys::$app = $this;
        $this->di = $container;

        parent::__construct([
            'name' => 'LightCms Console',
            'version' => '0.0.1'
        ]);

        // $config->loadArray($this->config);
        $this->loadBootstrapCommands();
    }

    /**
     * loadBuiltInCommands
     */
    public function loadBootstrapCommands()
    {
        /** @var \inhere\console\Command $command */
        foreach ((array)static::$bootstraps['commands'] as $command) {
            $this->command($command::getName(), $command);
        }

        /** @var \inhere\console\Controller $controller */
        foreach ((array)static::$bootstraps['controllers'] as $controller) {
            $this->controller($controller::getName(), $controller);
        }
    }

    /**
     * @param string $name
     * @param mixed $handler
     * @return $this
     */
    public function add($name, $handler = null)
    {
        return $this->command($name, $handler);
    }

    /**
     * Enable access to the DI container by consumers of $app
     *
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }
}
