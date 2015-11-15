<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Trip;
use AppBundle\Entity\Trkpt;
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
            ->add('xmlFile', 'file')
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

            // save trip
            $em->persist($trip);

            // save trips in db
            if(!$this->create_trip_points($trip, 'json'))
                // if xml not valid show error page
                return $this->redirect($this->generateUrl('error', ['message' => 'invalid xml']));

            // up with him
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
     * @param $trip \AppBundle\Entity\Trip
     * @param string $method string
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    private function create_trip_points($trip, $method='db')
    {
        // get trip's file name from db
        $xmlDataUrl = $trip->getXmlName();

        // get xml text data
        $xmlData = file_get_contents("../web/xml/trips/$xmlDataUrl");

        // then into php object
        $xml = simplexml_load_string($xmlData);

        // check if is valid gpx structure
        if(!isset($xml->trk->trkseg) || !isset($xml->trk->trkseg->trkpt))
            return false;


        // separate just needed array
        $points = $xml->trk->trkseg->trkpt;

        // do it as requested
        if($method === 'db') {
            $this->create_trip_points_by_db($points, $trip);
        } else if($method === 'json') {
            $this->create_trip_points_by_json($trip);
        }

        return true;
    }

    /**
     * saving route data in separate table: trkpt each point being new row in db
     *
     * @param $points array of points from .gpx file
     * @param $trip \AppBundle\Entity\Trip
     */
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

    /**
     * saving route data in table trip, column points_json as json object
     *
     * @param $trip \AppBundle\Entity\Trip
     */
    private function create_trip_points_by_json($trip)
    {
        // set points
        $xmlDataUrl = $trip->getXmlName();

        // get xml text data
        $xmlData = file_get_contents("../web/xml/trips/$xmlDataUrl");

        // then into php object
        $xml = simplexml_load_string($xmlData);

        // and into trip we go...
        $trip->setPointsJson(json_encode($xml->trk->trkseg));
    }

}
