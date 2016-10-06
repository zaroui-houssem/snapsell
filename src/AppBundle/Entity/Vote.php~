<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Vote
 *
 * @ORM\Table(name="vote")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\VoteRepository")
 * @ExclusionPolicy("all")
 */
class Vote
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @Groups({"Default","vote"})
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="value", type="float")
     * @Expose
     * @Groups({"Default","vote"})
     *
     * @Assert\Range(
     *      min = 1,
     *      max = 5,
     *      minMessage = "Vote must be at least {{ limit }}",
     *      maxMessage = "Vote cannot be more than {{ limit }}"
     * )
     *
     */
    private $value;


    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User" )
     * @ORM\JoinColumn(nullable=false)
     * @Expose
     * @Groups({"Default","vote"})
     */
    private $buyer;

    /**
     * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User" )
     * @ORM\JoinColumn(nullable=false)
     */
    private $seller;

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
     * @param float $value
     *
     * @return Vote
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    

    
    /**
     * Set buyer
     *
     * @param \UserBundle\Entity\User $buyer
     *
     * @return Vote
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
     * Set seller
     *
     * @param \UserBundle\Entity\User $seller
     *
     * @return Vote
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
}
