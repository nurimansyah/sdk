<?php

namespace Flipbox\SDK;

use Exception;
use ArrayAccess;
use InvalidArgumentException;
use Flipbox\SDK\Contracts\Module;

class Factory implements ArrayAccess
{
    /**
     * Early binded modules.
     *
     * @var array
     */
    protected static $availableModules = [
        'translation' => Modules\Translation\Module::class,
        'menu' => Modules\Menu\Module::class,
        'banner' => Modules\Banner\Module::class,
    ];

    /**
     * Resolved module.
     *
     * @var array
     */
    protected static $resolvedModules = [];

    /**
     * Add module to the factory.
     *
     * @param string|Module $module
     *
     * @return Factory
     */
    public function addModule($module, string $moduleName): self
    {
        if (is_string($module)) {
            static::$availableModules[$moduleName] = $module;
        } elseif ($module instanceof Module) {
            static::$availableModules[$moduleName] = get_class($module);
            static::$resolvedModules[$moduleName] = $module;
        } else {
            throw new InvalidArgumentException(
                'Module is not a valid class name or not an instance of: ['.Module::class.'].'
            );
        }

        return $this;
    }

    /**
     * Add multiple modules to the factory.
     *
     * @param array $modules
     *
     * @return Factory
     */
    public function addModules(array $modules): self
    {
        foreach ($modules as $key => $module) {
            $this->addModule($module, $key);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($moduleName)
    {
        return $this->moduleHasRegistered($moduleName);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($moduleName)
    {
        return $this->resolve($moduleName);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($moduleName, $module)
    {
        $this->addModule($module, $moduleName);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($moduleName)
    {
        // no-op
    }

    /**
     * Resolve a module.
     *
     * @param string $moduleName
     *
     * @return Module
     */
    public function resolve(string $moduleName): Module
    {
        if ($this->moduleHasResolved($moduleName)) {
            return static::$resolvedModules[$moduleName];
        }

        if ($this->moduleHasntRegistered($moduleName)) {
            throw new InvalidArgumentException("Module [{$moduleName}] has not registered.");
        }

        $moduleFqcn = static::$availableModules[$moduleName];

        $module = $this->createModule($moduleFqcn);

        return static::$resolvedModules[$moduleName] = $module;
    }

    /**
     * Create module.
     *
     * @param string $moduleFqcn
     *
     * @return Module
     */
    protected function createModule(string $moduleFqcn): Module
    {
        $module = app($moduleFqcn);

        if (!$module instanceof Module) {
            throw new Exception("Module [{$moduleFqcn}] is not an instance of: [".Module::class.'].');
        }

        return $module;
    }

    /**
     * Check whether module has registered.
     *
     * @param string $moduleName
     *
     * @return bool
     */
    protected function moduleHasRegistered(string $moduleName): bool
    {
        return array_key_exists($moduleName, static::$availableModules);
    }

    /**
     * Check whether module has not registered.
     *
     * @param string $moduleName
     *
     * @return bool
     */
    protected function moduleHasntRegistered(string $moduleName): bool
    {
        return !$this->moduleHasRegistered($moduleName);
    }

    /**
     * Check whether module has resolved.
     *
     * @param string $moduleName
     *
     * @return bool
     */
    protected function moduleHasResolved(string $moduleName): bool
    {
        return array_key_exists($moduleName, static::$resolvedModules);
    }

    /**
     * Check whether module has not resolved.
     *
     * @param string $moduleName
     *
     * @return bool
     */
    protected function moduleHasntResolved(string $moduleName): bool
    {
        return !$this->moduleHasResolved($moduleName);
    }

    /**
     * Undocumented function.
     *
     * @param string        $moduleName
     * @param string|Module $module
     */
    public function __set($moduleName, $module)
    {
        $this->addModule($module, $moduleName);
    }

    /**
     * Undocumented function.
     *
     * @param string $moduleName
     *
     * @return Module
     */
    public function __get($moduleName)
    {
        return $this->resolve($moduleName);
    }

    /**
     * Undocumented function.
     *
     * @param string $moduleName
     *
     * @return bool
     */
    public function __isset($moduleName)
    {
        return $this->moduleHasRegistered($moduleName);
    }

    /**
     * Undocumented function.
     *
     * @param string $moduleName
     */
    public function __unset($moduleName)
    {
        // no-op
    }

    /**
     * Undocumented function.
     *
     * @param string $moduleName
     * @param array  $arguments
     *
     * @return Module
     */
    public function __call(string $moduleName, array $arguments = []): Module
    {
        return $this->resolve($moduleName);
    }
}
