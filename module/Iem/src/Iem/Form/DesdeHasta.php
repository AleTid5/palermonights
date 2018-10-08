<?php

namespace Iem\Form;

use Zend\Form\Form,
    Doctrine\Common\Persistence\ObjectManager,
    DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Zend\Form\Annotation\AnnotationBuilder;
use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import

class DesdeHasta extends Form {

    public function __construct() {
        // we want to ignore the name passed
        parent::__construct('DesdeHasta');


        $this->setAttribute('method', 'get');
        $this->setAttribute('class', "form-horizontal");
        $this->setAttribute('role', "form");




        $this->add(array(
            'name' => 'desde',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => true,
                'class' => "form-control",
                'data-date-format' => 'YYYY/MM/DD',
                'placeholder' => "Desde"
            ),
            'options' => array(
                'label' => 'Desde',
            )
        ));

        $this->add(array(
            'name' => 'hasta',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                  'required' => true,
                'class' => "form-control",
                'data-date-format' => 'YYYY/MM/DD',
                'placeholder' => "Hasta"
            ),
            'options' => array(
                'label' => 'Hasta',
            )
        ));



        $this->addSubmitAndCsrf();
    }

    protected function addSubmitAndCsrf() {
//        $this->add(array(
//            'type' => 'Zend\Form\Element\Csrf',
//            'name' => 'csrf'
//        ));

        $this->add(array(
            'name' => 'submit',
            'type' => 'Zend\Form\Element\Submit',
            'attributes' => array(
                'type' => 'submit',
                'value' => 'Filtrar'
            )
        ));
    }

    public function InputFilter() {

        $inputFilter = new InputFilter();
        $factory = new InputFactory();

        $inputFilter->add($factory->createInput(array(
                    'name' => 'desde',
                    'required' => true,
                    'validators' => array(
                        array(
                            'name' => 'not_empty',
                        ),
                    ),
        )));



        $inputFilter->add($factory->createInput(array(
                    'name' => 'hasta',
                    'required' => true,
                    'validators' => array(
                        array(
                            'name' => 'not_empty',
                        ),
                    ),
        )));

        return $inputFilter;
    }

}