<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("trips", name="trips")
     */
    public function tripsAction()
    {
        return $this->render('default/trips.html.twig');
    }

    /**
     * @Route("new", name="new trip")
     */
    public function newAction()
    {
        return $this->render('default/new.html.twig');
    }
}
