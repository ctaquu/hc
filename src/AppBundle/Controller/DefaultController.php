<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
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
     * @Route("new", name="trip_new")
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

            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('default/new.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function testAction(Request $request)
    {
        $x = 666;

        // adding
        /*$category = new Category();
        $category->setName('Main Products');

        $product = new Product();
        $product->setName('Foo');
        $product->setPrice(19.99);
        $product->setDescription('Lorem ipsum dolor');
        // relate this product to the category
        $product->setCategory($category);

        $em = $this->getDoctrine()->getManager();
        $em->persist($category);
        $em->persist($product);
        $em->flush();

        return new Response(
            'Created product id: '.$product->getId()
            .' and category id: '.$category->getId()
        );*/

        // show category
        /*$id = 1;
        $product = $this->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->find($id);

        $x = $product->getCategory()->getName();
        */

        // list products
        /*        $id = 1;
        $category = $this->getDoctrine()
            ->getRepository('AppBundle:Category')
            ->find($id);

        $x = $category->getProducts();
//        $x = count($x);
        foreach ($x as $item) {
            var_dump($item->getName());
        }

        die;*/

        // show user from trip
        /*$id = 1;
       $trip = $this->getDoctrine()
           ->getRepository('AppBundle:Trip')
           ->find($id);

       $x = $trip->getUser()->getUsername();*/

        // list user's trips
/*        $id = 2;
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->find($id);

//        $x = $user->getUsername();

        $x = $user->getTrips();
//        $x = count($x);
        foreach ($x as $item) {
            var_dump($item->getName());
        }

        die;*/

        $id = 1;
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneByIdJoinedToTrip($id);
//            ->find($id);


        foreach ($user->getTrips() as $trip) {
//            echo 'trip   ';
            echo $trip->getName();
        }

        die;
//        var_dump($user);
//        die;
//        $x = count($user->getTrips());
//        $x = count($user->getTrip());

//        $x = $user->getUsername();
//        $x = $user->getUsername();

        $response = new Response();
        $response->setContent(json_encode($x));
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }

}
