<?php

namespace Src;

class Cache
{
    private static $cacheDir = __DIR__ . '/../storage/cache/';

    /**
     * Store data in a cache file
     *
     * @param string $key
     * @param mixed $data
     * @param int $expiry Time in seconds (default: 1 hour)
     */
    public static function set($key, $data, $expiry = 3600)
    {
        self::ensureCacheDirectoryExists();

        $cacheFile = self::$cacheDir . md5($key) . '.cache';
        $cacheData = [
            'expiry' => time() + $expiry,
            'data' => $data
        ];

        file_put_contents($cacheFile, serialize($cacheData));
    }

    /**
     * Retrieve data from the cache
     *
     * @param string $key
     * @return mixed|null
     */
    public static function get($key)
    {
        $cacheFile = self::$cacheDir . md5($key) . '.cache';

        if (file_exists($cacheFile)) {
            $cacheData = unserialize(file_get_contents($cacheFile));

            if ($cacheData['expiry'] > time()) {
                return $cacheData['data'];
            }

            unlink($cacheFile); // Remove expired cache
        }

        return null;
    }

    /**
     * Delete a cache entry
     *
     * @param string $key
     */
    public static function delete($key)
    {
        $cacheFile = self::$cacheDir . md5($key) . '.cache';

        if (file_exists($cacheFile)) {
            unlink($cacheFile);
        }
    }

    /**
     * Ensure cache directory exists
     */
    private static function ensureCacheDirectoryExists()
    {
        if (!is_dir(self::$cacheDir)) {
            mkdir(self::$cacheDir, 0777, true);
        }
    }
}
