<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 * Offer
 *
 * @ORM\Table(name="offer")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\OfferRepository")
 * @ExclusionPolicy("all")
 */
class Offer
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @Groups({"Default","listOffer","user offers list"})
     */
    private $id;



    /**
     * @var double
     *
     * @ORM\Column(name="value", type="decimal")
     * @Expose
     * @Groups({"Default","listOffer","user offers list"})
     */
    private $value;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
     * @ORM\JoinColumn(nullable=false)
     * @Expose
     * @Groups({"Default","listOffer","user offers list"})
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     * @Expose
     * @Groups({"Default","listOffer"})
     */
    private $buyer;

    /**
     * @var \DateTime
     * @ORM\Column(name="date", type="datetime")
     * @Expose
     * @Groups({"Default","listOffer","user offers list"})
     */
    private $date;

    /**
     * @var int
     * @ORM\Column(name="accept", type="integer")
     * @Expose
     * @Groups({"Default","listOffer","user offers list"})
     */
    private $accept;

    /**
     * @var int
     * @ORM\Column(name="activated" , type="integer")
     */
    private $activated;
    
    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }


    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Offer
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
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Offer
     */
    public function setProduct(\AppBundle\Entity\Product $product)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \AppBundle\Entity\Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * Set buyer
     *
     * @param \UserBundle\Entity\User $buyer
     *
     * @return Offer
     */
    public function setBuyer(\UserBundle\Entity\User $buyer)
    {
        $this->buyer = $buyer;

        return $this;
    }

    /**
     * Get buyer
     *
     * @return \UserBundle\Entity\User
     */
    public function getBuyer()
    {
        return $this->buyer;
    }

    /**
     * Set accept
     *
     * @param boolean $accept
     *
     * @return Offer
     */
    public function setAccept($accept)
    {
        $this->accept = $accept;

        return $this;
    }

    /**
     * Get accept
     *
     * @return boolean
     */
    public function getAccept()
    {
        return $this->accept;
    }

    /**
     * Set activated
     *
     * @param integer $activated
     *
     * @return Offer
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
