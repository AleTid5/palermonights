<?php

namespace DBAL\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * An example of how to implement a role aware user entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="face_token")
 *
 * @author Cristian Incarnato
 */

class FaceToken  {

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
    protected $accessToken;
    
       /**
     * @var integer
     * @ORM\Column(type="integer", length=10, unique=false, nullable=true)
     */
    protected $expiresAt;
    
 

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

    public function getAccessToken() {
        return $this->accessToken;
    }

    public function setAccessToken($accessToken) {
        $this->accessToken = $accessToken;
    }

    public function getExpiresAt() {
        return $this->expiresAt;
    }

    public function setExpiresAt($expiresAt) {
        $this->expiresAt = $expiresAt;
    }

            
 
    public function getDate() {
        return $this->date;
    }

    public function setDate(\datetime $date) {
        $this->date = $date;
    }

    


}

