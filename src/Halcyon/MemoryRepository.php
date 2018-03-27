<?php namespace October\Rain\Halcyon;

use Illuminate\Cache\Repository;

/**
 * Provides a simple request-level cache.
 *
 * @package october\halcyon
 * @author Alexey Bobkov, Samuel Georges
 */
class MemoryRepository extends Repository
{
    /**
     * Values stored in memory
     *
     * @var array
     */
    protected $cache = [];

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string|array $key
     * @return mixed
     */
    public function get($key, $default = null)
    {
        if(is_array($key)) {
            return $this->many($key);
        }

        if(isset($this->cache[$key])) {
            return $this->cache[$key];
        }

        return $this->cache[$key] = parent::get($key, $default);
    }

    /**
     * Store an item in the cache.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  \DateTimeInterface|\DateInterval|float|int  $minutes
     * @return void
     */
    public function put($key, $value, $minutes = null)
    {
        if (is_array($key)) {
            $this->putMany($key, $value);
        }

        if (!is_null($minutes = $this->getMinutes($minutes))) {
            $this->cache[$key] = $value;
            parent::put($key, $value, $minutes);
        }
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return int|bool
     */
    public function increment($key, $value = 1)
    {
        $this->cache[$key] = isset($this->cache[$key]) ? $this->cache[$key] + $value : $value;
        return parent::increment($key, $value);
    }

    /**
     * Decrement the value of an item in the cache.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return int|bool
     */
    public function decrement($key, $value = 1)
    {
        $this->cache[$key] = isset($this->cache[$key]) ? $this->cache[$key] - $value : -$value;
        return parent::decrement($key, $value);
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function forever($key, $value)
    {
        $this->cache[$key] = $value;
        parent::forever($key, $value); // TODO: Change the autogenerated stub
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function forget($key)
    {
        unset($this->cache[$key]);
        return parent::forget($key); // TODO: Change the autogenerated stub
    }


    /**
     * Remove all items from the cache.
     *
     * @return bool
     */
    public function flush()
    {
        $this->flushInternalCache();
        parent::flush();
    }

    public function flushInternalCache()
    {
        $this->cache = [];
    }

    /**
     * Get the cache key prefix.
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->store->getPrefix();
    }
}
