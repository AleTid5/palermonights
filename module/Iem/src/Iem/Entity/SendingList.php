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
 * @ORM\Table(name="sending_list")
 *
 * @author Cristian Incarnato
 */

class SendingList extends \Iem\Entity\ExtendedEntity implements InputFilterAwareInterface {

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
     * @ORM\ManyToOne(targetEntity="Iem\Entity\ScheduleSendingEmail")
     * 
     */
    protected $scheduleSendingEmail;

    /**
     * @var string
     * @ORM\Column(type="string", length=20,unique=false, nullable=true)
     */
    protected $status;
    
       /**
     * @var integer
     * @ORM\Column(type="integer", length=11,unique=false, nullable=true, name="time_interval")
     */
    protected $timeInterval;
    

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Iem\Entity\Subject")
     * 
     */
    protected $subject;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Iem\Entity\Text")
     * 
     */
    protected $text;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Iem\Entity\EmailAuth")
     * 
     */
    protected $emailAuth;

    /**
     * @var string
     * @ORM\Column(type="string", length=80,unique=false, nullable=true)
     */
    protected $emailFrom;
    
      /**
     * @var string
     * @ORM\Column(type="string", length=100,unique=false, nullable=true)
     */
    protected $displaynameFrom;
    
    /**
     * @var string
     * @ORM\Column(type="text", unique=false, nullable=true, name="send_error")
     */
    protected $sendError;
    
    /**
     * @var string
     * @ORM\Column(type="text", unique=false, nullable=true)
     */
    protected $textParsed;
    
       /**
     * @var string
     * @ORM\Column(type="text", unique=false, nullable=true)
     */
    protected $htmlParsed;
    
    /**
     * @var string
     * @ORM\Column(type="string", length=300,unique=false, nullable=true)
     */
    protected $subjectParsed;
    
     /**
     * @var \DateTime
     * @ORM\Column(type="datetime",unique=false, nullable=true)
     */
    protected $shippingDate;
    
    /**
     * @var \DateTime
     * @ORM\Column(type="datetime",unique=false, nullable=true)
     */
    protected $estimatedDate;


    /**
     * @var boolean
     * @ORM\Column(type="boolean", unique=false, nullable=true)
     */
    protected $open;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", unique=false, nullable=true)
     */
    protected $rebound;

    /**
     * @var boolean
     * @ORM\Column(type="boolean", unique=false, nullable=true)
     */
    protected $response;

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    function getHtmlParsed() {
        return $this->htmlParsed;
    }

    function setHtmlParsed($htmlParsed) {
        $this->htmlParsed = $htmlParsed;
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

    public function getSendError() {
        return $this->sendError;
    }

    public function setSendError($sendError) {
        $this->sendError = $sendError;
    }

        
    public function getContact() {
        return $this->contact;
    }

    public function setContact($contact) {
        $this->contact = $contact;
    }

    public function getScheduleSendingEmail() {
        return $this->scheduleSendingEmail;
    }

    public function setScheduleSendingEmail($scheduleSendingEmail) {
        $this->scheduleSendingEmail = $scheduleSendingEmail;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getSubject() {
        return $this->subject;
    }

    public function setSubject($subject) {
        $this->subject = $subject;
    }

    public function getText() {
        return $this->text;
    }

    public function setText($text) {
        $this->text = $text;
    }

    public function getEmailAuth() {
        return $this->emailAuth;
    }

    public function setEmailAuth($emailAuth) {
        $this->emailAuth = $emailAuth;
    }

    public function getEmailFrom() {
        return $this->emailFrom;
    }

    public function setEmailFrom($emailFrom) {
        $this->emailFrom = $emailFrom;
    }
    
    public function getDisplaynameFrom() {
        return $this->displaynameFrom;
    }

    public function setDisplaynameFrom($displaynameFrom) {
        $this->displaynameFrom = $displaynameFrom;
    }

    public function getTextParsed() {
        return $this->textParsed;
    }

    public function setTextParsed($textParsed) {
        $this->textParsed = $textParsed;
    }

    public function getSubjectParsed() {
        return $this->subjectParsed;
    }

    public function setSubjectParsed($subjectParsed) {
        $this->subjectParsed = $subjectParsed;
    }

    
    public function getShippingDate() {
        return $this->shippingDate;
    }

    public function setShippingDate(\DateTime $shippingDate) {
        $this->shippingDate = $shippingDate;
    }

    public function getEstimatedDate() {
        return $this->estimatedDate;
    }

    public function setEstimatedDate(\DateTime $estimatedDate) {
        $this->estimatedDate = $estimatedDate;
    }

        
    public function getOpen() {
        return $this->open;
    }

    public function setOpen($open) {
        $this->open = $open;
    }

    public function getRebound() {
        return $this->rebound;
    }

    public function setRebound($rebound) {
        $this->rebound = $rebound;
    }

    public function getResponse() {
        return $this->response;
    }

    public function setResponse($response) {
        $this->response = $response;
    }

    public function getTimeInterval() {
        return $this->timeInterval;
    }

    public function setTimeInterval($timeInterval) {
        $this->timeInterval = $timeInterval;
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

