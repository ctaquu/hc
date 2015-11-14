<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Trip;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;

class TripController extends Controller
{

    /**
     * listing all trips
     * @return Response
     */
    public function indexAction()
    {
        //TODO: make it in one place to rule them all!!
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        return $this->render('default/index.html.twig');
    }

    /**
     * show one trip
     * @param $slug trip id
     * @return Response
     */
    public function showAction($slug)
    {
        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        return new Response('showAction... ' . $slug);
        // .....
    }

    /**
     * adding new trip using .gpx file
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function createAction(Request $request)
    {

        if (!$this->get('security.context')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw new AccessDeniedException();
        }

        // get authenticated user object
        $user = $this->getUser();


        // create a task and give it some dummy data for this example
        $trip = new Trip();

        // generate form
        $form = $this->createFormBuilder($trip)
            ->setMethod('POST')
            ->add('name', 'text')
            ->add('imageFile', 'file')
            ->add('save', 'submit', array('label' => 'Create Task'))
            ->getForm();

        // make form parse data from request
        $form->handleRequest($request);

        // if data submitted
        if ($form->isValid()) {

            // set params
            $trip->setFosUser($user);

            // get entity manager
            $em = $this->getDoctrine()->getManager();

            // save to db
            $em->persist($trip);
            $em->flush();

            // redirect to show updated list of trips
            return $this->redirect($this->generateUrl('trip_list'));
        }

        // render view
        return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
