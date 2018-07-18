<?php
namespace Ttskch\ContactForm;

use Ttskch\ContactForm\Session\Csrf;
use Ttskch\ContactForm\Session\Errors;
use Ttskch\ContactForm\Session\Submissions;

class ContactForm
{
    const CSRF_INPUT_NAME = 'csrf_token';


    /**
     * @var Submissions
     */
    public $submissions;

    /**
     * @var Errors
     */
    public $errors;

    /**
     * @var SubmissionPresenter
     */
    public $presenter;


    /**
     * @var RequestContext
     */
    private $context;

    /**
     * @var Csrf
     */
    private $csrf;

    /**
     * @var Mailer
     */
    private $mailer;


    public function __construct(\Swift_Transport $transport = null)
    {
        $this->submissions = new Submissions(new UploadedFilesFixer());
        $this->errors = new Errors();
        $this->presenter = new SubmissionPresenter($this->submissions);

        $this->context = new RequestContext();
        $this->csrf = new Csrf();
        $this->mailer = new Mailer($this->submissions, $transport);

        session_start();

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

        $validator = new Validator($requiredKeys, $emailKeys);
        $validator->validate($this->submissions, $this->errors);

        if (count($this->errors->getAll()) === 0) {
            $this->redirectTo($redirectTo);
        } else {
            // redirect to self to clear POST data
            $this->redirectTo($_SERVER['SCRIPT_NAME']);
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
