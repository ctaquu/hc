<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Trip;
use AppBundle\Entity\Trkpt;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\Validator\Constraints as Assert; //TODO: validation!

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

        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($this->getUser());

        return $this->render('trip/index.html.twig', [
            "trips"     => $user->getTrips(),
        ]);
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

        // get trip by id/slug
        $trip = $this->getDoctrine()
            ->getRepository('AppBundle:Trip')
            ->find($slug);

        // json to php object from db column (trip.points_json)
        $points = json_decode($trip->getPointsJson());

        return $this->render('trip/one.html.twig', [
            'points'    => $points->trkpt,
            "trip"      => $trip,
        ]);

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
        $trip = new Trip;

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

            // set creator
            $trip->setFosUser($user);

            // get entity manager
            $em = $this->getDoctrine()->getManager();

            // save to db
            $em->persist($trip);

            // save trips in db
            $this->create_trip_points($trip, 'json');
//            $this->create_trip_points($trip);

//            // set points
//            $xmlDataUrl = $trip->getImageName();
//
//            // get xml text data
//            $xmlData = file_get_contents("../web/xml/trips/$xmlDataUrl");
//
//            // then into php object
//            $xml = simplexml_load_string($xmlData);
//
//            // and into trip we go...
//            $trip->setPointsJson(json_encode($xml->trk->trkseg));

            $em->flush();

            // preview trip
            return $this->redirect($this->generateUrl('trip_show', ['slug' => $trip->getId()]));

            // redirect to show updated list of trips
//            return $this->redirect($this->generateUrl('trip_list'));
        }

        // render view
        return $this->render('trip/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    /**
     * save trips in db -> two options: - json (in trip table as string(json) column: points_json)
     *                                  - db   (in separate table: Trkpt, each point as new row)
     * @param string $method
     */
    private function create_trip_points($trip, $method='db')
    {
        // get trip's file name from db
        $xmlDataUrl = $trip->getImageName();

        // get xml text data
        $xmlData = file_get_contents("../web/xml/trips/$xmlDataUrl");

        // then into php object
        $xml = simplexml_load_string($xmlData);

//        if(!isset($xml->trk->trkseg->trkpt))
//            throw new InvalidArgumentException();


        // separate just needed array
        $points = $xml->trk->trkseg->trkpt;

        // do it as requested
        if($method === 'db') {
            $this->create_trip_points_by_db($points, $trip);
        } else if($method === 'json') {
            $this->create_trip_points_by_json($trip);
        }
    }

    private function create_trip_points_by_db($points, $trip)
    {
        // get entity manager
        $em = $this->getDoctrine()->getManager();

        // create all track/trip points
        foreach ($points as $trkpt) {

            $t = new Trkpt;

            $t->setLat($trkpt->attributes()->lat);
            $t->setLon($trkpt->attributes()->lon);
            $t->setEle($trkpt->ele);
            $t->setTime(new \DateTime($trkpt->time));
            $t->setTrip($trip);

            $em->persist($t);

        }

        // save to db
        $em->flush();
    }

    private function create_trip_points_by_json($trip)
    {
        // set points
        $xmlDataUrl = $trip->getImageName();

        // get xml text data
        $xmlData = file_get_contents("../web/xml/trips/$xmlDataUrl");

        // then into php object
        $xml = simplexml_load_string($xmlData);

        // and into trip we go...
        $trip->setPointsJson(json_encode($xml->trk->trkseg));
    }

}
