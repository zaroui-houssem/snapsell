<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Product
 *
 * @ORM\Table(name="product")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ProductRepository")
 * @ExclusionPolicy("all")
 */
class Product
{
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->listMedia = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @Groups({"Default","list","listOffer","products user list","user offers list","comment"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     * @Assert\NotBlank()
     * @Expose
     * @Groups({"Default","list","listOffer","products user list","user offers list","comment"})
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text")
     * @Assert\NotBlank()
     * @Expose
     * @Groups({"Default","list","listOffer","products user list","user offers list","comment"})
     */
    private $description;
    /**
     * @var string
     * @ORM\Column(name="hashtags", type="string")
     * @Expose
     * @Groups({"Default","list","products user list","user offers list","comment"})
     */
    private $hashtags;

    /**
     * @var  double
     * @ORM\Column(name="price", type="decimal",precision=10, scale=4)
     * @Expose
     * @Groups({"Default","list","listOffer","products user list","user offers list","comment"})
     * @Assert\Type(
     *     type="numeric",
     *     message="product.price.numeric"
     * )
     * @Assert\NotBlank()
     */
    private $price;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     * @Expose
     * @Groups({"Default","list","listOffer","products user list","user offers list","comment"})
     */
    private $date;
    
    /**
     * @var double
     *
     * @ORM\Column(name="longitude", type="decimal", precision=14, scale=10)
     * @Expose
     * @Groups({"Default","list","products user list","user offers list"})
     */
    private $longitude;

    /**
     * @var double
     *
     * @ORM\Column(name="latitude", type="decimal", precision=14, scale=10)
     * @Expose
     * @Groups({"Default","list","products user list","user offers list"})
     */
    private $latitude;
    
    /**
     * @var string
     * @ORM\Column(name="offerType", type="string")
     * @Assert\NotBlank()
     * @Expose
     * @Groups({"Default","list","listOffer","products user list","user offers list"})
     */
    private $offerType;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Media", mappedBy="product" ,cascade={"remove"})
     * @Expose
     * @Groups({"Default","list","listOffer","products user list","user offers list"})
     */
    private $listMedia;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotNull()
     * @Expose
     * @Groups({"Default","list","user offers list","comment"})
     */
    private $seller;

    /**
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Comment" ,mappedBy="product" ,cascade={"remove"})
     * @Expose
     * @Groups({"Default","list","listOffer","products user list","user offers list"})
     */
    private $comments;


    /**
     * @var int
     * @ORM\Column(name="sold_out" , type="integer")
     */
    public  $sold_out;

    /**
     * @var int
     * @ORM\Column(name="activated" , type="integer")
     */
    private $activated;

    public $media;
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
     * Set name
     *
     * @param string $name
     *
     * @return Product
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return Product
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get description
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     *
     * @return Product
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
     * @return Product
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
     * Add listMedia
     *
     * @param \AppBundle\Entity\Media $listMedia
     *
     * @return Product
     */
    public function addListMedia(\AppBundle\Entity\Media $listMedia)
    {
        $this->listMedia[] = $listMedia;

        return $this;
    }

    /**
     * Remove listMedia
     *
     * @param \AppBundle\Entity\Media $listMedia
     */
    public function removeListMedia(\AppBundle\Entity\Media $listMedia)
    {
        $this->listMedia->removeElement($listMedia);
    }

    /**
     * Get listMedia
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getListMedia()
    {
        return $this->listMedia;
    }


    /**
     * Add comment
     *
     * @param \AppBundle\Entity\Comment $comment
     *
     * @return Product
     */
    public function addComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments[] = $comment;

        return $this;
    }

    /**
     * Remove comment
     *
     * @param \AppBundle\Entity\Comment $comment
     */
    public function removeComment(\AppBundle\Entity\Comment $comment)
    {
        $this->comments->removeElement($comment);
    }

    /**
     * Get comments
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getComments()
    {
        return $this->comments;
    }

    

    /**
     * Set hashtags
     *
     * @param string $hashtags
     *
     * @return Product
     */
    public function setHashtags($hashtags)
    {
        $this->hashtags = $hashtags;

        return $this;
    }

    /**
     * Get hashtags
     *
     * @return string
     */
    public function getHashtags()
    {
        return $this->hashtags;
    }

    /**
     * Set price
     *
     * @param string $price
     *
     * @return Product
     */
    public function setPrice($price)
    {
        $this->price = $price;

        return $this;
    }

    /**
     * Get price
     *
     * @return string
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Product
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set offerType
     *
     * @param string $offerType
     *
     * @return Product
     */
    public function setOfferType($offerType)
    {
        $this->offerType = $offerType;

        return $this;
    }

    /**
     * Get offerType
     *
     * @return string
     */
    public function getOfferType()
    {
        return $this->offerType;
    }

    
    public function getSoldOut()
    {
        return $this->sold_out;
    }

    /**
     * Set seller
     *
     * @param \UserBundle\Entity\User $seller
     *
     * @return Product
     */
    public function setSeller(\UserBundle\Entity\User $seller)
    {
        $this->seller = $seller;

        return $this;
    }

    /**
     * Get seller
     *
     * @return \UserBundle\Entity\User
     */
    public function getSeller()
    {
        return $this->seller;
    }

    /**
     * Set soldOut
     *
     * @param integer $soldOut
     *
     * @return Product
     */
    public function setSoldOut($soldOut)
    {
        $this->sold_out = $soldOut;

        return $this;
    }

    /**
     * Set activated
     *
     * @param integer $activated
     *
     * @return Product
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
