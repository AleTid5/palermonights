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
 * @ORM\Table(name="contacts_login")
 *
 * @author Cristian Incarnato
 */

class ContactLogin extends \Iem\Entity\ExtendedEntity implements InputFilterAwareInterface {

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
     * @ORM\Column(type="string", length=50, unique=false, nullable=true)
     */
    protected $fullname;

    /**
     * @var string
     * @ORM\Column(type="text", length=50,unique=false, nullable=true)
     */
    protected $email;

    /**
     * @var \DateTime
     * @ORM\Column(type="datetime", length=20,unique=false, nullable=true)
     */
    protected $birthdate;
    
      /**
     * @var string
     * @ORM\Column(type="string", length=20,unique=false, nullable=true, name="birthday_num")
     */
    protected $birthdayNum;
    
    
    /**
     * @var string
     * @ORM\Column(type="string", length=20,unique=false, nullable=true, name="birthday_text")
     */
    protected $birthdayText;
    
     /**
     * @var string
     * @ORM\Column(type="string", length=3,unique=false, nullable=true)
     */
    protected $age;

    /**
     * @var string
     * @ORM\Column(type="string", length=40,unique=true, nullable=false, name="facebook_id")
     */
    protected $facebookId;

    /**
     * @var string
     * @ORM\Column(type="string", length=50,unique=false, nullable=true, name="facebook_email")
     */
    protected $facebookEmail;

    /**
     * @var string
     * @ORM\Column(type="string", length=30,unique=false, nullable=true,name="facebook_user")
     */
    protected $facebookUser;

    /**
     * @var string
     * @ORM\Column(type="string", length=120,unique=false, nullable=true, name="facebook_url")
     */
    protected $facebookUrl;

    /**
     * @var string
     * @ORM\Column(type="string", length=30,unique=false, nullable=true, name="facebook_country")
     */
    protected $facebookCountry;

    /**
     * @var string
     * @ORM\Column(type="string", length=50,unique=false, nullable=true, name="facebook_province")
     */
    protected $facebookProvince;

    /**
     * @var string
     * @ORM\Column(type="string", length=50,unique=false, nullable=true, name="facebook_city")
     */
    protected $facebookCity;
    
     /**
     * @var string
     * @ORM\Column(type="string", length=50,unique=false, nullable=true, name="facebook_neighborhood")
     */
    protected $facebookNeighborhood;
    
    
       /**
     * @var string
     * @ORM\Column(type="string", length=50,unique=false, nullable=true, name="facebook_location_name")
     */
    protected $facebookLocationName;
    
       /**
     * @var string
     * @ORM\Column(type="string", length=50,unique=false, nullable=true, name="facebook_location_id")
     */
    protected $facebookLocationId;
    
    
    
        /**
     * @var string
     * @ORM\Column(type="string", length=50,unique=false, nullable=true, name="facebook_hometown_name")
     */
    protected $facebookHometownName;
    
       /**
     * @var string
     * @ORM\Column(type="string", length=50,unique=false, nullable=true, name="facebook_hometown_id")
     */
    protected $facebookHometownId;

    /**
     * @var string
     * @ORM\Column(type="string", length=50,unique=false, nullable=true, name="facebook_friend_fullname")
     */
    protected $facebookFriendFullname;

    /**
     * @var string
     * @ORM\Column(type="string", length=50,unique=false, nullable=true, name="facebook_friend_username")
     */
    protected $facebookFriendUsername;
    
      /**
     * @var string
     * @ORM\Column(type="string", length=50,unique=false, nullable=true)
     */
    protected $origin;

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

    public function getLastname() {
        return $this->lastname;
    }

    public function setLastname($lastname) {
        $this->lastname = $lastname;
    }

    public function getFullname() {
        return $this->fullname;
    }

    public function setFullname($fullname) {
        $this->fullname = $fullname;
    }

    public function getEmail() {
        return $this->email;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function getBirthdate() {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTime $birthdate) {
        $this->birthdate = $birthdate;
    }

            public function getFacebookId() {
        return $this->facebookId;
    }

    public function setFacebookId($facebookId) {
        $this->facebookId = $facebookId;
    }

    public function getFacebookEmail() {
        return $this->facebookEmail;
    }

    public function setFacebookEmail($facebookEmail) {
        $this->facebookEmail = $facebookEmail;
    }

    public function getFacebookUser() {
        return $this->facebookUser;
    }

    public function setFacebookUser($facebookUser) {
        $this->facebookUser = $facebookUser;
    }

    public function getFacebookUrl() {
        return $this->facebookUrl;
    }

    public function setFacebookUrl($facebookUrl) {
        $this->facebookUrl = $facebookUrl;
    }

    public function getFacebookCountry() {
        return $this->facebookCountry;
    }

    public function setFacebookCountry($facebookCountry) {
        $this->facebookCountry = $facebookCountry;
    }

    public function getFacebookProvince() {
        return $this->facebookProvince;
    }

    public function setFacebookProvince($facebookProvince) {
        $this->facebookProvince = $facebookProvince;
    }

    public function getFacebookCity() {
        return $this->facebookCity;
    }

    public function setFacebookCity($facebookCity) {
        $this->facebookCity = $facebookCity;
    }

    public function getFacebookFriendFullname() {
        return $this->facebookFriendFullname;
    }

    public function setFacebookFriendFullname($facebookFriendFullname) {
        $this->facebookFriendFullname = $facebookFriendFullname;
    }

    public function getFacebookFriendUsername() {
        return $this->facebookFriendUsername;
    }

    public function setFacebookFriendUsername($facebookFriendUsername) {
        $this->facebookFriendUsername = $facebookFriendUsername;
    }

    public function getOrigin() {
        return $this->origin;
    }

    public function setOrigin($origin) {
        $this->origin = $origin;
    }
    
    public function getFacebookLocationName() {
        return $this->facebookLocationName;
    }

    public function setFacebookLocationName($facebookLocationName) {
        $this->facebookLocationName = $facebookLocationName;
    }

    public function getFacebookLocationId() {
        return $this->facebookLocationId;
    }

    public function setFacebookLocationId($facebookLocationId) {
        $this->facebookLocationId = $facebookLocationId;
    }

    public function getFacebookHometownName() {
        return $this->facebookHometownName;
    }

    public function setFacebookHometownName($facebookHometownName) {
        $this->facebookHometownName = $facebookHometownName;
    }

    public function getFacebookHometownId() {
        return $this->facebookHometownId;
    }

    public function setFacebookHometownId($facebookHometownId) {
        $this->facebookHometownId = $facebookHometownId;
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
    public function getAge() {
        return $this->age;
    }

    public function setAge($age) {
        $this->age = $age;
    }

    public function getBirthdayNum() {
        return $this->birthdayNum;
    }

    public function setBirthdayNum($birthdayNum) {
        $this->birthdayNum = $birthdayNum;
    }

    
    public function getBirthdayText() {
        return $this->birthdayText;
    }

    public function setBirthdayText($birthdayText) {
        $this->birthdayText = $birthdayText;
    }

    
    public function getFacebookNeighborhood() {
        return $this->facebookNeighborhood;
    }

    public function setFacebookNeighborhood($facebookNeighborhood) {
        $this->facebookNeighborhood = $facebookNeighborhood;
    }




}

