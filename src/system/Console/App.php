<?php

namespace LightCms\Console;

use Inhere\Console\Application;
use Inhere\Library\DI\Container;
use LightCms\Base\AppInterface;
use LightCms\Base\AppTrait;

/**
 * Class ConsoleApp
 * @package LightCms\Console
 */
class App extends Application implements AppInterface
{
    use AppTrait;

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
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        \Sys::$app = $this;
        $this->di = $container;

        parent::__construct([
            'name' => 'LightCms Console',
            'version' => '0.0.1',
            'publishAt' => '2017.10.19',
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

}
