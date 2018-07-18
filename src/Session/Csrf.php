<?php
namespace Ttskch\ContactForm\Session;

class Csrf
{
    const SESSION_KEY = 'ttskch-contact-form-csrf';

    public function generateToken()
    {
        return $_SESSION[self::SESSION_KEY] = sha1(uniqid(mt_rand(), true));
    }

    public function getToken()
    {
        return @$_SESSION[self::SESSION_KEY] ?: null;
    }

    public function validate($token)
    {
        if (!isset($token) || is_null($this->getToken()) || $this->getToken() !== $token) {
            $this->exitWithError('Invalid access!');
        }
    }

    private function exitWithError($message)
    {
        // macOS Safari shows a document which isn't specified charset with sjis somehow regardless of browser's encoding config (as of 2016.04.07)
        // so specify charset
        echo <<<EOS
<!doctype html>
<html>
<head>
<meta charset="utf-8">
</head>
<body>{$message}</body>
</html>
EOS;
        exit;
    }
}
