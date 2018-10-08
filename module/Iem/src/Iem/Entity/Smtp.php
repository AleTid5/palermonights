<?php

namespace Iem\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;


use Zend\InputFilter\Factory as InputFactory;     // <-- Add this import
use Zend\InputFilter\InputFilter;                 // <-- Add this import
use Zend\InputFilter\InputFilterAwareInterface;   // <-- Add this import
use Zend\InputFilter\InputFilterInterface;        // <-- Add this import
/**
 * An example of how to implement a role aware user entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="smtp")
 *
 * @author Cristian Incarnato
 */
class Smtp extends \Iem\Entity\ExtendedEntity implements InputFilterAwareInterface {

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     */
    protected $name;

       /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     */
    protected $smtp;
    
       /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true, name="smtp_port")
     */
    protected $smtpPort;
    
       /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true, name="smtp_secure")
     */
    protected $smtpSecure;
    
    
    /**
     * 
     * @ORM\OneToMany(targetEntity="Iem\Entity\EmailAuth", mappedBy="smtp", cascade={"persist"})
     * 
     */
    protected $emailAuthCollection;

  public function __construct() {
        $this->emailAuthCollection = new ArrayCollection();
    }
    
    public function getEmailAuthCollection() {
        return $this->emailAuthCollection;
    }

    public function setEmailAuthCollection($emailAuthCollection) {
        $this->emailAuthCollection = $emailAuthCollection;
    }

        
    
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

    
    public function getSmtp() {
        return $this->smtp;
    }

    public function setSmtp($smtp) {
        $this->smtp = $smtp;
    }

    public function getSmtpPort() {
        return $this->smtpPort;
    }

    public function setSmtpPort($smtpPort) {
        $this->smtpPort = $smtpPort;
    }

    public function getSmtpSecure() {
        return $this->smtpSecure;
    }

    public function setSmtpSecure($smtpSecure) {
        $this->smtpSecure = $smtpSecure;
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

