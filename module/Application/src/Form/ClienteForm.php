<?php

namespace Application\Form;

use Laminas\Form\Form;
use Laminas\InputFilter\InputFilter;
use Application\Entity\Cliente;

class ClienteForm extends Form {

    public function __construct() {
        parent::__construct('post-form');
        $this->setAttribute('method', 'post');
        $this->addElements();
        $this->addInputFilter();
    }

    protected function addElements() {
        $this->add([
            'type' => 'text',
            'name' => 'nombre',
            'attributes' => [
                'id' => 'nombre'
            ],
            'options' => [
                'label' => 'Nombre',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'apellidos',
            'attributes' => [
                'id' => 'apellidos'
            ],
            'options' => [
                'label' => 'Apellidos',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'nif',
            'attributes' => [
                'id' => 'nif'
            ],
            'options' => [
                'label' => 'Nif',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'dni',
            'attributes' => [
                'id' => 'dni'
            ],
            'options' => [
                'label' => 'DNI',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'direccion',
            'attributes' => [
                'id' => 'direccion'
            ],
            'options' => [
                'label' => 'Dirección',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'fecha_de_antiguedad',
            'attributes' => [
                'id' => 'fecha_de_antiguedad'
            ],
            'options' => [
                'label' => 'Fecha de Antiguedad',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'telefono',
            'attributes' => [
                'id' => 'telefono'
            ],
            'options' => [
                'label' => 'Telefono',
            ],
        ]);

        $this->add([
            'type' => 'text',
            'name' => 'n_cc',
            'attributes' => [
                'id' => 'n_cc'
            ],
            'options' => [
                'label' => 'Nº CC',
            ],
        ]);

        $this->add([
            'type' => 'file',
            'name' => 'contrato_1',
            'attributes' => [
                'id' => 'contrato_1'
            ],
            'options' => [
                'label' => 'Contrato 1',
            ],
        ]);

        $this->add([
            'type' => 'file',
            'name' => 'contrato_2',
            'attributes' => [
                'id' => 'contrato_2'
            ],
            'options' => [
                'label' => 'Contrato 2',
            ],
        ]);

        $this->add([
            'type' => 'file',
            'name' => 'contrato_3',
            'attributes' => [
                'id' => 'contrato_3'
            ],
            'options' => [
                'label' => 'Contrato 3',
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'status',
            'attributes' => [
                'id' => 'status'
            ],
            'options' => [
                'label' => 'Status',
                'value_options' => [
                    Cliente::STATUS_ACEPTADO => 'Aceptado',
                    Cliente::STATUS_ACTIVO => 'Activo',
                    Cliente::STATUS_ESPERA => 'Espera',
                    Cliente::STATUS_RECHAZADO => 'Rechazado',
                ]
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'tipo',
            'attributes' => [
                'id' => 'tipo'
            ],
            'options' => [
                'label' => 'Tipo',
                'value_options' => [
                    Cliente::TIPO_LUXE => 'Contrato Luxe',
                    Cliente::TIPO_PAMM => 'Contrato PAMM',
                ]
            ],
        ]);

        $this->add([
            'type' => 'select',
            'name' => 'tipo_cliente',
            'attributes' => [
                'id' => 'tipo_cliente'
            ],
            'options' => [
                'label' => 'Tipo del cliente',
                'value_options' => [
                    Cliente::TIPO_C_AUTONOMO => 'Autonomo',
                    Cliente::TIPO_C_EMPRESA => 'Empresa',
                ]
            ],
        ]);

        $this->add([
            'type' => 'submit',
            'name' => 'crear',
            'attributes' => [
                'value' => 'Crear',
                'id' => 'crear',
            ],
        ]);
    }

    /**
     * This method creates input filter (used for form filtering/validation).
     */
    private function addInputFilter() {

        $inputFilter = new InputFilter();
        $this->setInputFilter($inputFilter);

        $inputFilter->add([
            'name' => 'nombre',
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
                        'max' => 255
                    ],
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'apellidos',
            'required' => false,
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
                        'max' => 255
                    ],
                ],
            ],
        ]);

        //Input nif
        //Input DNI
        //Input direccion
        //Input fecha_de_antiguedad
        //Input telefono
        //Input n_cc
        $inputFilter->add([
            'type' => 'Laminas\InputFilter\FileInput',
            'name' => 'contrato_1', // Element's name.
            'required' => false, // Whether the field is required.
            'filters' => [
                [
                    'name' => 'FileRenameUpload',
                    'options' => [
                        'target' => './public/docs/upload/',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => false
                    ]
                ]
            ],
            'validators' => [
                ['name' => 'FileUploadFile'],
                [
                    'name' => 'FileMimeType',
                    'options' => [
                        'mimeType' => ['image/jpeg', 'image/png', 'application/pdf', 'application/msword']
                    ]
                ],
            ]
        ]);

        $inputFilter->add([
            'type' => 'Laminas\InputFilter\FileInput',
            'name' => 'contrato_2',
            'required' => false,
            'filters' => [
                [
                    'name' => 'FileRenameUpload',
                    'options' => [
                        'target' => './public/docs/upload/',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => false
                    ]
                ]
            ],
            'validators' => [
                ['name' => 'FileUploadFile'],
                [
                    'name' => 'FileMimeType',
                    'options' => [
                        'mimeType' => ['image/jpeg', 'image/png', 'application/pdf', 'application/msword']
                    ]
                ],
            ]
        ]);

        $inputFilter->add([
            'type' => 'Laminas\InputFilter\FileInput',
            'name' => 'contrato_3', // Element's name.
            'required' => false,
            'filters' => [
                [
                    'name' => 'FileRenameUpload',
                    'options' => [
                        'target' => './public/docs/upload/',
                        'useUploadName' => true,
                        'useUploadExtension' => true,
                        'overwrite' => true,
                        'randomize' => false
                    ]
                ]
            ],
            'validators' => [
                ['name' => 'FileUploadFile'],
                [
                    'name' => 'FileMimeType',
                    'options' => [
                        'mimeType' => ['image/jpeg', 'image/png', 'application/pdf', 'application/msword']
                    ]
                ],
            ]
        ]);

        $inputFilter->add([
            'name' => 'status',
            'required' => true,
            'validators' => [
                [
                    'name' => 'InArray',
                    'options' => [
                        'haystack' => [Cliente::STATUS_ACEPTADO, Cliente::STATUS_ACTIVO, Cliente::STATUS_ESPERA, Cliente::STATUS_RECHAZADO],
                    ]
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'tipo',
            'required' => true,
            'validators' => [
                [
                    'name' => 'InArray',
                    'options' => [
                        'haystack' => [Cliente::TIPO_LUXE, Cliente::TIPO_PAMM],
                    ]
                ],
            ],
        ]);

        $inputFilter->add([
            'name' => 'tipo_cliente',
            'required' => true,
            'validators' => [
                [
                    'name' => 'InArray',
                    'options' => [
                        'haystack' => [Cliente::TIPO_C_AUTONOMO, Cliente::TIPO_C_EMPRESA],
                    ]
                ],
            ],
        ]);
    }

}
