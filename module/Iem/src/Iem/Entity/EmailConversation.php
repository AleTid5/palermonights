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
 * @ORM\Table(name="email_conversation", uniqueConstraints={@ORM\UniqueConstraint(name="ckey", columns={"subject", "email_a", "email_b"})})
 *
 * @author Cristian Incarnato
 */

class EmailConversation extends \Iem\Entity\ExtendedEntity implements InputFilterAwareInterface {

     
    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=150, unique=false, nullable=true)
     */
    protected $subject;

    /**
     * @var string
     * @ORM\Column(type="string", length=80, unique=false, nullable=true, name="email_a")
     */
    protected $emailA;

    /**
     * @var string
     * @ORM\Column(type="string", length=80, unique=false, nullable=true, name="email_b")
     */
    protected $emailB;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Iem\Entity\ConversationState")
     * 
     */
    protected $state;

    /**
     * @var boolean
     * @ORM\Column(type="boolean",  unique=false, nullable=true)
     */
    protected $isResponded;
    
    
     /**
     * 
     * @ORM\ManyToOne(targetEntity="Iem\Entity\ScheduleSendingEmail")
     * 
     */
    protected $ScheduleSendingEmail;
    
     /**
     * @var boolean
     * @ORM\Column(type="boolean",  unique=false, nullable=true)
     */
    protected $deleted;

    function getId() {
        return $this->id;
    }

    function getSubject() {
        return $this->subject;
    }

    function getEmailA() {
        return $this->emailA;
    }

    function getEmailB() {
        return $this->emailB;
    }

    function getState() {
        return $this->state;
    }

    function getIsResponded() {
        return $this->isResponded;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setSubject($subject) {
        $this->subject = $subject;
    }

    function setEmailA($emailA) {
        $this->emailA = $emailA;
    }

    function setEmailB($emailB) {
        $this->emailB = $emailB;
    }

    function setState($state) {
        $this->state = $state;
    }

    function setIsResponded($isResponded) {
        $this->isResponded = $isResponded;
    }

    function getScheduleSendingEmail() {
        return $this->ScheduleSendingEmail;
    }

    function setScheduleSendingEmail($ScheduleSendingEmail) {
        $this->ScheduleSendingEmail = $ScheduleSendingEmail;
    }
    
    function getDeleted() {
        return $this->deleted;
    }

    function setDeleted($deleted) {
        $this->deleted = $deleted;
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
