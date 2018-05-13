<?php namespace Maduser\Minimal\Modules;

use Maduser\Minimal\Framework\ArrayLoader;
use Maduser\Minimal\Framework\Facades\App;
use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Modules\Contracts\ModuleInterface;

use ReflectionClass;
use ReflectionException;

/**
 * Class Module
 *
 * @package Maduser\Minimal\Modules
 */
class Module implements ModuleInterface
{
    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $path = '';

    /**
     * @var string
     */
    protected $bindings = 'Config/bindings.php';

    /**
     * @var string
     */
    protected $providers = 'Config/providers.php';

    /**
     * @var string
     */
    protected $config = 'Config/config.php';

    /**
     * @var string
     */
    protected $subscribers = 'Config/subscribers.php';

    /**
     * @var string
     */
    protected $routes = 'Config/routes.php';


    /**
     * @return string
     * @throws ReflectionException
     * @throws \Maduser\Minimal\Config\Exceptions\KeyDoesNotExistException
     */
    public function getName(): string
    {
        if (empty($this->name)) {
            $this->setName($this->getPath());
        }

        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return ModuleInterface
     */
    public function setName(string $name): ModuleInterface
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     * @throws ReflectionException
     * @throws \Maduser\Minimal\Config\Exceptions\KeyDoesNotExistException
     */
    public function getPath(): string
    {
        if (!$this->path) {
            $this->setPath($this->resolvePath());
        }

        return rtrim($this->path, '/') . '/';
    }

    /**
     * @param string $path
     *
     * @return ModuleInterface
     */
    public function setPath(string $path): ModuleInterface
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return string
     */
    public function getBindings(): string
    {
        return $this->bindings;
    }

    /**
     * @param string $bindings
     *
     * @return ModuleInterface
     */
    public function setBindings(string $bindings): ModuleInterface
    {
        $this->bindings = $bindings;

        return $this;
    }

    /**
     * @return string
     */
    public function getProviders(): string
    {
        return $this->providers;
    }

    /**
     * @param string $providers
     *
     * @return ModuleInterface
     */
    public function setProviders(string $providers): ModuleInterface
    {
        $this->providers = $providers;

        return $this;
    }

    /**
     * @return string
     */
    public function getConfig(): string
    {
        return $this->config;
    }

    /**
     * @param string $config
     *
     * @return ModuleInterface
     */
    public function setConfig(string $config): ModuleInterface
    {
        $this->config = $config;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubscribers(): string
    {
        return $this->subscribers;
    }

    /**
     * @param string $subscribers
     *
     * @return ModuleInterface
     */
    public function setSubscribers(string $subscribers): ModuleInterface
    {
        $this->subscribers = $subscribers;

        return $this;
    }

    /**
     * @return string
     */
    public function getRoutes(): string
    {
        return $this->routes;
    }

    /**
     * @param string $routes
     *
     * @return ModuleInterface
     */
    public function setRoutes(string $routes): ModuleInterface
    {
        $this->routes = $routes;

        return $this;
    }

    /**
     * Module constructor.
     *
     * @param array $settings
     */
    public function __construct(array $settings = [])
    {
        foreach ($settings as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (method_exists($this, $method)) {
                $this->{$method}($value);
            }
        }

        count($settings) <= 2 || $this->load();
    }

    /**
     * @throws \Maduser\Minimal\Config\Exceptions\KeyDoesNotExistException
     */
    protected function load()
    {
        ArrayLoader::config(
            Config::paths('system') . $this->getConfig()
        );

        ArrayLoader::providers(
            Config::paths('system') . $this->getProviders()
        );

        ArrayLoader::bindings(
            Config::paths('system') . $this->getBindings()
        );

        ArrayLoader::subscribers(
            Config::paths('system') . $this->getSubscribers()
        );

        ArrayLoader::routes(
            Config::paths('system') . $this->getRoutes()
        );
    }

    /**
     * @return string
     * @throws ReflectionException
     * @throws \Maduser\Minimal\Config\Exceptions\KeyDoesNotExistException
     */
    public function resolvePath()
    {
        $reflection = new ReflectionClass($this);
        $path = dirname($reflection->getFileName());
        $path = str_replace(Config::item('paths.system'), '', $path);

        return ltrim($path, '/');
    }

    /**
     * @return mixed
     * @throws ReflectionException
     * @throws \Maduser\Minimal\Config\Exceptions\KeyDoesNotExistException
     */
    public function create()
    {
        $class = get_called_class();

        return new $class([
            'name' => $this->getName(),
            'path' => $this->getPath(),
            'config' => $this->getPath() . $this->getConfig(),
            'bindings' => $this->getPath() . $this->getBindings(),
            'providers' => $this->getPath() . $this->getProviders(),
            'subscribers' => $this->getPath() . $this->getSubscribers(),
            'routes' => $this->getPath() . $this->getRoutes()
        ]);
    }

}