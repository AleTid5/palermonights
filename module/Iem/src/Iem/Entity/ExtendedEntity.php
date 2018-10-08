<?php

namespace Iem\Entity;

use Doctrine\Common\Util\ClassUtils;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Doctrine\ORM\Proxy\Proxy;
use Gedmo\Mapping\Annotation as Gedmo;
use Zend\InputFilter\InputFilter;
use Zend\Form\Annotation;
class ExtendedEntity extends \Iem\Entity\AbstractEntity {

    /**
     * 
     * @ORM\ManyToOne(targetEntity="CdiUser\Entity\User")
     * @Annotation\Exclude()
     */
    protected $createdBy;

    /**
     * 
     * @ORM\ManyToOne(targetEntity="CdiUser\Entity\User")
     * @Annotation\Exclude()
     */
    protected $lastUpdatedBy;

    /**
     * @var \DateTime createdAt
     *
     * @Gedmo\Timestampable(on="create")
     * @ORM\Column(type="datetime", name="created_at")
     * @Annotation\Exclude()
     */
    protected $createdAt;

    /**
     * @var \DateTime updatedAt
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(type="datetime", name="updated_at", nullable=true)
     * @Annotation\Exclude()
     */
    protected $updatedAt;

    public function getId() {
        return $this->id;
    }

    public function getCreatedBy() {
        return $this->createdBy;
    }

    public function setCreatedBy($createdBy) {
        $this->createdBy = $createdBy;
    }

    public function getLastUpdatedBy() {
        return $this->lastUpdatedBy;
    }

    public function setLastUpdatedBy($lastUpdatedBy) {
        $this->lastUpdatedBy = $lastUpdatedBy;
    }

    public function getCreatedAt() {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt) {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt() {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt) {
        $this->updatedAt = $updatedAt;
    }

}
