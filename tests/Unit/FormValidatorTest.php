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
        self::assertTrue($this->getValidator(Text::class, 'Test'));
        self::assertTrue($this->getValidator(Text::class, 'Test12345'));
        self::assertTrue($this->getValidator(Text::class, '12345'));
        self::assertFalse($this->getValidator(Text::class, ''));
    }

    public function testNumber()
    {
        self::assertTrue($this->getValidator(Number::class, 12345));
        self::assertTrue($this->getValidator(Number::class, 1.5));
        self::assertTrue($this->getValidator(Number::class, '12345'));
    }

    public function testEmail()
    {
        self::assertTrue($this->getValidator(Email::class, 'test@test.de'));
        self::assertFalse($this->getValidator(Email::class, 'test@test'));
        self::assertFalse($this->getValidator(Email::class, ''));
    }

    public function testDateTime()
    {
        self::assertTrue($this->getValidator(DateTime::class, '2016-06-22'));
        self::assertTrue($this->getValidator(DateTime::class, '22.06.2016'));
        self::assertTrue($this->getValidator(DateTime::class, '22.06.2016 00:01'));
        self::assertFalse($this->getValidator(DateTime::class, '2016-22-06'));
        self::assertFalse($this->getValidator(DateTime::class, '2'));
        self::assertFalse($this->getValidator(DateTime::class, '22:299'));
    }

    public function testImage()
    {
        /** @var Config $config */
        $config = ServiceLocator::instance()->get(Config::class);
        $publicPath = $config->get('projectRoot') . '/public';

        self::assertTrue($this->getValidator(Image::class, $publicPath . '/images/img.jpg'));
        self::assertFalse($this->getValidator(Image::class, $publicPath . '/images/imgNotExistent.jpg'));
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