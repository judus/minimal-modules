<?php

namespace Maduser\Minimal\Modules;

use Maduser\Minimal\Collections\Contracts\CollectionInterface;
use Maduser\Minimal\Demos\Events\EventModule;
use Maduser\Minimal\Framework\Facades\Config;
use Maduser\Minimal\Framework\Facades\IOC;
use Maduser\Minimal\Modules\Contracts\ModulesInterface;

class Modules implements ModulesInterface
{
    private $modules = [];

    public function __construct(CollectionInterface $collection)
    {
        $this->modules = $collection;
    }

    public function add($name, $module)
    {
        if (! $this->modules->exists($name)) {
            $this->modules->add($module, $name);
        }
    }

    public function get($name)
    {
        return $this->modules->exists($name);
    }

    public function all()
    {
        return $this->modules;
    }

    public function register(string $module)
    {
        $dir = Config::paths('system') . '/' .
               Config::paths('modules') . '/' .
               ltrim(rtrim($module, '*'), '/');

        if (is_dir($dir)) {
            return $this->fromPath($module);
        } else {
            return $this->fromProvider($module);
        }
    }

    protected function getDirs($path, $name)
    {
        $array = [];

        $dirs = array_filter(glob($path . $name), 'is_dir');

        foreach ($dirs as $dir) {
            $array[str_replace($path, '', $dir)] = $path;
        }

        return $array;
    }

    protected function fromPath($name)
    {
        $modules = [];

        $base = Config::paths('system') . '/';
        $path = Config::paths('modules') . '/';

        if ($this->endsWith($name, '*')) {

            $dirs = $this->getDirs($base . $path, $name);

            foreach ($dirs as $name => $dir) {
                $modules[] = $this->makeFromPath($name, $path);
            }

        } else {
            $modules[] = $this->makeFromPath($name, $name);
        }

        return $modules;
    }

    protected function makeFromPath($name, $path)
    {
        if (!$this->get($name)) {

            $params = [
                'name' => $name,
                'path' => rtrim($path, '/') . '/' . $name
            ];

            $module = IOC::resolve('Module', $params);
            $this->add($module->getName(), $module->create());

            return $module;
        }

        return null;
    }

    protected function fromProvider($provider)
    {
        $provider = IOC::resolve($provider);
        $module = $provider->create();
        $this->add($provider->getName(), $module);
        return $module;
    }

    /**
     * @param $haystack
     * @param $needle
     *
     * @return bool
     */
    protected function startsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * @param $haystack
     * @param $needle
     *
     * @return bool
     */
    protected function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }


}