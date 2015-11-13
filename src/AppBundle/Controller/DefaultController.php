<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Trip;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

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
    public function newAction(Request $request)
    {

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        $user = $this->getUser();


        // create a task and give it some dummy data for this example
        $trip = new Trip();

        $form = $this->createFormBuilder($trip)
            ->setMethod('POST')
            ->add('name', 'text')
            ->add('imageFile', 'file')
            ->add('save', 'submit', array('label' => 'Create Task'))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {

            $trip->setUser($user);

            $em = $this->getDoctrine()->getManager();

            $em->persist($trip);
            $em->flush();

            return $this->redirect($this->generateUrl('trips'));
        }

        return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
