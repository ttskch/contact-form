<?php
namespace Ttskch\ContactForm;

use Ttskch\ContactForm\Session\Csrf;
use Ttskch\ContactForm\Session\Errors;
use Ttskch\ContactForm\Session\Session;
use Ttskch\ContactForm\Session\Submissions;
use Ttskch\ContactForm\ValueObject\ValidationMessages;

class ContactForm
{
    const CSRF_INPUT_NAME = 'csrf_token';


    /**
     * @var Submissions
     */
    private $submissions;

    /**
     * @var Errors
     */
    private $errors;

    /**
     * @var SubmissionPresenter
     */
    private $presenter;

    /**
     * @var Mailer
     */
    private $mailer;

    /**
     * @var RequestContext
     */
    private $context;

    /**
     * @var Csrf
     */
    private $csrf;

    /**
     * @var ValidationMessages
     */
    private $validationMessages;


    public function __construct(ValidationMessages $validationMessages = null, \Swift_Transport $transport = null)
    {
        $session = new Session();
        $this->submissions = new Submissions($session, new UploadedFilesFixer());
        $this->errors = new Errors($session);
        $this->presenter = new SubmissionPresenter($this->submissions);
        $this->mailer = new Mailer($this->submissions, $transport);
        $this->context = new RequestContext();
        $this->csrf = new Csrf($session);
        $this->validationMessages = $validationMessages;

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $this->submissions->initialize();
    }

    public function rejectAccessWithoutSubmissions($redirectTo = '/')
    {
        if ($this->submissions->isEmpty()) {
            $this->redirectTo($redirectTo);
        }
    }

    public function validateAndRedirectAfterSelfPosted($redirectTo, array $requiredKeys = [], array $emailKeys = [])
    {
        if (!$this->context->isPost()) {
            return;
        }

        $this->csrf->validate($this->submissions->get(self::CSRF_INPUT_NAME));

        $validator = new Validator($requiredKeys, $emailKeys, $this->validationMessages);
        $validator->validate($this->submissions, $this->errors);

        if (count($this->errors->getAll()) === 0) {
            $this->redirectTo($redirectTo);
        } else {
            // redirect to self to clear POST data
            $this->redirectTo($_SERVER['REQUEST_URI']);
        }
    }

    public function csrfHiddenInput()
    {
        return sprintf('<input type="hidden" name="%s" value="%s">', self::CSRF_INPUT_NAME, $this->csrf->generateToken());
    }

    public function present($dotSeparatedKeys, $sanitize = true, $nl2br = false, $format = '%s', $glue = ', ')
    {
        return $this->presenter->present($dotSeparatedKeys, $sanitize, $nl2br, $format, $glue);
    }

    public function presentSelected($dotSeparatedKeys, $value, $default = false)
    {
        return $this->presenter->selected($dotSeparatedKeys, $value, $default);
    }

    public function presentChecked($dotSeparatedKeys, $needle, $default = false)
    {
        return $this->presenter->checked($dotSeparatedKeys, $needle, $default);
    }

    public function presentError($key)
    {
        return $this->errors->get($key);
    }

    public function hasError($key)
    {
        return $this->errors->has($key);
    }

    public function sendEmail($to, $from, $fromName, $subject, $body, $attachingSubmissionKeys = [])
    {
        $message = $this->mailer->buildMessage($to, $from, $fromName, $subject, $body, $attachingSubmissionKeys);

        $this->mailer->send($message);
    }

    public function clearSubmissions()
    {
        $this->submissions->clear();
    }


    private function redirectTo($path)
    {
        header("Location: {$path}");
        exit;
    }
}
