<?php

namespace Unit;

use Faulancer\Form\Type\AbstractType;
use Faulancer\Form\Validator\AbstractValidator;
use Faulancer\Form\Validator\Base\DateTime;
use Faulancer\Form\Validator\Base\Email;
use Faulancer\Form\Validator\Base\Image;
use Faulancer\Form\Validator\Base\Number;
use Faulancer\Form\Validator\Base\Text;
use Faulancer\Service\Config;
use Faulancer\ServiceLocator\ServiceLocator;
use PHPUnit\Framework\TestCase;

/**
 * Class FormValidatorTest
 * @package Unit
 */
class FormValidatorTest extends TestCase
{

    public function testText()
    {
        $this->assertTrue($this->getValidator(Text::class, 'Test'));
        $this->assertTrue($this->getValidator(Text::class, 'Test12345'));
        $this->assertFalse($this->getValidator(Text::class, '12345'));
        $this->assertFalse($this->getValidator(Text::class, ''));
    }

    public function testNumber()
    {
        $this->assertTrue($this->getValidator(Number::class, 12345));
        $this->assertTrue($this->getValidator(Number::class, 1.5));
        $this->assertFalse($this->getValidator(Number::class, '12345'));
    }

    public function testEmail()
    {
        $this->assertTrue($this->getValidator(Email::class, 'test@test.de'));
        $this->assertFalse($this->getValidator(Email::class, 'test@test'));
        $this->assertFalse($this->getValidator(Email::class, ''));
    }

    public function testDateTime()
    {
        $this->assertTrue($this->getValidator(DateTime::class, '2016-06-22'));
        $this->assertTrue($this->getValidator(DateTime::class, '22.06.2016'));
        $this->assertTrue($this->getValidator(DateTime::class, '22.06.2016 00:01'));
        $this->assertFalse($this->getValidator(DateTime::class, '2016-22-06'));
        $this->assertFalse($this->getValidator(DateTime::class, '2'));
        $this->assertFalse($this->getValidator(DateTime::class, '22:299'));
    }

    public function testImage()
    {
        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);
        $publicPath = $config->get('projectRoot') . '/public';

        $this->assertTrue($this->getValidator(Image::class, $publicPath . '/images/img.jpg'));
        $this->assertFalse($this->getValidator(Image::class, $publicPath . '/images/imgNotExistent.jpg'));
    }

    /**
     * @param string $validator
     * @param mixed  $data
     * @return boolean
     */
    private function getValidator($validator, $data)
    {
        $mock = $this
            ->getMockBuilder(AbstractType::class)
            ->disableOriginalConstructor()
            ->getMock();

        /** @var AbstractValidator $class */
        $class = new $validator($mock);
        return $class->process($data);
    }

}