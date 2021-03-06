<?php

namespace Egent\Setting\Collections;

use Egent\Setting\Exceptions\SettingDoesNotExist;
use Egent\Setting\Models\Metadata;
use Egent\Setting\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Enumerable;
use Illuminate\Support\Traits\EnumeratesValues;
use RuntimeException;

/**
 * Class SettingsCollection
 *
 * @package Egent\Setting
 *
 * @method Setting get(string $name, mixed $default = null)
 */
class SettingsCollection extends Collection
{
    use EnumeratesValues {
        __get as __dynamicGet;
    }

	public $owner = null;


    /**
     * The cache helper instance.
     *
     * We will set it here since we need to keep an eye once this object instance
     * is garbage collected. Once done, a `__destruct()` call will be fired, and
     * that is when we will make the cache store regenerate the settings there.
     *
     * @var \Egent\Setting\SettingsCache|null
     */
    public ?SettingsCache $cache = null;

    /**
     * If the settings should be regenerated on exit.
     *
     * @var bool
     */
    public bool $regeneratesOnExit = false;

    /**
     * Returns all the settings grouped by their group name.
     *
     * @return static|\Egent\Setting\Models\Setting[]
     */
    public function groups(): static
    {
        return $this->groupBy('group');
    }

    /**
     * Returns the value of a setting.
     *
     * @param  string  $name
     * @param  mixed|null  $default
     *
     * @return \Illuminate\Support\Carbon|\Illuminate\Support\Collection|array|string|int|float|bool|null
     */
    public function value(string $name, mixed $default = null): Carbon|Collection|array|string|int|float|bool|null
    {
        $setting = $this->get($name, $default);

        if ($setting instanceof Models\Setting) {
            return $setting->value;
        }

        return $setting;
    }

    /**
     * Checks if the value of a setting is the same as the one issued.
     *
     * @param  string  $name
     * @param  mixed  $value
     *
     * @return bool
     */
    public function is(string $name, mixed $value): bool
    {
        return $this->value($name) === $value;
    }

    /**
     * Sets one or multiple setting values.
     *
     * @param  string|array  $name
     * @param  mixed  $value
     * @param  bool  $force
     *
     * @return void
     */
    public function set(string|array $name, mixed $value = null, bool $force = true): void
    {
        // If the name is not an array, we will make it one to iterate over.
        if (is_string($name)) {
            $name = [$name => $value];
        }

        foreach ($name as $key => $setting) {
			$this->invalidate();
//			print_r(\DB::getQueryLog());
//			print_r(get_class_methods($this));
//			dd($this);
            if (!$instance = $this->get($key)) {
				if (!$force) {
					throw new RuntimeException("The setting [$key] doesn't exist.");
				} else if (!$this->owner) {
					throw new RuntimeException("The setting [$key] doesn't exist. Owner is not configured, so unable to generate");
				}
				$metadata = Metadata::where('name',$key)->take(1)->first();
				/*
				$setting = Metadata::firstOrNew([
					'name' => $key,
				]);
				if (!$setting->exists) {
					$setting->name = $key;
					$setting->group = 'default';
					$setting->bag = 'default';
					$setting->type = Metadata::TYPE_STRING;
					$setting->is_enabled = 1;
					$setting->save();
				}
				*/
	            if (!$metadata) {
					throw new SettingDoesNotExist($key);
	            }

	            /**
	             * Add setting.
	             */
				$setting = new Setting();
				$setting->metadata()->associate($metadata);
				$setting->user()->save($this->owner);
				$setting->value = $metadata->default;
				$setting->is_enabled = $metadata->is_enabled;
				$setting->saveQuietly();

				$this->put($key, $setting);
			}

            $instance->set($setting, $force);
        }
    }

    /**
     * Sets the default value of a given setting.
     *
     * @param  string  $name
     *
     * @return void
     */
    public function setDefault(string $name): void
    {
        $this->get($name)->setDefault();
    }

    /**
     * Checks if the setting is using a null value.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function isNull(string $name): bool
    {
        return null === $this->value($name);
    }

    /**
     * Checks if the Setting is enabled.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function isEnabled(string $name): bool
    {
        return $this->get($name)->is_enabled === true;
    }

    /**
     * Checks if the Setting is disabled.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function isDisabled(string $name): bool
    {
        return ! $this->isEnabled($name);
    }

    /**
     * Disables a Setting.
     *
     * @param  string  $name
     *
     * @return void
     */
    public function disable(string $name): void
    {
        $this->get($name)->disable();
    }

    /**
     * Enables a Setting.
     *
     * @param  string  $name
     *
     * @return void
     */
    public function enable(string $name): void
    {
        $this->get($name)->enable();
    }

    /**
     * Sets a value into a setting if it exists and it's enabled.
     *
     * @param  string|array  $name
     * @param  mixed  $value
     *
     * @return void
     */
    public function setIfEnabled(string|array $name, mixed $value = null): void
    {
        $this->set($name, $value, false);
    }

    /**
     * Returns only the models from the collection with the specified keys.
     *
     * @param  mixed  $keys
     * @return static
     */
    public function only($keys): static
    {
        if (is_null($keys)) {
            return new static($this->items);
        }

        if ($keys instanceof Enumerable) {
            $keys = $keys->all();
        }

        $keys = is_array($keys) ? $keys : func_get_args();

        $settings = new static(Arr::only($this->items, Arr::wrap($keys)));

        if ($settings->isNotEmpty()) {
            return $settings;
        }

        return parent::only($keys);
    }

    /**
     * Returns all models in the collection except the models with specified keys.
     *
     * @param  mixed  $keys
     * @return static
     */
    public function except($keys): static
    {
        if ($keys instanceof Enumerable) {
            $keys = $keys->all();
        } elseif (! is_array($keys)) {
            $keys = func_get_args();
        }

        $settings = new static(Arr::except($this->items, $keys));

        if ($settings->isNotEmpty()) {
            return $settings;
        }

        return parent::except($keys);
    }

    /**
     * Invalidates the cache of the setting's user.
     *
     * @return void
     */
    public function invalidate(): void
    {
        $this->cache?->invalidate();
    }

    /**
     * Invalidate the settings cache if it has not been done before.
     *
     * @return void
     */
    public function invalidateIfNotInvalidated(): void
    {
        $this->cache?->invalidateIfNotInvalidated();
    }

    /**
     * Saves the collection of settings in the cache.
     *
     * @param  bool  $force
     *
     * @return void
     */
    public function regenerate(bool $force = false): void
    {
        $this->cache?->regenerate($force);
    }

    /**
     * Handle the destruction of the settings collection.
     *
     * @return void
     */
    public function __destruct()
    {
        if ($this->regeneratesOnExit) {
            $this->cache?->setSettings($this)->regenerate();
        }
    }

    /**
     * Dynamically sets a value.
     *
     * @param  string  $name
     * @param  mixed $value
     */
    public function __set(string $name, mixed $value): void
    {
        $this->set($name, $value);
    }

    /**
     * Check if a given property exists.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return $this->has($name);
    }

    /**
     * Dynamically access collection proxies.
     *
     * @param  string  $key
     * @return mixed
     *
     * @throws \Exception
     */
    public function __get($key): mixed
    {
        if ($setting = $this->get($key)) {
            return $setting->getAttribute('value');
        }

        return $this->__dynamicget($key);
    }

	/**
	 * Set an array item to a given value using "dot" notation.
	 *
	 * If no key is given to the method, the entire array will be replaced.
	 *
	 * @param array  $array
	 * @param string $key
	 * @param mixed  $value
	 *
	 * @return array
	 */
	public function dotString(string $key) : string
	{
		return implode('.', array_filter(preg_split('/[\[|\]]/', $key)));
	}

	/**
	 * Get an item from the collection by key.
	 *
	 * @param  mixed  $key
	 * @param  mixed  $default
	 * @return mixed
	 */
	public function get($key, $default = null)
	{
		if (array_key_exists($key, $this->items)) {
			return $this->items[$key];
		}
		// Convert to a dot syntax to see if it exists.  This is the normal format.
		$temp = $this->dotString($key);
		if (array_key_exists($temp, $this->items)) {
			return $this->items[$temp];
		}

		return value($default);
	}
}
