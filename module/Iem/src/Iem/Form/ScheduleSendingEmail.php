<?php

namespace Iem\Form;

use Zend\Form\Form,
    Doctrine\Common\Persistence\ObjectManager,
    DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Zend\Form\Annotation\AnnotationBuilder;
use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import

class ScheduleSendingEmail extends Form {

    public function __construct(\Doctrine\ORM\EntityManager  $entityManager) {
        parent::__construct('ScheduleSendingEmail');

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
                'description' => 'Ingrese un nombre descriptivo'
            )
        ));


        $this->add(array(
            'name' => 'datetimeSchedule',
            'type' => 'Zend\Form\Element\DateTime',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                  'data-date-format' => 'YYYY/MM/DD HH:mm',
            ),
            'options' => array(
                'label' => 'Fecha y Hora',
                'description' => 'Indique la fecha y hora del inicio del envío'
            )
        ));
        
        $this->get('datetimeSchedule')->setFormat("Y/m/d H:i");
        
          /*
         * Input Text
         */
        $this->add(array(
            'name' => 'timeInterval',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'maxlength' => "3"
            ),
            'options' => array(
                'label' => 'Intervalo entre emails',
                'description' => 'Ingrese la cantidad de <strong>segundos</strong> a esperar entre envio de mails'
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
                'label' => "SMTP",
                  'display_empty_item' => true,
                  'empty_item_label' => '---',
                'description' => 'Seleccione el SMTP que se utilizara para el envío'
            ),
        ));
        
         $this->add(array(
            'name' => 'emailLayout',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => false,
                "class" => "form-control"
            ),
            'options' => array(
                'object_manager' => $entityManager,
                'target_class' => 'Iem\Entity\EmailLayout',
                'property' => 'name',
                'label' => "Layout",
                  'display_empty_item' => true,
                  'empty_item_label' => '---',
                'description' => 'Seleccione el Layout que se utilizara para el envío'
            ),
        ));

        $this->add(array(
            'name' => 'mailingListManager',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => false,
                "class" => "form-control"
            ),
            'options' => array(
                'object_manager' => $entityManager,
                'target_class' => 'Iem\Entity\MailingListManager',
                'property' => 'name',
                'label' => "Lista a enviar",
                  'display_empty_item' => true,
                  'empty_item_label' => '---',
                'description' => 'Seleccione la lista que utilizara para el envío'
            ),
        ));

        $this->add(array(
            'name' => 'groupingTemplate',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => false,
                "class" => "form-control"
            ),
            'options' => array(
                'object_manager' => $entityManager,
                'target_class' => 'Iem\Entity\Grouping',
                'property' => 'name',
                'label' => "Grupo de Templates",
                  'display_empty_item' => true,
                  'empty_item_label' => '---',
                'description' => 'Seleccione el grupo de template a utilizar (Asuntos y Textos)'
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
                'value' => 'Guardar'
            )
        ));
    }

    public function InputFilter() {

        $inputFilter = new InputFilter();
        //$factory = new InputFactory();



        return $inputFilter;
    }

}
