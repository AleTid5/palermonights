<?php

namespace Iem\Form;

use Zend\Form\Form,
    Doctrine\Common\Persistence\ObjectManager,
    DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Zend\Form\Annotation\AnnotationBuilder;
use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import

class ContactFilter extends Form {

    public function __construct() {
        // we want to ignore the name passed
        parent::__construct('ContactFilter');


        $this->setAttribute('method', 'get');
        $this->setAttribute('class', "form-horizontal");
        $this->setAttribute('role', "form");


        $this->add(array(
            'name' => 'fs',
            'type' => 'Zend\Form\Element\Hidden',
            'attributes' => array(
                'value' => 'ok'
            )
        ));


        $this->add(array(
            'name' => 'fromDate',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'data-date-format' => 'YYYY/MM/DD',
            ),
            'options' => array(
                'label' => 'Cumple Desde',
            )
        ));

        $this->add(array(
            'name' => 'toDate',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'data-date-format' => 'YYYY/MM/DD',
            ),
            'options' => array(
                'label' => 'Cumple Hasta',
            )
        ));

        $this->add(array(
            'name' => 'fromAge',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
            ),
            'options' => array(
                'label' => 'Edad Desde',
            )
        ));

        $this->add(array(
            'name' => 'toAge',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
            ),
            'options' => array(
                'label' => 'Edad Hasta',
            )
        ));

        $this->add(array(
            'name' => 'neighborhood',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
            ),
            'options' => array(
                'label' => 'Barrio',
            )
        ));

        $this->add(array(
            'name' => 'city',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
            ),
            'options' => array(
                'label' => 'Ciudad',
            )
        ));

        $this->add(array(
            'name' => 'province',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
            ),
            'options' => array(
                'label' => 'Provincia',
            )
        ));

        $this->add(array(
            'name' => 'country',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
            ),
            'options' => array(
                'label' => 'Pais',
            )
        ));


        $this->add(array(
            'name' => 'iedad',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
            ),
            'options' => array(
                'label' => 'Excluir edad vacio',
                'checked_value' => 1,
                'unchecked_value' => 0,
            )
        ));
        
        
         $this->add(array(
            'name' => 'rebound',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                "value" => 0
            ),
            'options' => array(
                'label' => 'Rebotados',
                'checked_value' => 1,
                'unchecked_value' => 0,
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

    public function addNeighborhood() {
        $this->add(array(
            'name' => 'caba',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'value' => "Ciudad Autónoma de Buenos Aires",
                'onclick' => "dynamicFilter(this)"
            ),
            'options' => array(
                'label' => 'CABA',
                'checked_value' => 'Ciudad Autónoma de Buenos Aires',
                'unchecked_value' => 0,
            )
        ));

        $this->add(array(
            'name' => 'vicenteLopez',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'value' => "Vicente Lopez",
                'onclick' => "dynamicFilter(this)"
            ),
            'options' => array(
                'label' => 'Vicente Lopez',
                'checked_value' => 'Vicente Lopez',
                'unchecked_value' => 0,
            )
        ));

        $this->add(array(
            'name' => 'Olivos',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'value' => "Olivos",
                'onclick' => "dynamicFilter(this)"
            ),
            'options' => array(
                'label' => 'Olivos',
                'checked_value' => 'Olivos',
                'unchecked_value' => 0,
            )
        ));

        $this->add(array(
            'name' => 'Martinez',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'value' => "Martinez",
                'onclick' => "dynamicFilter(this)"
            ),
            'options' => array(
                'label' => 'Martinez',
                'checked_value' => 'Martinez',
                'unchecked_value' => 0,
            )
        ));

        $this->add(array(
            'name' => 'Pilar',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'value' => 'Pilar',
                'onclick' => "dynamicFilter(this)"
            ),
            'options' => array(
                'label' => 'Pilar',
                'checked_value' => 'Pilar',
                'unchecked_value' => 0,
            )
        ));

        $this->add(array(
            'name' => 'SanIsidro',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'value' => 'San Isidro',
                'onclick' => "dynamicFilter(this)"
            ),
            'options' => array(
                'label' => 'San Isidro',
                'checked_value' => 'San Isidro',
                'unchecked_value' => 0,
            )
        ));


        $this->add(array(
            'name' => 'BellaVista',
            'type' => 'Zend\Form\Element\Checkbox',
            'required' => false,
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'value' => 'Bella Vista',
                'onclick' => "dynamicFilter(this)"
            ),
            'options' => array(
                'label' => 'Bella Vista',
                'checked_value' => 'Bella Vista',
                'unchecked_value' => 0,
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
