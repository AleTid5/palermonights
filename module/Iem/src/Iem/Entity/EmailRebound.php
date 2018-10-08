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
 * @ORM\Table(name="email_rebound")
 *
 * @author Cristian Incarnato
 */
class EmailRebound extends \Iem\Entity\ExtendedEntity implements InputFilterAwareInterface {

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
   /**
     * @var int
     * @ORM\Column(type="string",length=100,  unique=false)
     */
    protected $messageId;

    /**
     * @var int
     * @ORM\Column(type="integer",length=11,  unique=false, nullable=true)
     */
    protected $uid;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, unique=false, nullable=true)
     */
    protected $subject;
    
     /**
     * @var string
     * @ORM\Column(type="string", length=80, unique=false, nullable=true, name="sender_by")
     */
    protected $senderBy;
    
      /**
     * @var string
     * @ORM\Column(type="string", length=80, unique=false, nullable=true, name="email_rebound")
     */
    protected $emailRebound;
    
     /**
     * @var string
     * @ORM\Column(type="string", length=80, unique=false, nullable=true, name="sent_to")
     */
    protected $sentTo;

       /**
     * @var string
     * @ORM\Column(type="text",  unique=false, nullable=true)
     */
    protected $body;
    
       /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     */
    protected $type;
    
      /**
     * @var \DateTime
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $date;
     
        /**
     * @var boolean
     * @ORM\Column(type="boolean",  unique=false, nullable=true)
     */
    protected $seen;
    
    
    function getId() {
        return $this->id;
    }

    function getSubject() {
        return $this->subject;
    }

    function getBody() {
        return $this->body;
    }

    function getType() {
        return $this->type;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setSubject($subject) {
        $this->subject = $subject;
    }

    function setBody($body) {
        $this->body = $body;
    }

    function setType($type) {
        $this->type = $type;
    }
    
    function getSeen() {
        return $this->seen;
    }

    function setSeen($seen) {
        $this->seen = $seen;
    }

    function getDate() {
        return $this->date;
    }

    function setDate(\DateTime $date) {
        $this->date = $date;
    }

    function getSenderBy() {
        return $this->senderBy;
    }

    function getSentTo() {
        return $this->sentTo;
    }

    function setSenderBy($senderBy) {
        $this->senderBy = $senderBy;
    }

    function setSentTo($sentTo) {
        $this->sentTo = $sentTo;
    }

    function getEmailRebound() {
        return $this->emailRebound;
    }

    function setEmailRebound($emailRebound) {
        $this->emailRebound = $emailRebound;
    }

    function getMessageId() {
        return $this->messageId;
    }

    function setMessageId($messageId) {
        $this->messageId = $messageId;
    }

    function getUid() {
        return $this->uid;
    }

    function setUid($uid) {
        $this->uid = $uid;
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
        return $this->subject;
    }



}

