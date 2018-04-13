<?php

namespace Maduser\Minimal\Modules;

use Maduser\Minimal\Collections\Contracts\CollectionInterface;
use Maduser\Minimal\Framework\Facades\IOC;
use Maduser\Minimal\Modules\Contracts\ModulesInterface;

/**
 * Class Modules
 *
 * @package Maduser\Minimal\Modules
 */
class Modules implements ModulesInterface
{
    /**
     * @var CollectionInterface
     */
    private $modules;

    /**
     * @var string
     */
    private $dir = '';

    /**
     * @var string
     */
    private $base = '';

    /**
     * @return CollectionInterface
     */
    public function getModules(): CollectionInterface
    {
        return $this->modules;
    }

    /**
     * @param CollectionInterface $modules
     *
     * @return Modules
     */
    public function setModules(CollectionInterface $modules): Modules
    {
        $this->modules = $modules;

        return $this;
    }

    /**
     * @return string
     */
    public function getDir(): string
    {
        return rtrim($this->dir, '/') . '/';
    }

    /**
     * @param string $dir
     *
     * @return Modules
     */
    public function setDir(string $dir): Modules
    {
        $this->dir = $dir;

        return $this;
    }

    /**
     * @return string
     */
    public function getBase(): string
    {
        return rtrim($this->base, '/') . '/';
    }

    /**
     * @param string $base
     *
     * @return Modules
     */
    public function setBase(string $base): Modules
    {
        $this->base = $base;

        return $this;
    }

    /**
     * Modules constructor.
     *
     * @param CollectionInterface $collection
     * @param string              $dir
     * @param string              $base
     */
    public function __construct(
        CollectionInterface $collection,
        string $dir = '',
        string $base = ''
    ) {
        $this->setModules($collection);
        $this->setDir($dir);
        $this->setBase($base);
    }

    /**
     * @param $name
     * @param $module
     */
    public function add($name, $module)
    {
        if (! $this->modules->exists($name)) {
            $this->modules->add($module, $name);
        }
    }

    /**
     * @param $name
     *
     * @return mixed|null
     */
    public function get($name)
    {
        return $this->modules->exists($name);
    }

    /**
     * @return CollectionInterface
     */
    public function all()
    {
        return $this->modules;
    }

    /**
     * @param string $module
     *
     * @return array
     */
    public function register(string $module)
    {
        $dir = $this->getBase() . $this->getDir() . rtrim($module, '*');

        if (is_dir($dir)) {
            return $this->fromPath($module);
        } else {
            return $this->fromProvider($module);
        }
    }

    /**
     * @param $path
     * @param $name
     *
     * @return array
     */
    protected function getDirs($path, $name)
    {
        $array = [];

        $dirs = array_filter(glob($path . $name), 'is_dir');

        foreach ($dirs as $dir) {
            $array[str_replace($path, '', $dir)] = $path;
        }

        return $array;
    }

    /**
     * @param $name
     *
     * @return array
     */
    protected function fromPath($name)
    {
        $modules = [];

        $base = $this->getBase();
        $path = $this->getDir();

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

    /**
     * @param $name
     * @param $path
     *
     * @return mixed|null
     */
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

    /**
     * @param $provider
     *
     * @return mixed
     */
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