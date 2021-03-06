<?php

namespace UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use JMS\Serializer\Annotation\Groups;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="UserBundle\Repository\UserRepository")
 * @UniqueEntity(fields="username")
 * @ExclusionPolicy("all")
 */
class User implements UserInterface
{

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @Groups({"Default","me","list","listOffer","vote","products user list","user offers list","comment"})
     */
    private $id;


    public function __construct()
    {
        $this->salt = base_convert(sha1(uniqid(mt_rand(), true)), 16, 36);
        $this->roles = array("ROLE_USER");
    }

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=50, unique=true)
     * @Assert\NotBlank()
     * @Assert\Regex(
     *     pattern="/^\d+/",
     *     match=false,
     *     message="user.name.nonumber"
     * )
     * @Expose
     * @Groups({"Default","me","list","listOffer","vote","products user list","user offers list","comment"})
     */
    public $username;



    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255 ,nullable=true)
     
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="salt", type="string", length=255)
     * @Expose
     * @Groups({"me"})
     */
    private $salt;

    /**
     * @ORM\Column(name="roles" ,type="array")
     * @Expose
     * @Groups({"me"})
     *
     */
    private $roles ;

    /**
     * @var string
     * @ORM\Column(name="facebook_id", type="string", length=255 , nullable=true)
     * @Expose
     * @Groups({"me"})
     */
    private $facebookId;
    /**
     * @var string
     * @ORM\Column(name="facebook_profile", type="string", length=255 , nullable=true)
     * @Expose
     * @Groups({"Default","me"})
     */
    private $facebookProfile;
    /**
     * @var string
     * @ORM\Column(name="google_id", type="string", length=255, nullable=true)
     * @Expose
     * @Groups({"me"})
     */
    private $googleId;

    /**
     * @var string
     * @ORM\Column(name="google_profile", type="string", length=255 , nullable=true)
     * @Expose
     * @Groups({"Default","me"})
     */
    private $googleProfile;
    /**
     * @var string
     * @ORM\Column(name="twitter_id", type="string", length=255 , nullable=true)
     * @Expose
     * @Groups({"me"})
     */
    private $twitterId;

    /**
     * @var string
     * @ORM\Column(name="twitter_profile", type="string", length=255 , nullable=true)
     * @Expose
     * @Groups({"Default","me"})
     */
    private $twitterProfile;

    /**
     * @var string
     * @ORM\Column(name="gcm_registration_id", type="string",nullable=true)
     */
    private $gcm_registration_id;
    /**
     * @var int
     * @ORM\Column(name="phone_number", type="integer",nullable=true)
     * @Expose
     * @Groups({"Default","me"})
     * @Assert\Type(
     *     type="numeric",
     *     message="user.phone_number.numeric"
     * )
     */
    private $phone_number;
    /**
     *
     * @ORM\OneToOne(targetEntity="AppBundle\Entity\Avatar" ,cascade={"remove"})
     * @ORM\JoinColumn(nullable=true)
     * @Expose
     * @Groups({"Default","me","list","listOffer","vote","user offers list","comment"})
     */
    private $avatar;

    /**
     * @var string
     * @ORM\Column(name="address", type="string" ,nullable=true)
     * @Expose
     * @Groups({"Default","me","list","listOffer","vote","user offers list","comment"})
     */
    private $address;
    /**
     * @var double
     * @ORM\Column(name="longitude", type="decimal", precision=14, scale=10 ,nullable=true)
     * @Expose
     * @Groups({"Default","me"})
     */
    private $longitude;

    /**
     * @var double
     * @ORM\Column(name="latitude", type="decimal", precision=14, scale=10 ,nullable=true)
     * @Expose
     * @Groups({"Default","me"})
     */
    private $latitude;

    /**
     * @var string
     * @ORM\Column(name="signUpType", type="string" ,length=50)
     * @Expose
     * @Groups({"me"})
     */
    private $signUpType;

    /**
     * @var float
     * @ORM\Column(name="vote" , type="float" ,nullable=true)
     * @Expose
     * @Groups({"Default","me","list","user offers list"})
     */
    private $vote;

    /**
     * @var float
     * @ORM\Column(name="nb_vote" , type="float",nullable=true)
     */
    private $nb_vote;
    
    /**
     * @var int
     * @ORM\Column(name="activated" , type="integer")
     */
    private $activated;

    public $img;
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }


    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }


    /**
     * Get salt
     *
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }



    /**
     * Returns the roles granted to the user.
     *
     * <code>
     * public function getRoles()
     * {
     *     return array('ROLE_USER');
     * }
     * </code>
     *
     * Alternatively, the roles might be stored on a ``roles`` property,
     * and populated in any number of different ways when the user object
     * is created.
     *
     * @return (Role|string)[] The user roles
     */
    public function getRoles()
    {
        return $this->roles;
    }
    /**
    * Set roles
    *
    * @param array $roles
    *
    * @return User
    */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Removes sensitive data from the user.
     *
     * This is important if, at any given point, sensitive information like
     * the plain-text password is stored on this object.
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * Set salt
     *
     * @param string $salt
     *
     * @return User
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;

        return $this;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return User
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     *
     * @return User
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set address
     *
     * @param string $address
     *
     * @return User
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }



    /**
     * Set avatar
     *
     * @param \AppBundle\Entity\Avatar $avatar
     *
     * @return User
     */
    public function setAvatar(\AppBundle\Entity\Avatar $avatar = null)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get avatar
     *
     * @return \AppBundle\Entity\Avatar
     */
    public function getAvatar()
    {
        return $this->avatar;
    }

    /**
     * Set facebookID
     *
     * @param string $facebookID
     *
     * @return User
     */
    public function setFacebookID($facebookID)
    {
        $this->facebookId = $facebookID;

        return $this;
    }

    /**
     * Get facebookID
     *
     * @return string
     */
    public function getFacebookID()
    {
        return $this->facebookId;
    }

    /**
     * Set googleID
     *
     * @param string $googleID
     *
     * @return User
     */
    public function setGoogleID($googleID)
    {
        $this->googleId = $googleID;

        return $this;
    }

    /**
     * Get googleID
     *
     * @return string
     */
    public function getGoogleID()
    {
        return $this->googleId;
    }

    /**
     * Set twitterID
     *
     * @param string $twitterID
     *
     * @return User
     */
    public function setTwitterID($twitterID)
    {
        $this->twitterId = $twitterID;

        return $this;
    }

    /**
     * Get twitterID
     *
     * @return string
     */
    public function getTwitterID()
    {
        return $this->twitterId;
    }

    /**
     * Set phoneNumber
     *
     * @param integer $phoneNumber
     *
     * @return User
     */
    public function setPhoneNumber($phoneNumber)
    {
        $this->phone_number = $phoneNumber;

        return $this;
    }

    /**
     * Get phoneNumber
     *
     * @return integer
     */
    public function getPhoneNumber()
    {
        return $this->phone_number;
    }

    /**
     * Set signUpType
     *
     * @param string $signUpType
     *
     * @return User
     */
    public function setSignUpType($signUpType)
    {
        $this->signUpType = $signUpType;

        return $this;
    }

    /**
     * Get signUpType
     *
     * @return string
     */
    public function getSignUpType()
    {
        return $this->signUpType;
    }

    /**
     * Set vote
     *
     * @param float $vote
     *
     * @return User
     */
    public function setVote($vote)
    {
        $this->vote = $vote;

        return $this;
    }

    /**
     * Get vote
     *
     * @return float
     */
    public function getVote()
    {
        return $this->vote;
    }

    /**
     * Set nbVote
     *
     * @param float $nbVote
     *
     * @return User
     */
    public function setNbVote($nbVote)
    {
        $this->nb_vote = $nbVote;

        return $this;
    }

    /**
     * Get nbVote
     *
     * @return float
     */
    public function getNbVote()
    {
        return $this->nb_vote;
    }

    /**
     * Set facebookProfile
     *
     * @param string $facebookProfile
     *
     * @return User
     */
    public function setFacebookProfile($facebookProfile)
    {
        $this->facebookProfile = $facebookProfile;

        return $this;
    }

    /**
     * Get facebookProfile
     *
     * @return string
     */
    public function getFacebookProfile()
    {
        return $this->facebookProfile;
    }

    /**
     * Set googleProfile
     *
     * @param string $googleProfile
     *
     * @return User
     */
    public function setGoogleProfile($googleProfile)
    {
        $this->googleProfile = $googleProfile;

        return $this;
    }

    /**
     * Get googleProfile
     *
     * @return string
     */
    public function getGoogleProfile()
    {
        return $this->googleProfile;
    }

    /**
     * Set twitterProfile
     *
     * @param string $twitterProfile
     *
     * @return User
     */
    public function setTwitterProfile($twitterProfile)
    {
        $this->twitterProfile = $twitterProfile;

        return $this;
    }

    /**
     * Get twitterProfile
     *
     * @return string
     */
    public function getTwitterProfile()
    {
        return $this->twitterProfile;
    }

    /**
     * Set gcmRegistrationId
     *
     * @param string $gcmRegistrationId
     *
     * @return User
     */
    public function setGcmRegistrationId($gcmRegistrationId)
    {
        $this->gcm_registration_id = $gcmRegistrationId;

        return $this;
    }

    /**
     * Get gcmRegistrationId
     *
     * @return string
     */
    public function getGcmRegistrationId()
    {
        return $this->gcm_registration_id;
    }

    /**
     * Set activated
     *
     * @param integer $activated
     *
     * @return User
     */
    public function setActivated($activated)
    {
        $this->activated = $activated;

        return $this;
    }

    /**
     * Get activated
     *
     * @return integer
     */
    public function getActivated()
    {
        return $this->activated;
    }
}
