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
 * @ORM\Table(name="email_auth")
 *
 * @author Cristian Incarnato
 */
class EmailAuth extends \Iem\Entity\ExtendedEntity implements InputFilterAwareInterface {

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
    protected $lastname;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     */
    protected $email;
    
     /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     */
    protected $password;
    
      /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     */
    protected $mobile;
    
      /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true, name="email_recuperacion")
     */
    protected $emailRecovery;
    
     /**
     * 
     * @ORM\ManyToOne(targetEntity="Iem\Entity\Smtp", cascade={"persist"})
     * 
     */
    protected $smtp;

    /**
     * @var string
     * @ORM\Column(type="boolean",  unique=false, nullable=true)
     */
    protected $enable;



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

    public function getPassword() {
        return $this->password;
    }

    public function setPassword($password) {
        $this->password = $password;
    }

    public function getMobile() {
        return $this->mobile;
    }

    public function setMobile($mobile) {
        $this->mobile = $mobile;
    }

    
    public function getEmailRecovery() {
        return $this->emailRecovery;
    }

    public function setEmailRecovery($emailRecovery) {
        $this->emailRecovery = $emailRecovery;
    }

        public function getSmtp() {
        return $this->smtp;
    }

    public function setSmtp($smtp) {
        $this->smtp = $smtp;
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
                                    'max' => 50,
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

    /**
     * @return string
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * @param string $enable
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
    }


    
}

