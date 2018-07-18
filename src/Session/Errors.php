<?php
namespace Ttskch\ContactForm\Session;

class Errors
{
    const SESSION_KEY = 'ttskch-contact-form-error';

    public function set($key, $message)
    {
        return $_SESSION[self::SESSION_KEY][$key] = $message;
    }

    public function get($key)
    {
        return @$_SESSION[self::SESSION_KEY][$key] ?: null;
    }

    public function getAll()
    {
        return @$_SESSION[self::SESSION_KEY] ?: [];
    }

    public function has($key)
    {
        return (boolean)$this->get($key);
    }

    public function clear()
    {
        unset($_SESSION[self::SESSION_KEY]);
    }
}
