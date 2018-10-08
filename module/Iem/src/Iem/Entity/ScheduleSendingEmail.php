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
 * @ORM\Table(name="schedule_sending_mail")
 *
 * @author Cristian Incarnato
 */

class ScheduleSendingEmail extends \Iem\Entity\ExtendedEntity implements InputFilterAwareInterface {

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
     * @var \DateTime
     * @ORM\Column(type="datetime", length=50, unique=false, nullable=true, name="datetime_schedule")
     */
    protected $datetimeSchedule;
    
     /**
     * @var \DateTime
     * @ORM\Column(type="datetime", length=50, unique=false, nullable=true, name="datetime_finish")
     */
    protected $datetimeFinish;
    
     /**
     * 
     * @ORM\ManyToOne(targetEntity="Iem\Entity\MailingListManager")
     * var \Iem\Entity\MailingListManager
     */
    protected $mailingListManager;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Iem\Entity\Smtp")
     * @var \Iem\Entity\Smtp
     */
    protected $smtp;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="Iem\Entity\Grouping")
     * @var \Iem\Entity\Grouping
     */
    protected $groupingTemplate;

    /**
     * @var integer
     * @ORM\Column(type="integer", length=11,unique=false, nullable=true, name="time_interval")
     */
    protected $timeInterval;
    
      /**
     * @var integer
     * @ORM\Column(type="integer", length=11,unique=false, nullable=true, name="time_interval_total")
     */
    protected $timeIntervalTotal;
    
       
     /**
     * 
     * @ORM\OneToMany(targetEntity="Iem\Entity\SendingList", mappedBy="scheduleSendingEmail")
     * 
     */
    protected $sendingList;
    
    
    /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     */
    protected $state;
    
        /**
     * @var integer
     * @ORM\Column(type="integer", length=11, unique=false, nullable=true, name="emails_processed")
     */
    protected $emailsProcessed;
    
          /**
     * @var integer
     * @ORM\Column(type="integer", length=11, unique=false, nullable=true,  name="emails_sent")
     */
    protected $emailsSent;
    
          /**
     * @var integer
     * @ORM\Column(type="integer", length=11, unique=false, nullable=true, name="emails_failed")
     */
    protected $emailsFailed;
    
           /**
     * @var integer
     * @ORM\Column(type="integer", length=11, unique=false, nullable=true, name="emails_pending")
     */
    protected $emailsPending;
    
              /**
     * @var integer
     * @ORM\Column(type="integer", length=11, unique=false, nullable=true, name="emails_total")
     */
    protected $emailsTotal;
    
    
    /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=false, nullable=true, name="estimated_ending_time")
     */
    protected $estimatedEndingtime;
    
    
           /**
     * @var integer
     * @ORM\Column(type="integer", length=11, unique=false, nullable=true)
     */
    protected $combination;
    
      /**
     * 
     * @ORM\OneToOne(targetEntity="Iem\Entity\ScheduleEmailControl", cascade={"persist"})
     * 
     */
    protected $scheduleEmailControl;
    
    
    /**
     * 
     * @ORM\ManyToOne(targetEntity="Iem\Entity\EmailLayout")
     * @var \Iem\Entity\EmailLayout
     */
    protected $emailLayout;
    
      public function __construct() {
         $this->sendingList = new ArrayCollection();
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

    public function getState() {
        return $this->state;
    }

    public function setState($state) {
        $this->state = $state;
    }
    public function getDatetimeFinish() {
        return $this->datetimeFinish;
    }

    public function setDatetimeFinish(\DateTime $datetimeFinish) {
        $this->datetimeFinish = $datetimeFinish;
    }

        
    public function getEstimatedEndingtime() {
        return $this->estimatedEndingtime;
    }

    public function setEstimatedEndingtime($estimatedEndingtime) {
        $this->estimatedEndingtime = $estimatedEndingtime;
    }

    
        
    public function getDatetimeSchedule() {
        return $this->datetimeSchedule;
    }

    public function setDatetimeSchedule(\DateTime $datetimeSchedule) {
        $this->datetimeSchedule = $datetimeSchedule;
    }

    public function getSmtp() {
        return $this->smtp;
    }

    public function setSmtp($smtp) {
        $this->smtp = $smtp;
    }

    public function getGroupingTemplate() {
        return $this->groupingTemplate;
    }

    public function setGroupingTemplate($groupingTemplate) {
        $this->groupingTemplate = $groupingTemplate;
    }

    public function getTimeInterval() {
        return $this->timeInterval;
    }

    public function setTimeInterval($timeInterval) {
        $this->timeInterval = $timeInterval;
    }

    public function getMailingListManager() {
        return $this->mailingListManager;
    }

    public function setMailingListManager($mailingListManager) {
        $this->mailingListManager = $mailingListManager;
    }
    
    public function getSendingList() {
        return $this->sendingList;
    }

    public function setSendingList($sendingList) {
        $this->sendingList = $sendingList;
    }

    public function getTimeIntervalTotal() {
        return $this->timeIntervalTotal;
    }

    public function setTimeIntervalTotal($timeIntervalTotal) {
        $this->timeIntervalTotal = $timeIntervalTotal;
    }
    
    public function getEmailsProcessed() {
        return $this->emailsProcessed;
    }

    public function setEmailsProcessed($emailsProcessed) {
        $this->emailsProcessed = $emailsProcessed;
    }

    public function getEmailsSent() {
        return $this->emailsSent;
    }

    public function setEmailsSent($emailsSent) {
        $this->emailsSent = $emailsSent;
    }

    public function getEmailsFailed() {
        return $this->emailsFailed;
    }

    public function setEmailsFailed($emailsFailed) {
        $this->emailsFailed = $emailsFailed;
    }

    public function getEmailsPending() {
        return $this->emailsPending;
    }

    public function setEmailsPending($emailsPending) {
        $this->emailsPending = $emailsPending;
    }

    
    public function getEmailsTotal() {
        return $this->emailsTotal;
    }

    public function setEmailsTotal($emailsTotal) {
        $this->emailsTotal = $emailsTotal;
    }
    
    public function getCombination() {
        return $this->combination;
    }

    public function setCombination($combination) {
        $this->combination = $combination;
    }
    
    function getScheduleEmailControl() {
        return $this->scheduleEmailControl;
    }

    function setScheduleEmailControl($scheduleEmailControl) {
        $this->scheduleEmailControl = $scheduleEmailControl;
    }

    function getEmailLayout() {
        return $this->emailLayout;
    }

    function setEmailLayout($emailLayout) {
        $this->emailLayout = $emailLayout;
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

