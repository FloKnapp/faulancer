<?php
/**
 * Class MailerTest | MailerTest.php
 * @package Unit
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Unit;

use Faulancer\Mail\Mailer;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceLocator;
use PHPUnit\Framework\TestCase;

/**
 * Class MailerTest
 */
class MailerTest extends TestCase
{

    /**
     * Test mailer in general
     */
    public function testGeneralMailer()
    {
        $projectRoot = ServiceLocator::instance()->get(Config::class)->get('projectRoot');

        $mock = $this->createPartialMock(Mailer::class, ['sendMail']);
        $mock->method('sendMail')->will($this->returnValue(true));

        $mock->addTo('test@test.de');
        $mock->addCc('test@test.de');
        $mock->addBcc('test@test.de');
        $mock->setFrom('test@test.de');
        $mock->setReplyTo('test@test.de');
        $mock->setSubject('Testsubject');
        $mock->addAttachment('42_attachment.jpg', $projectRoot . '/public/images/img.jpg');
        $mock->addAttachment('42_inline.jpg', $projectRoot . '/public/images/img.jpg', true);
        $mock->setContent('Testmessage<br /><img src="cid:42_inline.jpg">');

        $this->assertTrue($mock->send());
    }

    /**
     * Test automatic set of replyTo within from value
     */
    public function testSetReplyToByFrom()
    {
        $projectRoot = ServiceLocator::instance()->get(Config::class)->get('projectRoot');

        $mock = $this->createPartialMock(Mailer::class, ['sendMail']);
        $mock->method('sendMail')->will($this->returnValue(true));

        $mock->addTo('test@test.de');
        $mock->addCc('test@test.de');
        $mock->addBcc('test@test.de');
        $mock->setFrom('test@test.de');
        $mock->setSubject('Testsubject');
        $mock->addAttachment('42_attachment.jpg', $projectRoot . '/public/images/img.jpg');
        $mock->addAttachment('42_inline.jpg', $projectRoot . '/public/images/img.jpg', true);
        $mock->setContent('Testmessage<br /><img src="cid:42_inline.jpg">');

        $this->assertTrue($mock->send());
    }

    /**
     * Test sending plain text
     */
    public function testSendPlainText()
    {
        $projectRoot = ServiceLocator::instance()->get(Config::class)->get('projectRoot');

        $mock = $this->createPartialMock(Mailer::class, ['sendMail']);
        $mock->method('sendMail')->will($this->returnValue(true));

        $mock->addTo('test@test.de');
        $mock->addCc('test@test.de');
        $mock->addBcc('test@test.de');
        $mock->setFrom('test@test.de');
        $mock->setSubject('Testsubject');
        $mock->addAttachment('42_attachment.jpg', $projectRoot . '/public/images/img.jpg');
        $mock->addAttachment('42_inline.jpg', $projectRoot . '/public/images/img.jpg', true);
        $mock->setContent('Testmessage<br /><img src="cid:42_inline.jpg">');

        $this->assertTrue($mock->send(false));
    }

}