<?php

namespace Src;

class Session
{
    /**
     * Start the session if not already started
     */
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Set a flash message
     *
     * @param string $key
     * @param string $message
     */
    public static function flash($key, $message)
    {
        self::start();
        $_SESSION['flash'][$key] = $message;
    }

    /**
     * Get a flash message (only available for the next request)
     *
     * @param string $key
     * @return string|null
     */
    public static function getFlash($key)
    {
        self::start();

        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]); // Remove after accessing
            return $message;
        }

        return null;
    }
}
