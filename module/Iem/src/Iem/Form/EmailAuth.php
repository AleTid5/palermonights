<?php

namespace Iem\Form;

use Zend\Form\Form,
    Doctrine\Common\Persistence\ObjectManager,
    DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Zend\Form\Annotation\AnnotationBuilder;
use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import

class EmailAuth extends Form {

    public function __construct(\Doctrine\ORM\EntityManager  $entityManager) {
        parent::__construct('EmailAuth');
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
                'description' => 'Ingrese el nombre de la cuenta de mail'
            )
        ));

         /*
         * Input Text
         */
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
                'description' => 'Ingrese el Apellido de la cuenta de mail'
            )
        ));

      
          /*
         * Input Email
         */
        $this->add(array(
            'name' => 'email',
            'type' => 'Zend\Form\Element\Email',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'placeholder' => ""
            ),
            'options' => array(
                'label' => 'Email',
                'description' => 'Ingrese el email (ej: juan.roble@gmail.com)'
            )
        ));
        
           /*
         * Input Text
         */
        $this->add(array(
            'name' => 'password',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'placeholder' => ""
            ),
            'options' => array(
                'label' => 'Clave',
                'description' => 'Ingrese la clave para autenticar la cuenta en el SMTP'
            )
        ));
        
        
        
           /*
         * Input Text
         */
        $this->add(array(
            'name' => 'mobile',
            'type' => 'Zend\Form\Element\Text',
              'required' => false,
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'placeholder' => ""
            ),
            'options' => array(
                'label' => 'Cel',
                'description' => 'Ingrese el celular asociado al email (Algunos proveedores lo solicitan)'
            )
        ));
        
        
           /*
         * Input Text
         */
        $this->add(array(
            'name' => 'emailRecovery',
            'type' => 'Zend\Form\Element\Text',
            'required' => false,
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'placeholder' => ""
            ),
            'options' => array(
                'label' => 'Email de Recuperacion',
                'description' => 'Ingrese el email para recuperar cuenta (algunos proveedores lo solicitan)'
            )
        ));

        
        $this->add(array(
            'name' => 'smtp',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => false,
                "class" => "form-control"
            ),
            'options' => array(
                'object_manager' => $entityManager,
                'target_class' => 'Iem\Entity\Smtp',
                'property' => 'name',
                'label' => "Smtp",
                'description' => 'Seleccion el SMTP que utilizara esta cuenta (Debe cargar los SMTP de forma previa)'
            ),
        ));

        /*
 * Input Text
 */
        $this->add(array(
            'name' => 'enable',
            'type' => 'Zend\Form\Element\Checkbox',
            'required' => false,
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'placeholder' => ""
            ),
            'options' => array(
                'label' => 'Enable',
                'description' => 'Habilita o deshabilita la cuenta'
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