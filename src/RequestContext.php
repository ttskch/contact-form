<?php
namespace Ttskch\ContactForm;

class RequestContext
{
    public function isGet()
    {
        return $this->getMethod() === 'get';
    }

    public function isPost()
    {
        return $this->getMethod() === 'post';
    }

    private function getMethod()
    {
        return strtolower($_SERVER['REQUEST_METHOD']);
    }
}
