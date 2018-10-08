<?php

namespace DBAL\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * An example of how to implement a role aware user entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="store_ga")
 *
 * @author Cristian Incarnato
 */

class StoreGa  {

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    

     /**
     * @var integer
     * @ORM\Column(type="integer", length=10, unique=false, nullable=true)
     */
    protected $visits;
    
       /**
     * @var integer
     * @ORM\Column(type="integer", length=10, unique=false, nullable=true)
     */
    protected $visitors;
    
         /**
     * @var integer
     * @ORM\Column(type="integer", length=10, unique=false, nullable=true)
     */
    protected $bounce;

    /**
     * 
     * @var datetime
     * @ORM\Column(type="datetime", unique=false, nullable=true)
     */
    protected $date;
    
     /**
     * @var datetime
     * @ORM\Column(type="datetime", unique=false,  nullable=true, name="created_at")
     */
    protected $createdAt;
    
     public function __construct() {
        $this->publications = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }
    
  
    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function setCreatedAt(\datetime $createdAt) {
        $this->createdAt = $createdAt;
    }

    public function getVisits() {
        return $this->visits;
    }

    public function setVisits($visits) {
        $this->visits = $visits;
    }

    public function getVisitors() {
        return $this->visitors;
    }

    public function setVisitors($visitors) {
        $this->visitors = $visitors;
    }

    public function getBounce() {
        return $this->bounce;
    }

    public function setBounce($bounce) {
        $this->bounce = $bounce;
    }

    public function getDate() {
        return $this->date;
    }

    public function setDate(\datetime $date) {
        $this->date = $date;
    }

    


}

