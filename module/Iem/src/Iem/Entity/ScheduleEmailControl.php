<?php

namespace Iem\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * An example of how to implement a role aware user entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="schedule_email_control")
 *
 * @author Cristian Incarnato
 */

class ScheduleEmailControl extends \Iem\Entity\ExtendedEntity  {

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
    protected $state;

    
    
       
     /**
     * 
     * @ORM\OneToOne(targetEntity="Iem\Entity\ScheduleSendingEmail")
     * 
     */
    protected $scheduleSendingEmail;
    

  

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
    function getState() {
        return $this->state;
    }

    function getScheduleSendingEmail() {
        return $this->scheduleSendingEmail;
    }

    function setState($state) {
        $this->state = $state;
    }

    function setScheduleSendingEmail($scheduleSendingEmail) {
        $this->scheduleSendingEmail = $scheduleSendingEmail;
    }

    
    public function __toString() {
        return $this->state;
    }

}

