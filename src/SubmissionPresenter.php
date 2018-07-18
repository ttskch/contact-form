<?php
namespace Ttskch\ContactForm;

use Ttskch\ContactForm\Session\Submissions;

class SubmissionPresenter
{
    /**
     * @var Submissions
     */
    private $submissions;

    public function __construct(Submissions $submissions)
    {
        $this->submissions = $submissions;
    }

    public function present($dotSeparatedKeys, $sanitize = true, $nl2br = false, $format = '%s', $glue = ', ')
    {
        $values = (array)$this->submissions->get($dotSeparatedKeys);

        foreach ($values as $i => $value) {
            $values[$i] = sprintf($format, $sanitize ? $this->sanitize($value) : $value);
        }

        $string = implode($glue, $values);

        if ($nl2br) {
            $string = $this->nl2br($string);
        }

        return $string;
    }

    public function selected($dotSeparatedKeys, $value, $default = false)
    {
        $submission = $this->submissions->get($dotSeparatedKeys);

        if ((!$submission && $default) || $submission === $value) {
            return 'selected';
        }

        return '';
    }

    public function checked($dotSeparatedKeys, $needle, $default = false)
    {
        $submission = (array)$this->submissions->get($dotSeparatedKeys);

        if ((!$submission && $default) || in_array($needle, $submission)) {
            return 'checked';
        }

        return '';
    }

    private function sanitize($value)
    {
        return htmlspecialchars($value, ENT_QUOTES);
    }

    private function nl2br($value)
    {
        return preg_replace('/\n/', '<br>', $value);
    }
}
