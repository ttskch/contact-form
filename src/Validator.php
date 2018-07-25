<?php
namespace Ttskch\ContactForm;

use Ttskch\ContactForm\Session\Errors;
use Ttskch\ContactForm\Session\Submissions;

class Validator
{
    const EMAIL_REGEX = '/^[a-zA-Z0-9][a-zA-Z0-9\._-]*@[a-zA-Z0-9_-]+\.[a-zA-Z0-9\._-]*[a-zA-Z0-9]$/';

    const REQUIRED_MESSAGE = 'Require field';
    const EMAIL_MESSAGE = 'Incorrect email address';

    /**
     * @var array
     */
    private $requiredKeys;

    /**
     * @var array
     */
    private $emailKeys;

    public function __construct(array $requiredKeys, array $emailKeys)
    {
        $this->requiredKeys = $requiredKeys;
        $this->emailKeys = $emailKeys;
    }

    public function validate(Submissions $submissions, Errors $errors)
    {
        $errors->clear();

        foreach ($this->requiredKeys as $key) {
            if (!$submissions->has($key) || !$submissions->has("{$key}.name") || !$submissions->has("{$key}.name.0")) {
                $errors->set($key, self::REQUIRED_MESSAGE);
            }
        }

        foreach ($this->emailKeys as $key) {
            if ($errors->has($key)) {
                continue;
            }

            if (!preg_match(self::EMAIL_REGEX, (string)$submissions->get($key))) {
                $errors->set($key, self::EMAIL_MESSAGE);
            }
        }
    }
}
