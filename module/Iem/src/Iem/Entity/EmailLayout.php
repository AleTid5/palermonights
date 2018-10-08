<?php

namespace Iem\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\Form\Annotation;

use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import
/**
 * An example of how to implement a role aware user entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="email_layout")
 * @Annotation\Name("Layout")
 * @author Cristian Incarnato
 */
class EmailLayout extends \Iem\Entity\ExtendedEntity implements InputFilterAwareInterface {

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
      * @Annotation\Type("Zend\Form\Element\Hidden")
     */
    protected $id;
    
     /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Options({"label":"Nombre:"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":5, "max":50}})
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="text", unique=false, nullable=true, name="start_txt")
     * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Options({"label":"Inicio TXT:"})
     */
    protected $startTxt;
 

       /**
     * @var string
     * @ORM\Column(type="text", unique=false, nullable=true, name="end_txt")
       * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Options({"label":"Fin TXT:"})
     */
    protected $endTxt;
    
    
      /**
     * @var string
     * @ORM\Column(type="text", unique=false, nullable=true, name="start_html")
        * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Options({"label":"Inicio HTML:"})
     */
    protected $startHtml;
 

       /**
     * @var string
     * @ORM\Column(type="text", unique=false, nullable=true, name="end_html")
        * @Annotation\Type("Zend\Form\Element\Textarea")
     * @Annotation\Options({"label":"Fin HTML:"})
     */
    protected $endHtml;
    
   


    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    function getStart() {
        return $this->start;
    }

    function getEnd() {
        return $this->end;
    }

    function setStart($start) {
        $this->start = $start;
    }

    function setEnd($end) {
        $this->end = $end;
    }

    function getStartTxt() {
        return $this->startTxt;
    }

    function getEndTxt() {
        return $this->endTxt;
    }

    function getStartHtml() {
        return $this->startHtml;
    }

    function getEndHtml() {
        return $this->endHtml;
    }

    function setStartTxt($startTxt) {
        $this->startTxt = $startTxt;
    }

    function setEndTxt($endTxt) {
        $this->endTxt = $endTxt;
    }

    function setStartHtml($startHtml) {
        $this->startHtml = $startHtml;
    }

    function setEndHtml($endHtml) {
        $this->endHtml = $endHtml;
    }

    function getName() {
        return $this->name;
    }

    function setName($name) {
        $this->name = $name;
    }

            
            
     public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();


            $inputFilter->add($factory->createInput(array(
                        'name' => 'name',
                        'required' => true,
                        'filters' => array(
                            array('name' => 'StripTags'),
                            array('name' => 'StringTrim'),
                        ),
                        'validators' => array(
                            array(
                                'name' => 'StringLength',
                                'options' => array(
                                    'encoding' => 'UTF-8',
                                    'min' => 1,
                                    'max' => 40,
                                ),
                            ),
                        ),
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        return null;
    }
    
    public function __toString() {
        return $this->name;
    }



}

