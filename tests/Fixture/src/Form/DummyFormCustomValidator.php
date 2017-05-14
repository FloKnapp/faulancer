<?php
/**
 * Class DummyFormCustomValidator | DummyFormCustomValidator.php
 * @author Florian Knapp <office@florianknapp.de>
 */

namespace Faulancer\Fixture\Form;

use Faulancer\Form\AbstractFormBuilder;
use Faulancer\Form\Validator\Base\NotEmpty;

/**
 * Class DummyForm
 */
class DummyFormCustomValidator extends AbstractFormBuilder
{

    public function create()
    {

        $this->add([
            'label' => 'Vorname',
            'attributes' => [
                'name' => 'firstname',
                'type' => 'text'
            ],
            'validator' => [
                NotEmpty::class
            ]
        ]);

        $this->add([
            'label' => 'Nachname',
            'attributes' => [
                'name' => 'lastname',
                'type' => 'text'
            ],
            'validator' => [
                NotEmpty::class
            ]
        ]);

    }

}