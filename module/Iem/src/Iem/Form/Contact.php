<?php

namespace Iem\Form;

use Zend\Form\Form,
    Doctrine\Common\Persistence\ObjectManager,
    DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Zend\Form\Annotation\AnnotationBuilder;
use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import

class Contact extends Form {

    public function __construct(\Doctrine\ORM\EntityManager $entityManager) {
        parent::__construct('Contacts');
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
            )
        ));


        $this->add(array(
            'name' => 'lastname',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'placeholder' => ""
            ),
            'options' => array(
                'label' => 'Apellido',
            )
        ));

        $this->add(array(
            'name' => 'fullname',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'placeholder' => ""
            ),
            'options' => array(
                'label' => 'Nombre Completo',
            )
        ));


        $this->add(array(
            'name' => 'country',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'placeholder' => ""
            ),
            'options' => array(
                'label' => 'Pais',
            )
        ));

        $this->add(array(
            'name' => 'province',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'placeholder' => ""
            ),
            'options' => array(
                'label' => 'Provincia',
            )
        ));

        $this->add(array(
            'name' => 'city',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'placeholder' => ""
            ),
            'options' => array(
                'label' => 'Ciudad',
            )
        ));

        $this->add(array(
            'name' => 'facebookId',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'placeholder' => ""
            ),
            'options' => array(
                'label' => 'Facebook Id',
            )
        ));

        $this->add(array(
            'name' => 'facebookUser',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'placeholder' => ""
            ),
            'options' => array(
                'label' => 'FacebookUser',
            )
        ));
        
          $this->add(array(
            'name' => 'facebookEmail',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'placeholder' => ""
            ),
            'options' => array(
                'label' => 'Facebook Email',
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