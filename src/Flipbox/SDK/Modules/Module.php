<?php

namespace Flipbox\SDK\Modules;

abstract class Module
{
    /**
     * Determine current locale.
     *
     * @param string $locale
     */
    protected function determineLocale(string $locale = '')
    {
        return $locale ?: $this->getLocaleFromSession();
    }

    /**
     * Get locale from session.
     *
     * @return string
     */
    protected function getLocaleFromSession(): ?string
    {
        return app('session')
            ->get(
                config('flipbox-sdk.locale.session'),
                config('app.locale', config('app.fallback_locale'))
            );
    }
}
