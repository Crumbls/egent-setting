<?php

namespace Egent\Setting\Facades;

use Egent\Setting\Registrar\SettingRegistrar;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \Illuminate\Support\Collection|\Egent\Setting\Models\Setting[] getSettings()
 * @method static \Egent\Setting\Registrar\Declaration name(string $name)
 */
class Setting extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return SettingRegistrar::class;
    }
}