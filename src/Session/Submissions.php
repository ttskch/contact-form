<?php
namespace Ttskch\ContactForm\Session;

use Ttskch\ContactForm\UploadedFilesFixer;

class Submissions
{
    const SESSION_KEY = 'data';

    /**
     * @var Session
     */
    private $session;

    /**
     * @var UploadedFilesFixer
     */
    private $fixer;

    public function __construct(Session $session, UploadedFilesFixer $fixer)
    {
        $this->session = $session;
        $this->fixer = $fixer;
    }

    /**
     * for testability
     */
    public function setSession(Session $session)
    {
        $this->session = $session;
    }

    public function initialize()
    {
        $this->fixer->fix();

        $this->session->set(self::SESSION_KEY, [
            '$_POST' => array_merge($this->session->get(self::SESSION_KEY)['$_POST'] ?: [], @$_POST ?: []),
            '$_FILES' => array_merge($this->session->get(self::SESSION_KEY)['$_FILES'] ?: [], @$_FILES ?: []),
        ]);
    }

    public function get($dotSeparatedKeys)
    {
        $keys = explode('.', $dotSeparatedKeys);

        $value = array_merge(@$this->session->get(self::SESSION_KEY)['$_POST'] ?: [], @$this->session->get(self::SESSION_KEY)['$_FILES'] ?: []);

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
        return $this->session->has(self::SESSION_KEY) || (!@$this->session->get(self::SESSION_KEY)['$_POST'] && !@$this->session->get(self::SESSION_KEY)['$_FILES']);
    }

    public function clear()
    {
        $this->session->clear(self::SESSION_KEY);
    }
}
