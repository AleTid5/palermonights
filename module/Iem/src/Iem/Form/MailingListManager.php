<?php

namespace Iem\Form;

use Zend\Form\Form,
    Doctrine\Common\Persistence\ObjectManager,
    DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Zend\Form\Annotation\AnnotationBuilder;
use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import

class MailingListManager extends Form {

    public function __construct() {
        parent::__construct('MailingListManager');
        
         $this->setAttribute('id', 'MailingListManager');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', "form-horizontal");
        $this->setAttribute('role', "form");
         //$this->setAttribute('onSubmit',"#");
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
                'description' => 'Ingrese un nombre descriptivo que utilizara luego para identificar y referenciar esta lista'
            )
        ));
        
        
          $options = array("," => "Coma (,)", ";" => "Punto y Coma (;)", "\t"=> "Tabulador" );
        $this->add(array(
            'name' => 'csvSeparator',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'required' => false,
                'class' => "form-control"
            ),
            'options' => array(
                'label' => 'Separador CSV',
                'description' => 'Ingrese el tipo de separador utilizado en el archivo CSV',
                'value_options' => $options
            ),
        ));


          $this->add(array(
            'type' => 'Zend\Form\Element\File',
            'name' => 'uploadlist',
             'options' => array(
                 'label' => "Archivo",
                'description' => 'Archivo con lista de destinatarios.<br>Formatos Permitidos: CSV',
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