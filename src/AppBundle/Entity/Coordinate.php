<?php
// src/AppBundle/Entity/Coordinate.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="coordinate")
 */
class Coordinate
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="decimal")
     */
    protected $lat;

    /**
     * @ORM\Column(type="decimal")
     */
    protected $lon;

    /**
     * @ORM\Column(type="decimal")
     */
    protected $ele;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTime
     */
    protected $time;

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
     * Set lat
     *
     * @param string $lat
     *
     * @return Coordinate
     */
    public function setLat($lat)
    {
        $this->lat = $lat;

        return $this;
    }

    /**
     * Get lat
     *
     * @return string
     */
    public function getLat()
    {
        return $this->lat;
    }

    /**
     * Set lon
     *
     * @param string $lon
     *
     * @return Coordinate
     */
    public function setLon($lon)
    {
        $this->lon = $lon;

        return $this;
    }

    /**
     * Get lon
     *
     * @return string
     */
    public function getLon()
    {
        return $this->lon;
    }

    /**
     * Set ele
     *
     * @param string $ele
     *
     * @return Coordinate
     */
    public function setEle($ele)
    {
        $this->ele = $ele;

        return $this;
    }

    /**
     * Get ele
     *
     * @return string
     */
    public function getEle()
    {
        return $this->ele;
    }

    /**
     * Set time
     *
     * @param \DateTime $time
     *
     * @return Coordinate
     */
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }

    /**
     * Get time
     *
     * @return \DateTime
     */
    public function getTime()
    {
        return $this->time;
    }
}
