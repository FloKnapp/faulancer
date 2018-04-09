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
     * Send email
     *
     * @param bool $html Whether to send as html or plain text
     * @return bool
     */
    public function send($html = true)
    {
        $this->isHtml = $html;
        return $this->sendMail($this->getHeaders(), $this->getMessage());
    }

    /**
     * Executes mail command
     *
     * @param string $headers
     * @param string $message
     *
     * @return bool
     */
    protected function sendMail($headers, $message)
    {
        if (mail(implode(',', $this->recipients), mb_encode_mimeheader($this->subject, 'UTF-8'), $message, $headers)) {
            return true;
        }
        return false;
    }

    /**
     * Get boundary for email headers
     *
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
     * Get headers
     *
     * @return string
     */
    protected function getHeaders()
    {
        $eol      = "\r\n";
        $boundary = $this->getBoundary();
        $headers = 'MIME-Version: 1.0' . $eol;
        $headers .= 'Content-Type: multipart/mixed; charset=UTF-8; boundary=' . $boundary . $eol;

        if (!empty($this->from)) {
            $headers .= 'From: ' .$this->from . $eol;
        }

        if (!empty($this->replyTo)) {
            $headers .= 'Reply-To: ' . $this->replyTo . $eol;
        } else if (!empty($this->from)) {
            $headers .= 'Reply-To: ' . $this->from . $eol;
        }

        if (!empty($this->carbonCopies)) {
            $headers .= 'CC: ' . implode(',', $this->carbonCopies) . $eol;
        }

        if (!empty($this->blindCarbonCopies)) {
            $headers .= 'BCC: ' . implode(',', $this->blindCarbonCopies) . $eol;
        }

        return $headers;
    }

    /**
     * Get message body
     *
     * @return string
     */
    protected function getMessage()
    {
        $eol      = "\r\n";
        $boundary = $this->getBoundary();

        $body = '--' . $boundary . $eol;

        if ($this->isHtml) {
            $body .= 'Content-Type: text/html; charset="utf-8"' . $eol;
        } else {
            $body .= 'Content-Type: text/plain; charset="utf-8"' . $eol;
        }

        $body .= 'Content-Transfer-Encoding: 8bit' . $eol . $eol;
        $body .= $this->content . $eol;

        if (!empty($this->attachment)) {

            foreach ($this->attachment as $attachment) {

                $encodedContent = $attachment['encoded'];
                $fileType       = $attachment['mimetype'];
                $fileName       = $attachment['name'];

                $body .= '--' . $boundary . $eol;
                $body .= 'Content-Type: ' . $fileType . '; name=' . $fileName . $eol;

                if ($attachment['inline']) {
                    $body .= 'Content-ID: <' . $fileName . '>' . $eol;
                    $body .= 'Content-Disposition: inline; filename=' . $fileName . $eol;
                } else {
                    $body .= 'Content-Disposition: attachment; filename=' . $fileName . $eol;
                }

                $body .= 'Content-Transfer-Encoding: base64' . $eol;
                $body .= 'X-Attachment-Id: ' . rand(1000,99999) . $eol . $eol;
                $body .= $encodedContent . $eol;

            }

        }

        $body .= '--' . $boundary . '--' . $eol;

        return $body;
    }

}