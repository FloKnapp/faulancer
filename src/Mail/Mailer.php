<?php
/**
 * Class Mailer | Mailer.php
 * @package Faulancer\Mail
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Mail;

/**
 * Class Mailer
 *
 * +--Example usage:
 * |
 * | $mail = new Mailer();
 * | $mail->addTo('test@test.com');
 * | $mail->addCc('cc@test.com');
 * | $mail->addBcc('bcc@test.com');
 * | $mail->setFrom('Test User <testuser@test.com>');
 * | $mail->setSubject('Testsubject');
 * | $mail->addAttachment('shownFileName.png', '/absolute/path/to/file.png');
 * | $mail->addAttachment('logo.png', '/absolute/path/to/logo.png', true); // We want to show it inline
 * | $mail->setContent('<img src="cid:logo.png"><br /><h3>Welcome to our newsletter</h3>... ... ...');
 * | $mail->send(); // Html is on per default; give this method 'false' to send in plain text
 * |
 */
class Mailer
{

    /**
     * @var array
     */
    protected $recipients = [];

    /**
     * @var array
     */
    protected $carbonCopies = [];

    /**
     * @var array
     */
    protected $blindCarbonCopies = [];

    /**
     * @var string
     */
    protected $from = '';

    /**
     * @var string
     */
    protected $replyTo = '';

    /**
     * @var string
     */
    protected $subject = '';

    /**
     * @var string
     */
    protected $content = '';

    /**
     * @var bool
     */
    protected $isHtml = false;

    /**
     * @var array
     */
    protected $attachment = [];

    /**
     * @var string
     */
    protected $boundary = '';

    /**
     * @param string $name
     */
    public function addTo(string $name)
    {
        $this->recipients[] = $name;
    }

    /**
     * @param string $name
     */
    public function addCc(string $name)
    {
        $this->carbonCopies[] = $name;
    }

    /**
     * @param string $name
     */
    public function addBcc(string $name)
    {
        $this->blindCarbonCopies[] = $name;
    }

    /**
     * @param string $name
     */
    public function setFrom(string $name)
    {
        $this->from = $name;
    }

    public function setReplyTo(string $name)
    {
        $this->replyTo = $name;
    }

    /**
     * @param string $subject
     */
    public function setSubject(string $subject)
    {
        $this->subject = $subject;
    }

    public function setContent(string $content)
    {
        $this->content = $content;
    }

    /**
     * @param string $name
     * @param string $path
     * @param bool   $inline
     */
    public function addAttachment(string $name = '', string $path, bool $inline = false)
    {
        $this->attachment[] = [
            'name'     => $name,
            'path'     => $path,
            'encoded'  => chunk_split(base64_encode(file_get_contents($path))),
            'mimetype' => mime_content_type($path),
            'inline'   => $inline
        ];
    }

    /**
     * @param bool $html
     * @return bool
     */
    public function send($html = true)
    {
        $this->isHtml = $html;
        return $this->sendMail($this->getHeaders(), $this->getMessage());
    }

    /**
     * @param string $headers
     * @param string $message
     * @return bool
     * @codeCoverageIgnore
     */
    protected function sendMail($headers, $message)
    {
        if (mail(implode(',', $this->recipients), $this->subject,$headers, $message)) {
            return true;
        }
        return false;
    }

    /**
     * @return string
     */
    protected function getBoundary()
    {
        if (empty($this->boundary)) {
            $this->boundary = md5('faulancer');
        }
        return $this->boundary;
    }

    /**
     * @return string
     */
    protected function getHeaders()
    {
        $boundary = $this->getBoundary();
        $headers  = 'MIME-Version: 1.0' . PHP_EOL;

        if (!empty($this->from)) {
            $headers .= 'From: ' .$this->from . PHP_EOL;
        }

        if (!empty($this->replyTo)) {
            $headers .= 'Reply-To: ' . $this->replyTo . PHP_EOL;
        } else if (!empty($this->from)) {
            $headers .= 'Reply-To: ' . $this->from . PHP_EOL;
        }

        if (!empty($this->carbonCopies)) {
            $headers .= 'CC: ' . implode(',', $this->carbonCopies) . PHP_EOL;
        }

        if (!empty($this->blindCarbonCopies)) {
            $headers .= 'BCC: ' . implode(',', $this->blindCarbonCopies) . PHP_EOL;
        }

        $headers .= 'Content-Base: multipart/mixed; boundary = ' . $boundary . PHP_EOL;

        return $headers;
    }

    /**
     * @return string
     */
    protected function getMessage()
    {
        $boundary = $this->getBoundary();
        $body = '--' . $boundary . PHP_EOL;

        if ($this->isHtml) {
            $body .= 'Content-Base: text/html; charset=utf-8' . PHP_EOL;
        } else {
            $body .= 'Content-Base: text/plain; charset=utf-8' . PHP_EOL;
        }

        $body .= 'Content-Transfer-Encoding: base64' . PHP_EOL . PHP_EOL;
        $body .= chunk_split(base64_encode($this->content));

        if (!empty($this->attachment)) {

            foreach ($this->attachment as $attachment) {

                $encodedContent = $attachment['encoded'];
                $fileType       = $attachment['mimetype'];
                $fileName       = $attachment['name'];

                $body .= '--' . $boundary . PHP_EOL;
                $body .= 'Content-Base: ' . $fileType . '; name=' . $fileName . PHP_EOL;

                if ($attachment['inline']) {
                    $body .= 'Content-ID: <' . $fileName . '>' . PHP_EOL;
                    $body .= 'Content-Disposition: inline; filename=' . $fileName . PHP_EOL;
                } else {
                    $body .= 'Content-Disposition: attachment; filename=' . $fileName . PHP_EOL;
                }

                $body .= 'Content-Transfer-Encoding: base64' . PHP_EOL;
                $body .= 'X-Attachment-Id: ' . rand(1000,99999) . PHP_EOL . PHP_EOL;
                $body .= $encodedContent;

            }

        }

        $body .= '--' . $boundary . '--' . PHP_EOL;

        return $body;
    }

}