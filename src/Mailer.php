<?php
namespace Ttskch\ContactForm;

use Ttskch\ContactForm\Session\Submissions;

class Mailer
{
    /**
     * @var Submissions
     */
    private $submissions;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    public function __construct(Submissions $submittions, \Swift_Transport $transport = null)
    {
        $this->submissions = $submittions;
        $this->mailer = new \Swift_Mailer($transport ?: new \Swift_SendmailTransport());
    }

    public function send($message)
    {
        return $this->mailer->send($message);
    }

    public function buildMessage($to, $from, $fromName, $subject, $body, array $attachingSubmissionKeys = [])
    {
        $message = new \Swift_Message();
        $message
            ->setTo($to)
            ->setFrom([$from => $fromName])
            ->setSubject($subject)
            ->setBody($body)
        ;

        foreach ($attachingSubmissionKeys as $key) {
            if (is_array($names = $this->submissions->get("{$key}.name"))) {
                // multiple files for one submission key

                // no files uploaded
                if (!$names[0]) {
                    continue;
                }

                foreach ($names as $i => $name) {
                    $path = (string)$this->submissions->get("{$key}.tmp_name.{$i}");
                    $type = (string)$this->submissions->get("{$key}.type.{$i}");

                    $message->attach(\Swift_Attachment::fromPath($path, $type)->setFilename($name));
                }
            } else {
                // single file for one submission key

                // no files uploaded
                if (!($name = $names)) {
                    continue;
                }

                $path = (string)$this->submissions->get("{$key}.tmp_name");
                $type = (string)$this->submissions->get("{$key}.type");

                $message->attach(\Swift_Attachment::fromPath($path, $type)->setFilename((string)$name));
            }
        }

        return $message;
    }
}
