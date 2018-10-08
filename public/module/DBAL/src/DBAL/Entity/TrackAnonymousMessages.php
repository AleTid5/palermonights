<?php

namespace DBAL\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * An example of how to implement a role aware user entity.
 *
 * @ORM\Entity
 * @ORM\Table(name="track_anonymous_messages")
 *
 * @author Cristian Incarnato
 */

class TrackAnonymousMessages  {

    /**
     * @var int
     * @ORM\Id
     * @ORM\Column(type="integer")
     * 
     */
    protected $id;
    


         /**
     * @var string
     * @ORM\Column(type="string", length=200, unique=false, nullable=true)
     */
    protected $comment;
    
    
         /**
     * @var string
     * @ORM\Column(type="string", length=30, unique=false, nullable=true)
     */
    protected $state;
    
         /**
     * @var string
     * @ORM\Column(type="string", length=30, unique=false, nullable=true, name="updated_by")
     */
    protected $updatedBy;
    
    
     /**
     * @var \DateTime createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected $createdAt;
    /**
     * @var \DateTime updatedAt
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", name="updated_at", nullable=true)
     */
    protected $updatedAt;
    
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getComment() {
        return $this->comment;
    }

    public function setComment($comment) {
        $this->comment = $comment;
    }

    public function getState() {
        return $this->state;
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function getUpdatedBy() {
        return $this->updatedBy;
    }

    public function setUpdatedBy($updatedBy) {
        $this->updatedBy = $updatedBy;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\datetime $updatedAt) {
        $this->updatedAt = $updatedAt;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function setCreatedAt(\datetime $createdAt) {
        $this->createdAt = $createdAt;
    }


    


}

