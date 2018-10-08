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
 * @ORM\Table(name="mailing_list")
 *
 * @author Cristian Incarnato
 */
class MailingList extends \Iem\Entity\ExtendedEntity implements InputFilterAwareInterface {

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
     * @ORM\Column(type="text", length=50,unique=false, nullable=true)
     */
    protected $email;
    
         /**
     * @var string
     * @ORM\Column(type="string", length=50,unique=false, nullable=true, name="facebook_email")
     */
    protected $facebookEmail;

    
      /**
     * @var string
     * @ORM\Column(type="string", length=20,unique=false, nullable=true, name="birthday_text")
     */
    protected $birthdayText;
    
     /**
     * @var string
     * @ORM\Column(type="string", length=20,unique=false, nullable=true)
     */
    protected $age;
 
       /**
     * 
     * @ORM\ManyToOne(targetEntity="Iem\Entity\Contact")
     * 
     */
    protected $contact;

    
     /**
     * 
     * @ORM\ManyToOne(targetEntity="Iem\Entity\MailingListManager")
     * 
     */
    protected $mailingListManager;


    


    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
    

    public function getAge() {
        return $this->age;
    }

    public function setAge($age) {
        $this->age = $age;
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

    public function getFacebookEmail() {
        return $this->facebookEmail;
    }

    public function setFacebookEmail($facebookEmail) {
        $this->facebookEmail = $facebookEmail;
    }

    public function getBirthdayText() {
        return $this->birthdayText;
    }

    public function setBirthdayText($birthdayText) {
        $this->birthdayText = $birthdayText;
    }

    public function getContact() {
        return $this->contact;
    }

    public function setContact($contact) {
        $this->contact = $contact;
    }

    public function getMailingListManager() {
        return $this->mailingListManager;
    }

    public function setMailingListManager($mailingListManager) {
        $this->mailingListManager = $mailingListManager;
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

