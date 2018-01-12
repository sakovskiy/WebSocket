<?php

namespace Lara\Foundation;

use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Illuminate\Config\Repository;
use Monolog\Handler\StreamHandler;
use Illuminate\Container\Container;
use Illuminate\Contracts\Container\Container as ContainerInterface;
use Illuminate\Contracts\Config\Repository as RepositoryInterface;


/**
 * Class Application
 * @package Lara\Fundation
 */
class Application extends Container
{
    /**
     * @var string
     */
    private $basePath;

    /**
     * @var array
     */
    private $providers = [];

    /**
     * Application constructor.
     * @param string $basePath
     */
    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;

        $this->registerKernelComponent();

        $this->registerProviders();
    }

    /**
     * @return void
     */
    public function registerKernelComponent()
    {
        //Application
        $this->instance(Application::class, $this);
        $this->alias(Application::class, Container::class);
        $this->alias(Application::class, ContainerInterface::class);
        $this->alias(Application::class, 'app');

        //Logs
        $logger = new Logger('lara');
        $logger->pushHandler(new StreamHandler(STDOUT));
        $this->instance(LoggerInterface::class, $logger);
        $this->alias(LoggerInterface::class, 'log');

        //Config
        $this->singleton(Repository::class, function () {
            $config = require $this->getConfigPath() . '/app.php';

            return new Repository(['app' => $config]);
        });

        $this->alias(Repository::class, 'config');
        $this->alias(Repository::class, RepositoryInterface::class);
    }

    /**
     * @return void
     */
    private function registerProviders()
    {
        $config = $this->make(Repository::class);

        foreach ($config->get('app.providers', []) as $providerClass) {

            $provider = new $providerClass($this);

            if (method_exists($provider, 'register')) {
                $this->call([$provider, 'register']);
            }
            $this->providers[] = $provider;
        }
    }

    /**
     * @return string
     */
    public function getConfigPath()
    {
        return $this->basePath . '/config';
    }


    /**
     * @return void
     */
    public function boot()
    {
        foreach ($this->providers as $provider) {
            if (method_exists($provider, 'boot')) {
                $this->call([$provider, 'boot']);
            }
        }
    }
}