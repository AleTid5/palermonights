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
 * @ORM\Table(name="email_test")
 * @Annotation\Name("EmailTest")
 * @author Cristian Incarnato
 */

class EmailTest extends \Iem\Entity\ExtendedEntity  {

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Annotation\Type("Zend\Form\Element\Hidden")
     * 
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Options({"label":"Nombre:"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":5, "max":25}})
     */
    protected $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     * @Annotation\Validator({"name":"StringLength", "options":{"min":3, "max":5}})
     * @Annotation\Type("Zend\Form\Element\Text")
     * @Annotation\Options({"label":"Apellido:"})
     * 
     */
    protected $lastname;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     * @Annotation\Type("Zend\Form\Element\Email")
     * @Annotation\Options({"label":"Email:"})
     */
    protected $email;

 

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function getLastname() {
        return $this->lastname;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

  


}
