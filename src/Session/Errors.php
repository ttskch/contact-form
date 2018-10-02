<?php
namespace Ttskch\ContactForm\Session;

class Errors
{
    const SESSION_KEY = 'error';

    /**
     * @var Session
     */
    private $session;

    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    public function set($key, $message)
    {
        return $this->session->set(sprintf('%s.%s', self::SESSION_KEY, $key), $message);
    }

    public function get($key)
    {
        return $this->session->get(sprintf('%s.%s', self::SESSION_KEY, $key));
    }

    public function getAll()
    {
        return $this->session->get(self::SESSION_KEY);
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
