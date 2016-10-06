<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation\ExclusionPolicy;
use JMS\Serializer\Annotation\Expose;
use JMS\Serializer\Annotation\Groups;


/**
 * Avatar
 *
 * @ORM\Table(name="avatar")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AvatarRepository")
 * @ORM\HasLifecycleCallbacks()
 * @ExclusionPolicy("all")
 */
class Avatar
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @Expose
     * @Groups({"Default","me","list","listOffer","user offers list","comment"})
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="web_path", type="string", length=255)
     * @Expose
     * @Groups({"Default","me","list","listOffer","user offers list","comment"})
     */
    private $webPath;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    public $path;

    public $file;
    private $old_path;

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
     * Set webPath
     *
     * @param string $webPath
     *
     * @return Avatar
     */
    public function setWebPath($webPath)
    {
        $this->webPath = $webPath;

        return $this;
    }

    /**
     * Get webPath
     *
     * @return string
     */
    public function getWebPath()
    {
        return $this->webPath;
    }

    /**
     * Set path
     *
     * @param string $path
     *
     * @return Avatar
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
     * @ORM\PreRemove()
     */
    public function preRemove(){
        if(file_exists($this->path))
            $this->old_path=$this->path;
    }
    /**
     * @ORM\PostRemove()
     */
    public function postRemove(){
        if(file_exists($this->old_path))
            unlink($this->old_path);
    }
    /**
     * @ORM\PreUpdate()
     */
    public function preUpdate(){
        if(file_exists($this->path))
           $this->old_path=$this->path;
    }
    /**
     * @ORM\postUpdate()
     */
    public function postUpdate(){
        if(file_exists($this->old_path))
            unlink($this->old_path);

    }
}
