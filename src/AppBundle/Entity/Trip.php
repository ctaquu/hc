<?php
// src/AppBundle/Entity/Trip.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity
 * @ORM\Table(name="trip")
 * @Vich\Uploadable
 */
class Trip
{

    public function __construct()
    {
        $this->trkseg = new ArrayCollection();
    }

    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     *
     * @ORM\Column(type="string", length=100)
     */
    protected $name;

    /**
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="trips")
     * @ORM\JoinColumn(name="fos_user_id", referencedColumnName="id")
     */
    protected $fos_user;

    /**
     * @ORM\OneToMany(targetEntity="Trkpt", mappedBy="trip", cascade={"persist"})
     *
     */
    public $trkseg;

    /**
     * @ORM\Column(type="string", length=65535)
     */
    protected $points_json = '{}';

    /**
     *
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="trip_xml", fileNameProperty="xmlName")
     * @var File
     *
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"application/xml"},
     *     mimeTypesMessage = "Please upload a valid GPX"
     * )
     */
    private $xmlFile;

    /**
     *
     * @ORM\Column(type="string", length=255)
     *
     * @var string
     */
    private $xmlName;

    /**
     *
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * If manually uploading a file (i.e. not using Symfony Form) ensure an instance
     * of 'UploadedFile' is injected into this setter to trigger the  update. If this
     * bundle's configuration parameter 'inject_on_load' is set to 'true' this setter
     * must be able to accept an instance of 'File' as the bundle will inject one here
     * during Doctrine hydration.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile $xml
     */
    public function setXmlFile(File $xml = null)
    {
        $this->xmlFile = $xml;

        if ($xml) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTime('now');
        }
    }

    /**
     * @return File
     */
    public function getXmlFile()
    {
        return $this->xmlFile;
    }

    /**
     * @param string $xmlName
     */
    public function setXmlName($xmlName)
    {
        $this->xmlName = $xmlName;
    }

    /**
     * @return string
     */
    public function getXmlName()
    {
        return $this->xmlName;
    }


    /**
     * Get id
     *
     * @return integer
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
     * @return Trip
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
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Trip
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set user
     *
     * @param \AppBundle\Entity\User $user
     *
     * @return Trip
     */
    public function setUser(\AppBundle\Entity\User $user = null)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \AppBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * Set fosUser
     *
     * @param \AppBundle\Entity\User $fosUser
     *
     * @return Trip
     */
    public function setFosUser(\AppBundle\Entity\User $fosUser = null)
    {
        $this->fos_user = $fosUser;

        return $this;
    }

    /**
     * Get fosUser
     *
     * @return \AppBundle\Entity\User
     */
    public function getFosUser()
    {
        return $this->fos_user;
    }

    /**
     * Set pointsJson
     *
     * @param string $pointsJson
     *
     * @return Trip
     */
    public function setPointsJson($pointsJson)
    {
        $this->points_json = $pointsJson;

        return $this;
    }

    /**
     * Get pointsJson
     *
     * @return string
     */
    public function getPointsJson()
    {
        return $this->points_json;
    }

    /**
     * Add trkseg
     *
     * @param \AppBundle\Entity\Trkpt $trkseg
     *
     * @return Trip
     */
    public function addTrkseg(\AppBundle\Entity\Trkpt $trkseg)
    {
        $this->trkseg[] = $trkseg;

        return $this;
    }

    /**
     * Remove trkseg
     *
     * @param \AppBundle\Entity\Trkpt $trkseg
     */
    public function removeTrkseg(\AppBundle\Entity\Trkpt $trkseg)
    {
        $this->trkseg->removeElement($trkseg);
    }

    /**
     * Get trkseg
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTrkseg()
    {
        return $this->trkseg;
    }
}
