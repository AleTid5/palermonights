<?php

namespace Iem\Form;

use Zend\Form\Form,
    Doctrine\Common\Persistence\ObjectManager,
    DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Zend\Form\Annotation\AnnotationBuilder;
use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import

class ConversationState extends Form {

    public function __construct(\Doctrine\ORM\EntityManager  $entityManager) {
        parent::__construct('ConversationState');
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
            'name' => 'state',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => false,
                "class" => "form-control",
            ),
            'options' => array(
                'object_manager' => $entityManager,
                'target_class' => 'Iem\Entity\ConversationState',
                'property' => 'name',
            ),
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
                'value' => 'Aplicar'
            )
        ));
    }

    public function InputFilter() {

        $inputFilter = new InputFilter();
        //$factory = new InputFactory();



        return $inputFilter;
    }

}
