<?php

namespace Iem\Form;

use Zend\Form\Form,
    Doctrine\Common\Persistence\ObjectManager,
    DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Zend\Form\Annotation\AnnotationBuilder;
use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import

class Subject extends Form {

    public function __construct(\Doctrine\ORM\EntityManager  $entityManager) {
        parent::__construct('Subject');
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
                'description' => 'Ingrese un nombre descriptivo que utilizara luego para identificar y referenciar este texto'
            )
        ));


         
        $this->add(array(
            'name' => 'grouping',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => false,
                "class" => "form-control"
            ),
            'options' => array(
                'object_manager' => $entityManager,
                'target_class' => 'Iem\Entity\Grouping',
                'property' => 'name',
                'label' => "Grupo",
                'description' => 'Seleccion del grupo al que pertence este texto'
            ),
        ));
       


          /*
         * Input Text
         */
        $this->add(array(
            'name' => 'subject',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                 'rows'=> 10 
            ),
            'options' => array(
                'label' => 'Asunto',
                'description' => 'Ingrese el texto que viajara en el asunto del Mail.<br>
                    Recuerde que puede utilizar los tag: {CUMPLE} {NOMBRE} {APELLIDO} {EDAD}'
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