<?php
// src/AppBundle/Entity/Trkpt.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;


/**
 *
 * TripPoint
 *
 * @ORM\Table(name="trkpt")
 * @ORM\Entity
 */
class Trkpt
{

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="lat", type="decimal")
     */
    private $lat;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="lon", type="decimal")
     */
    private $lon;

    /**
     * @var string
     *
     * @ORM\Column(name="ele", type="decimal")
     */
    private $ele;

    /**
     * @var string
     *
     * @ORM\Column(name="time", type="datetime")
     */
    private $time;



    /**
     * @ORM\ManyToOne(targetEntity="Trip", inversedBy="trkseg")
     * @ORM\JoinColumn(name="trip_id", referencedColumnName="id")
     */
    protected $trip;

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
     * @return TripPoint
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
     * @return TripPoint
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
     * @return TripPoint
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
     * @return TripPoint
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

    /**
     * Set trip
     *
     * @param \AppBundle\Entity\Trip $trip
     *
     * @return TripPoint
     */
    public function setTrip(\AppBundle\Entity\Trip $trip = null)
    {
        $this->trip = $trip;

        return $this;
    }

    /**
     * Get trip
     *
     * @return \AppBundle\Entity\Trip
     */
    public function getTrip()
    {
        return $this->trip;
    }
}
