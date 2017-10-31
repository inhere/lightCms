<?php

namespace Micro\Console;

use Inhere\Console\Application;
use Inhere\Library\DI\Container;
use Micro\Base\AppInterface;
use Micro\Base\AppTrait;

/**
 * Class ConsoleApp
 * @package Micro\Console
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
     * @param Container $di
     */
    public function __construct(Container $di = null)
    {
        \Mgr::$app = $this;

        $this->di = $di ?: new Container;
        $this->di->registerServiceProvider(new DefaultServicesProvider);

        $meta = [
            'name' => 'Micro Console',
            'version' => '0.0.1',
            'publishAt' => '2017.10.19',
            'debug' => $this->di->get('config')['debug'],
        ];

        parent::__construct($meta, $this->di->get('input'), $this->di->get('output'));

        // $config->loadArray($this->config);
        $this->loadBootstrapCommands();
    }

    protected function init()
    {
        parent::init();

        $this->prepare();
//        $errHandler = new ErrorHandler();
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
