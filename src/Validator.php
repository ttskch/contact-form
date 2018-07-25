<?php
namespace Ttskch\ContactForm;

use Ttskch\ContactForm\Session\Errors;
use Ttskch\ContactForm\Session\Submissions;
use Ttskch\ContactForm\ValueObject\ValidationMessages;

class Validator
{
    const EMAIL_REGEX = '/^[a-zA-Z0-9][a-zA-Z0-9\._-]*@[a-zA-Z0-9_-]+\.[a-zA-Z0-9\._-]*[a-zA-Z0-9]$/';

    const DEFAULT_REQUIRED_MESSAGE = 'Require field';
    const DEFAULT_EMAIL_MESSAGE = 'Incorrect email address';

    /**
     * @var array
     */
    private $requiredKeys;

    /**
     * @var array
     */
    private $emailKeys;

    /**
     * @var ValidationMessages
     */
    private $messages;

    public function __construct(array $requiredKeys, array $emailKeys, ValidationMessages $messages = null)
    {
        $this->requiredKeys = $requiredKeys;
        $this->emailKeys = $emailKeys;
        $this->messages = @$messages ?: new ValidationMessages(self::DEFAULT_REQUIRED_MESSAGE, self::DEFAULT_EMAIL_MESSAGE);
    }

    public function validate(Submissions $submissions, Errors $errors)
    {
        $errors->clear();

        foreach ($this->requiredKeys as $key) {

            // for file inputs
            $missFileUploaded = !is_null($submissions->get("{$key}.tmp_name")) && !$submissions->has("{$key}.name");
            $missFileUploaded = $missFileUploaded || !is_null($submissions->get("{$key}.tmp_name.0")) && !$submissions->has("{$key}.name.0");

            if (!$submissions->has($key) || $missFileUploaded) {
                $errors->set($key, $this->messages->getRequiredMessage());
            }
        }

        foreach ($this->emailKeys as $key) {
            if ($errors->has($key)) {
                continue;
            }

            if (!preg_match(self::EMAIL_REGEX, (string)$submissions->get($key))) {
                $errors->set($key, $this->messages->getEmailMessage());
            }
        }
    }
}
