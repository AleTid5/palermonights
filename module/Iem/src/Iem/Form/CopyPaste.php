<?php

namespace Iem\Form;

use Zend\Form\Form,
    Doctrine\Common\Persistence\ObjectManager,
    DoctrineModule\Stdlib\Hydrator\DoctrineObject as DoctrineHydrator,
    Zend\Form\Annotation\AnnotationBuilder;
use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import

class CopyPaste extends Form {

    public function __construct() {
        parent::__construct('Smtp');
        $this->setAttribute('method', 'post');
        $this->setAttribute('class', "form-horizontal");
        $this->setAttribute('role', "form");

        /*
         * Input hidden
         */
        $this->add(array(
            'name' => 'xxx',
            'type' => 'Zend\Form\Element\Hidden',
        ));


        /*
         * Input Text
         */
        $this->add(array(
            'name' => 'xxx',
            'type' => 'Zend\Form\Element\Text',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'placeholder' => "xxx"
            ),
            'options' => array(
                'label' => 'xxx',
                'description' => ''
            )
        ));


        /*
         * Input Email
         */
        $this->add(array(
            'name' => 'xxx',
            'type' => 'Zend\Form\Element\Email',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'placeholder' => "xxx"
            ),
            'options' => array(
                'label' => 'xxx',
                'description' => ''
            )
        ));



        /*
         * Input TextArea
         */
        $this->add(array(
            'name' => 'xxx',
            'type' => 'Zend\Form\Element\Textarea',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
            ),
            'options' => array(
                'label' => 'xxx',
                'description' => ''
            )
        ));

        /*
         * Input Checkbox
         */
        $this->add(array(
            'name' => 'xxx',
            'type' => 'Zend\Form\Element\Checkbox',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
            ),
            'options' => array(
                'label' => 'xxx',
                'description' => ''
            )
        ));


        /*
         * Input Date
         */

        $this->add(array(
            'name' => 'xxx',
            'type' => 'Zend\Form\Element\Date',
            'attributes' => array(
                'required' => false,
                'class' => "form-control",
                'data-date-format' => 'YYYY/MM/DD',
            ),
            'options' => array(
                'label' => 'xxx',
                'description' => ''
            )
        ));

        /*
         * Input File
         */
        $this->add(array(
            'type' => 'Zend\Form\Element\File',
            'name' => 'csv',
            'options' => array(
                'label' => 'Archivo CSV con IPs Listadas',
            ),
        ));


        /*
         * Input Select - Array (Example Horas)
         */
        $options = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23);
        $this->add(array(
            'name' => 'xxx',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'required' => false,
            ),
            'options' => array(
                'label' => 'xxx',
                'description' => '',
                'value_options' => $options
            ),
        ));


        /*
         * Input Select - Array (Example estados)
         */
        $options = array("Abierto" => "Abierto", "Cerrado" => "Cerrado");
        $this->add(array(
            'name' => 'xxx',
            'type' => 'Zend\Form\Element\Select',
            'attributes' => array(
                'required' => false,
                'class' => "form-control"
            ),
            'options' => array(
                'label' => 'xxx',
                'description' => '',
                'empty_option' => 'Seleccionar Estado',
                'value_options' => $options
            ),
        ));



        /*
         * Input Select - Doctrine SIMPLE
         */
        $this->add(array(
            'name' => 'xxx',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => false,
                "class" => "form-control"
            ),
            'options' => array(
                'object_manager' => $entityManager,
                'target_class' => 'Xxx\Entity\Xxx',
                'property' => 'xxxnamexxx',
                'label' => "xxx",
                'description' => ''
            ),
        ));

        /*
         * Input Select - Doctrine ADVANCED
         */

        $this->add(array(
            'name' => 'xxx',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'attributes' => array(
                'required' => false,
                "class" => "form-control"
            ),
            'options' => array(
                'object_manager' => $entityManager,
                'target_class' => 'Xxx\Entity\Xxx',
                'property' => 'xxxnamexxx',
                'display_empty_item' => true,
                'empty_item_label' => '---',
                'label' => "xxx",
                'description' => '',
                'is_method' => true,
                'find_method' => array(
                    'name' => 'findBy',
                    'params' => array(
                        'criteria' => array('xxx' => "xxx")
                    ),
                ),
            ),
        ));


        /*
         * Input Text - By Factory - Validators and Filters Include
         */
        $this->add('text', 'username', array(
            'validators' => array(
                'alnum',
                array('regex', false, '/^[a-z]/i')
            ),
            'required' => true,
            'filters' => array('StringToLower'),
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

        /*
         * Input Filter: Required and Not Empty
         */
        $inputFilter->add($factory->createInput(array(
                    'name' => 'xxx',
                    'required' => true,
                    'validators' => array(
                        array(
                            'name' => 'not_empty',
                        ),
                    ),
        )));

        /*
         * Input Filter: 
         * Required
         * Filters: StripTags | StringTrim | StringToLower (Tipical String)
         * Validators: StringLenght(Tipical String)
         */

        $inputFilter->add($factory->createInput(array(
                    'name' => 'xxx',
                    'required' => true,
                    'filters' => array(
                        array('name' => 'StripTags'),
                        array('name' => 'StringTrim'),
                        array('name' => 'StringToLower')
                    ),
                    'validators' => array(
                        array(
                            'name' => 'StringLength',
                            'options' => array(
                                'min' => 1,
                                'max' => 40,
                            ),
                        ),
                    ),
        )));

        return $inputFilter;
    }

}
