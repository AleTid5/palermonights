<?php

namespace Iem\Form;

use Zend\Form\Form,
    Doctrine\Common\Persistence\ObjectManager,
    DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Zend\Form\Annotation\AnnotationBuilder;
use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import

class Smtp extends Form {

    public function __construct() {
        parent::__construct('Smtp');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', "form-horizontal");
        $this->setAttribute('role', "form");
         $this->setAttribute('action', "javascript:cdiSubmitEdit()");
        /*
         * Input hidden
         */
        $this->add(array(
            'name' => 'id',
            'type' => 'Zend\Form\Element\Hidden',
        ));

        /*
         * Input Text
         */
        $this->add(array(
            'name' => 'name',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'placeholder' => ""
            ),
            'options' => array(
                'label' => 'Nombre',
                'description' => 'Ingrese un nombre descriptivo que utilizara luego para identificar y referenciar este STMP'
            )
        ));


        /*
         * Input Text
         */
        $this->add(array(
            'name' => 'smtp',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'placeholder' => ""
            ),
            'options' => array(
                'label' => 'SMTP',
                'description' => 'Ingrese la IP o dominio del SMTP (Ej: smtp.gmail.com, localhost, 10.10.10.1)'
            )
        ));


        /*
         * Input Text
         */
        $this->add(array(
            'name' => 'smtpPort',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'placeholder' => ""
            ),
            'options' => array(
                'label' => 'Puerto SMTP',
                'description' => 'Ingrese el puerto que utiliza este SMTP'
            )
        ));


        /*
         * Input Text
         */
        $this->add(array(
            'name' => 'smtpSecure',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'placeholder' => ""
            ),
            'options' => array(
                'label' => 'Seguridad SMTP',
                'description' => 'En el caso de que el smtp utilice algun protocolo de seguridad, ingrese
                    aqui el mismo (Ej: SSL)'
            )
        ));


        $this->addSubmitAndCsrf();
    }

    protected function addSubmitAndCsrf() {
        $this->add(array(
            'type' => 'Zend\Form\Element\Csrf',
            'name' => 'csrf'
        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'value' => 'Enviar'
            )
        ));
    }

    public function InputFilter() {

        $inputFilter = new InputFilter();
        //$factory = new InputFactory();



        return $inputFilter;
    }

}