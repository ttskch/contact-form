<?php
namespace Ttskch\ContactForm\Session;

use Ttskch\ContactForm\UploadedFilesFixer;

class Submissions
{
    const SESSION_KEY = 'ttskch-contact-form-data';

    /**
     * @var UploadedFilesFixer
     */
    private $fixer;

    public function __construct(UploadedFilesFixer $fixer)
    {
        $this->fixer = $fixer;
    }

    /**
     * for testability
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    public function initialize()
    {
        $this->fixer->fix();

        $_SESSION[self::SESSION_KEY]['$_POST'] = array_merge(@$_SESSION[self::SESSION_KEY]['$_POST'] ?: [], @$_POST ?: []);
        $_SESSION[self::SESSION_KEY]['$_FILES'] = array_merge(@$_SESSION[self::SESSION_KEY]['$_FILES'] ?: [], @$_FILES ?: []);
    }

    public function get($dotSeparatedKeys)
    {
        $keys = explode('.', $dotSeparatedKeys);

        $value = array_merge(@$_SESSION[self::SESSION_KEY]['$_POST'] ?: [], @$_SESSION[self::SESSION_KEY]['$_FILES'] ?: []);

        foreach ($keys as $key) {
            if (!isset($value[$key])) {
                return null;
            }

            $value = $value[$key];
        }

        return $value;
    }

    public function has($dotSeparatedKeys)
    {
        return (boolean)$this->get($dotSeparatedKeys);
    }

    public function isEmpty()
    {
        return !isset($_SESSION[self::SESSION_KEY]) || (!$_SESSION[self::SESSION_KEY]['$_POST'] && !$_SESSION[self::SESSION_KEY]['$_FILES']);
    }

    public function clear()
    {
        unset($_SESSION[self::SESSION_KEY]);
    }
}
