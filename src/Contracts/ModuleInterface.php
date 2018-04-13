<?php
/**
 * ModuleProviderInterface.php
 * 4/12/18 - 8:33 PM
 *
 * PHP version 7
 *
 * @package    @package_name@
 * @author     Julien Duseyau <julien.duseyau@gmail.com>
 * @copyright  2018 Julien Duseyau
 * @license    https://opensource.org/licenses/MIT
 * @version    Release: @package_version@
 *
 * The MIT License (MIT)
 *
 * Copyright (c) Julien Duseyau <julien.duseyau@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Maduser\Minimal\Modules\Contracts;

use Maduser\Minimal\Framework\Contracts\AppInterface;
use ReflectionException;

interface ModuleInterface
{
    /**
     * @return string
     * @throws ReflectionException
     */
    public function getName(): string;

    /**
     * @param string $name
     *
     * @return ModuleInterface
     */
    public function setName(string $name): ModuleInterface;

    /**
     * @return string
     * @throws ReflectionException
     */
    public function getPath(): string;

    /**
     * @param string $path
     *
     * @return ModuleInterface
     */
    public function setPath(string $path): ModuleInterface;

    /**
     * @return string
     */
    public function getBindings(): string;

    /**
     * @param string $bindings
     *
     * @return ModuleInterface
     */
    public function setBindings(string $bindings): ModuleInterface;

    /**
     * @return string
     */
    public function getProviders(): string;

    /**
     * @param string $providers
     *
     * @return ModuleInterface
     */
    public function setProviders(string $providers): ModuleInterface;

    /**
     * @return string
     */
    public function getConfig(): string;

    /**
     * @param string $config
     *
     * @return ModuleInterface
     */
    public function setConfig(string $config): ModuleInterface;

    /**
     * @return string
     */
    public function getSubscribers(): string;

    /**
     * @param string $subscribers
     *
     * @return ModuleInterface
     */
    public function setSubscribers(string $subscribers): ModuleInterface;

    /**
     * @return string
     */
    public function getRoutes(): string;

    /**
     * @param string $routes
     *
     * @return ModuleInterface
     */
    public function setRoutes(string $routes): ModuleInterface;

    /**
     * @return string
     * @throws ReflectionException
     */
    public function resolvePath();

    /**
     * @return mixed
     * @throws ReflectionException
     */
    public function create();
}