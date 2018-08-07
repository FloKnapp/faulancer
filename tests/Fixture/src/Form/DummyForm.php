<?php
/**
 * Class DummyForm | DummyForm.php
 * @author Florian Knapp <office@florianknapp.de>
 */

namespace Faulancer\Fixture\Form;

use Faulancer\Form\AbstractFormBuilder;

/**
 * Class DummyForm
 */
class DummyForm extends AbstractFormBuilder
{

    public function create()
    {

        $this->setFormAttributes([
            'method' => 'GET',
            'action' => '/test'
        ]);

        $this->add([
            'label' => 'TextFirstname',
            'attributes' => [
                'name' => 'firstname',
                'type' => 'text'
            ]
        ]);

        $this->add([
            'label' => 'TextLastname',
            'attributes' => [
                'name' => 'lastname',
                'type' => 'text'
            ]
        ]);

        $this->add([
            'label' => 'TextSelect',
            'attributes' => [
                'name' => 'gender',
                'type' => 'select'
            ],
            'options' => [
                'w' => 'Frau',
                'm' => 'Herr'
            ]
        ]);

        $this->add([
            'label' => 'TextEmail',
            'attributes' => [
                'name' => 'email',
                'type' => 'email'
            ]
        ]);

        $this->add([
            'label' => 'TextDate',
            'attributes' => [
                'name' => 'date',
                'type' => 'date'
            ]
        ]);

        $this->add([
            'label' => 'FileType',
            'attributes' => [
                'name' => 'file',
                'type' => 'file'
            ]
        ]);

        $this->add([
            'label' => 'TextRadio',
            'attributes' => [
                'name' => 'radio',
                'type' => 'radio',
            ],
            'options' => [
                'first' => [
                    'label' => 'First',
                    'value' => 'FirstValue'
                ],
                'second' => [
                    'label' => 'Second',
                    'value' => 'SecondValue'
                ]
            ],
            'default' => 'first'
        ]);

        $this->add([
            'label' => 'TextCheckbox',
            'attributes' => [
                'name' => 'checkbox',
                'type' => 'checkbox',
                'value' => 'no'
            ],
            'default' => 'yes',
            'checked' => true
        ]);

        $this->add([
            'label' => 'TextTel',
            'attributes' => [
                'name' => 'tel',
                'type' => 'tel',
            ]
        ]);

        $this->add([
            'label' => 'TextHidden',
            'attributes' => [
                'name'  => 'hidden',
                'type'  => 'hidden',
                'value' => 'hidden_val'
            ]
        ]);

        $this->add([
            'label' => 'TextNumber',
            'attributes' => [
                'name'  => 'number',
                'type'  => 'number'
            ]
        ]);

        $this->add([
            'label' => 'TextPassword',
            'attributes' => [
                'name'  => 'password',
                'type'  => 'password'
            ]
        ]);

        $this->add([
            'label' => 'TextSubmit',
            'attributes' => [
                'name'  => 'submit',
                'type'  => 'submit'
            ]
        ]);

        $this->add([
            'label' => 'TextArea',
            'attributes' => [
                'name'  => 'textarea',
                'type'  => 'textarea'
            ]
        ]);

    }

}