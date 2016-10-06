<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;

/**
 * Comment
 *
 * @ORM\Table(name="comment")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CommentRepository")
 * @ExclusionPolicy("all")
 */
class Comment
{
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
     * @ORM\Column(name="value", type="text")
     * @Expose
     * @Groups({"Default","list","listOffer","products user list","user offers list","comment"})
     */
    private $value;
    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product" , inversedBy="comments")
     * @ORM\JoinColumn(nullable=false)
     * @Expose
     * @Groups({"Default","comment"})
     */
    private $product;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     * @Expose
     * @Groups({"Default","list","listOffer","products user list","user offers list","comment"})
     */
    private $user;

    /**
     * @var \DateTime
     * @ORM\Column(name="date", type="datetime")
     * @Expose
     * @Groups({"Default","list","listOffer","products user list","user offers list","comment"})
     */
    
    private $date;

    /**
     * @var int
     * @ORM\Column(name="activated" , type="integer")
     * @Expose
     * @Groups({"Default","list","listOffer","products user list","user offers list","comment"})
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

    /**
     * Set value
     *
     * @param string $value
     *
     * @return Comment
     */
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
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Comment
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
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Comment
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
     * Set user
     *
     * @param \UserBundle\Entity\User $user
     *
     * @return Comment
     */
    public function setUser(\UserBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \UserBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set activated
     *
     * @param integer $activated
     *
     * @return Comment
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
