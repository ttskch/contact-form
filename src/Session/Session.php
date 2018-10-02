<?php
namespace Ttskch\ContactForm\Session;

use Cake\Utility\Hash;

class Session
{
    private $baseKey;

    public function __construct($baseKey = null)
    {
        $this->baseKey = $baseKey ?: 'ttskch-contact-form-' . sha1(uniqid(mt_rand(), true));

        // todo: I have to initialize actual $_SESSION with [$this->baseKey => []] but it makes this class untestable :(
    }

    public function get($dotSeparatedKeys)
    {
        return Hash::get($_SESSION[$this->baseKey], $dotSeparatedKeys);
    }

    public function set($dotSeparatedKeys, $value)
    {
        $_SESSION[$this->baseKey] = array_replace_recursive($_SESSION[$this->baseKey], Hash::expand([$dotSeparatedKeys => $value]));

        return $value;
    }

    public function has($dotSeparatedKeys)
    {
        return (boolean)$this->get($dotSeparatedKeys);
    }

    public function clear()
    {
        $_SESSION[$this->baseKey] = [];
    }

    public function getBaseKey()
    {
        return $this->baseKey;
    }
}
