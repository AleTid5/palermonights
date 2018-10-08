<?php

namespace Iem\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import
use Zend\Form\Annotation;

/**
 * An example of how to implement a role aware user entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="reserva")
 * @Annotation\Name("Reserva")
 * @author Cristian Incarnato
 */
class Reserva extends \Iem\Entity\ExtendedEntity implements InputFilterAwareInterface {

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
    protected $nombre;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Options({"label":"Apellido:"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":5, "max":50}})
     */
    protected $apellido;
    
      /**
     * @var string
     * @ORM\Column(type="integer", length=3, unique=false, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Options({"label":"Cantidad Personas:"})
     */
    protected $cantidadPersonas;
    
    /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Options({"label":"Celular:"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":5, "max":50}})
     */
    protected $celular;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Options({"label":"Email:"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":5, "max":50}})
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Options({"label":"RRPP:"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":5, "max":50}})
     */
    protected $rrpp;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Options({"label":"Estado:"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":5, "max":50}})
     */
    protected $estado;
    
   

    /**
     * @var string
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Date")
     * @Annotation\Required({"required":"false" })
     * @Annotation\AllowEmpty({"allowempty":"true"})
     * @Annotation\Options({"label":"Fecha:"})
     * @Annotation\Attributes({"data-date-format":"YYYY/MM/DD", "required":false})
     */
    protected $fecha;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    function getNombre() {
        return $this->nombre;
    }

    function getApellido() {
        return $this->apellido;
    }

    function getCelular() {
        return $this->celular;
    }

    function getEmail() {
        return $this->email;
    }

    function getRrpp() {
        return $this->rrpp;
    }

    function getEstado() {
        return $this->estado;
    }

    function getFecha() {
        return $this->fecha;
    }

    function setNombre($nombre) {
        $this->nombre = $nombre;
    }

    function setApellido($apellido) {
        $this->apellido = $apellido;
    }

    function setCelular($celular) {
        $this->celular = $celular;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setRrpp($rrpp) {
        $this->rrpp = $rrpp;
    }

    function setEstado($estado) {
        $this->estado = $estado;
    }

    function setFecha($fecha) {
        $this->fecha = $fecha;
    }
    function getCantidadPersonas() {
        return $this->cantidadPersonas;
    }

    function setCantidadPersonas($cantidadPersonas) {
        $this->cantidadPersonas = $cantidadPersonas;
    }

    
    public function getInputFilter() {
        if (!$this->inputFilter) {
            $inputFilter = new InputFilter();
            $factory = new InputFactory();


            $inputFilter->add($factory->createInput(array(
                        'name' => 'nombre',
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
            
             $inputFilter->add($factory->createInput(array(
                        'name' => 'fecha',
                        'required' => false,
                        'allow_empty' => true
                       
            )));

            $this->inputFilter = $inputFilter;
        }

        return $this->inputFilter;
    }

    public function setInputFilter(InputFilterInterface $inputFilter) {
        return null;
    }

    public function __toString() {
        return $this->nombre;
    }

}
