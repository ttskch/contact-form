<?php
namespace Ttskch\ContactForm\ValueObject;

class ValidationMessages
{
    private $requiredMessage;
    private $emailMessage;

    public function __construct($requiredMessage, $emailMessage)
    {
        $this->requiredMessage = $requiredMessage;
        $this->emailMessage = $emailMessage;
    }

    public function getRequiredMessage()
    {
        return $this->requiredMessage;
    }

    public function getEmailMessage()
    {
        return $this->emailMessage;
    }
}
