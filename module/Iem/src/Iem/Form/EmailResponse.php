<?php

namespace Iem\Form;

use Zend\Form\Form,
    Doctrine\Common\Persistence\ObjectManager,
    DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Zend\Form\Annotation\AnnotationBuilder;
use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import

class EmailResponse extends Form {

    public function __construct(\Doctrine\ORM\EntityManager  $entityManager) {
        parent::__construct('EmailResponse');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', "form-horizontal");
        $this->setAttribute('role', "form");
        /*
         * Input hidden
         */
        $this->add(array(
            'name' => 'conversationId',
            'type' => 'Zend\Form\Element\Hidden',
        ));



         
        $this->add(array(
            'name' => 'textos',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => false,
                "class" => "form-control",
                'onchange' => 'loadText(this)'
            ),
            'options' => array(
                'object_manager' => $entityManager,
                'target_class' => 'Iem\Entity\ConversationText',
                'property' => 'name',
                  'empty_option' => 'Seleccionar Texto',
                'label' => "Textos",
                'description' => 'Seleccione un texto prestablecido'
            ),
        ));
       


          /*
         * Input TextArea
         */
        $this->add(array(
            'name' => 'bodyText',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                 'rows'=> 10,
                'id' => 'bodyText'
            ),
            'options' => array(
                'label' => 'Cuerpo del Mail',
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
        $factory = new InputFactory();
         $inputFilter->add($factory->createInput(array(
                    'name' => 'textos',
                    'required' => false,
                    
        )));


        return $inputFilter;
    }

}
