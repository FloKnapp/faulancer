<?php
/**
 * Class FormBuilderTest | FormBuilderTest.php
 * @package Unit
 * @author  Florian Knapp <office@florianknapp.de>
 */
namespace Faulancer\Test\Unit;

use Faulancer\Fixture\Form\DummyForm;
use Faulancer\Fixture\Form\DummyFormCustomValidator;
use PHPUnit\Framework\TestCase;

/**
 * Class FormBuilderTest
 */
class FormBuilderTest extends TestCase
{

    /** @var DummyForm */
    protected $dummyForm;

    /** @var DummyFormCustomValidator */
    protected $dummyFormCustomValidator;

    /**
     * SetUp
     */
    public function setUp()
    {
        $this->dummyForm = new DummyForm();
        $this->dummyFormCustomValidator = new DummyFormCustomValidator();
    }

    /**
     * Data provider for fields
     */
    public function fieldDataProvider()
    {
        return [
            ['<input name="firstname" type="text" />', 'firstname'],
            ['<input name="lastname" type="text" />', 'lastname'],
            ['<input name="email" type="email" />', 'email'],
            ['<input name="date" type="date" />', 'date'],
            ['<input name="hidden" type="hidden" value="hidden_val" />', 'hidden'],
            ['<input name="tel" type="tel" />', 'tel'],
            ['<input name="number" type="number" />', 'number'],
            ['<input type="hidden" name="checkbox" value="yes"/><input name="checkbox" type="checkbox" value="no" checked="checked"/>', 'checkbox'],
            ['<select name="gender"><option value="w">Frau</option><option value="m">Herr</option></select>', 'gender'],
            ['<button name="submit" type="submit">TextSubmit</button>', 'submit']
        ];
    }

    /**
     * Expect correct form open tag
     */
    public function testDummyFormOpen()
    {
        $this->assertSame('<form action="/test" method="GET" enctype="application/x-www-form-urlencoded" autocomplete="on">', $this->dummyForm->getFormOpen());
    }

    /**
     * Expect correct form close tag
     */
    public function testDummyFormClose()
    {
        $this->assertSame('</form>', $this->dummyForm->getFormClose());
    }

    /**
     * @param string $expected
     * @param string $fieldName
     *
     * @dataProvider fieldDataProvider
     */
    public function testStaticFormDefinitionText($expected, $fieldName)
    {
        ob_start();
        echo $this->dummyForm->getField($fieldName);
        $output = ob_get_contents();
        ob_end_clean();

        $this->assertNotEmpty($output);
        $this->assertTrue(is_string($output));
        $this->assertSame($expected, $output);

    }

    public function testRadioFormDefinition()
    {
        $this->assertSame(
            '<input value="FirstValue" name="radio"  type="radio"  id="radio_FirstValue" checked="checked"/>',
            $this->dummyForm->getField('radio')->getOption('first')
        );

        $this->assertSame(
            '<input value="SecondValue" name="radio"  type="radio"  id="radio_SecondValue"/>',
            $this->dummyForm->getField('radio')->getOption('second')
        );
    }

}