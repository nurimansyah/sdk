<?php

namespace Flipbox\SDK\Modules\Translation\Drivers;

use Illuminate\Support\Arr;
use InvalidArgumentException;
use Illuminate\Database\Eloquent\Builder;
use Flipbox\SDK\Modules\Translation\Contracts\TranslationDriver;
use Flipbox\SDK\Modules\Translation\Models\Translation as TranslationModel;

class Eloquent implements TranslationDriver
{
    use Concerns\SplitKey,
        Concerns\HasCache;

    /**
     * {@inheritdoc}
     */
    public function translate(string $key, string $locale, $default = null)
    {
        if (!$key) {
            throw new InvalidArgumentException('Argument locale cannot be empty.');
        }

        if (!$locale) {
            throw new InvalidArgumentException('Argument locale cannot be empty.');
        }

        if ($this->hasTranslated($key, $locale)) {
            return $this->getTranslated($key, $locale);
        }

        [$group, $normalizedKey] = $this->splitKey($key);

        if (!$normalizedKey || !$group) {
            return $default;
        }

        $result = $this->fetch($locale, $group, $normalizedKey) ?: $default;

        $this->setTranslated($key, $locale, $result);

        return $result;
    }

    /**
     * Fetch translation from database.
     *
     * @param string $locale
     * @param string $group
     * @param string $key
     *
     * @return string|array
     */
    protected function fetch(string $locale, string $group, string $key)
    {
        $query = TranslationModel::where('locale', '=', $locale)
            ->where('group', '=', $group)
            ->where('key', 'like', "$key%");

        $translations = $this->executeQuery($query);

        // No result
        if (0 === count($translations)) {
            return null;
        }

        // Return array
        if (count($translations) > 1) {
            $normalized = [];

            foreach ($translations as $accessor => $value) {
                $accessor = preg_replace("/^{$key}\./", '', $accessor);
                Arr::set($normalized, $accessor, $value);
            }

            return $normalized;
        }

        // Single translation
        return Arr::first(array_values($translations));
    }

    /**
     * Execute a query.
     *
     * @param Builder $query
     *
     * @return array
     */
    protected function executeQuery(Builder $query): array
    {
        $result = $query->get()
            ->pluck('value', 'key')
            ->toArray();

        return $result;
    }
}
