<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;


/**
 * Media
 *
 * @ORM\Table(name="media")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\MediaRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ExclusionPolicy("all")
 */
class Media
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
     * @ORM\Column(name="name", type="string", length=255)
     * @Expose
     * @Groups({"Default","list","listOffer","products user list","user offers list","comment"})
     */
    private $name;
    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    public $path;



    /**
     * @var string
     *
     * @ORM\Column(name="webPath", type="string", length=255)
     * @Expose
     * @Groups({"Default","list","listOffer","products user list","user offers list","comment"})
     */
    public $web_path;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product" ,inversedBy="listMedia" )
     * @ORM\JoinColumn(nullable=false)
     */
    private $product;

    /**
     * @var int
     * @ORM\Column(name="activated" , type="integer")
     */
    private $activated;
    
    /**
     * @ORM\PostRemove()
     */
    public function postRemove(){
        if(file_exists($this->path))
            unlink($this->path);
    }

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
     * Set product
     *
     * @param \AppBundle\Entity\Product $product
     *
     * @return Media
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
     * Set webPath
     *
     * @param string $webPath
     *
     * @return Media
     */
    public function setWebPath($webPath)
    {
        $this->web_path = $webPath;

        return $this;
    }

    /**
     * Get webPath
     *
     * @return string
     */
    public function getWebPath()
    {
        return $this->web_path;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Media
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
     * Set path
     *
     * @param string $path
     *
     * @return Media
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set activated
     *
     * @param integer $activated
     *
     * @return Media
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
