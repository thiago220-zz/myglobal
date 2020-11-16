<?php

namespace Application\Form;

use Laminas\Form\Form;

class ContactoForm extends Form {

    public function __construct() {
        parent::__construct('contact-form');
        $this->setAttribute('method', 'post');
        $this->addElements();
        $this->addInputFilter();
    }

    private function addElements() {    
        $this->add([
            'type' => 'text',
            'name' => 'asunto',
            'attributes' => [
                'id' => 'asunto'
            ],
            'options' => [
                'label' => 'Asunto',
            ],
        ]);

        $this->add([
            'type' => 'textarea',
            'name' => 'cuerpo',
            'attributes' => [
                'id' => 'cuerpo'
            ],
            'options' => [
                'label' => 'Cuerpo del mensaje',
            ],
        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'enviar',
            'attributes' => [
                'value' => 'Enviar',
            ],
        ]);
    }

    private function addInputFilter() {
        $inputFilter = $this->getInputFilter();

        $inputFilter->add([
            'name' => 'asunto',
            'required' => true,
            'filters' => [
                ['name' => 'StringTrim'],
                ['name' => 'StripTags'],
                ['name' => 'StripNewlines'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 128
                    ],
                ],
            ],
                ]
        );

        $inputFilter->add([
            'name' => 'cuerpo',
            'required' => true,
            'filters' => [
                ['name' => 'StripTags'],
            ],
            'validators' => [
                [
                    'name' => 'StringLength',
                    'options' => [
                        'min' => 1,
                        'max' => 4096
                    ],
                ],
            ],
                ]
        );
    }

}
