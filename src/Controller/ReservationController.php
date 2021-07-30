<?php

namespace App\Controller;

use App\Entity\Reservation;
use App\Repository\ReservationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;


/**
 * Class ReservationController
 * @package App\Controller
 * @Route ("/reservation")
 */


class ReservationController extends AbstractController
{
    /**
     * @Route("/index", name="reservation")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ReservationController.php',
        ]);
    }



    /**
     * @Route ("/getAll", name="pageReservation", methods={"get"})
     * @param ReservationRepository $reservationRepository
     * @return Response
     */
    public function getAllReservation (ReservationRepository $reservationRepository){
        $list=$reservationRepository->findAll();

        //$list= $this->getDoctrine()
        //->getRepository(Event::class)->findAll();
        $encoders = array(new JsonEncoder());
        $serializer = new Serializer([new ObjectNormalizer()], $encoders);
        $data = $serializer->serialize($list, 'json');
        $response = new Response($data, 200);
        //content type
        $response->headers->set('Content-Type', 'application/json');
        //Allow all websites
        $response->headers->set('Access-Control-Allow-Origin', '*');
        // You can set the allowed methods too, if you want
        $response->headers->set('Access-Control-Allow-Methods', 'GET');
        return $response;
    }

    /**
     * @Route ("/addAll", name="AddpageReservation", methods={"post"})
     * @param Request $request
     * @return Response
     */
    public function add (Request $request): Response
    {

        $data = $request->getContent();
        // dd($data);

        $encoders = array(new JsonEncoder());
        $serializer = new Serializer([new ObjectNormalizer()], $encoders);
        $r = $serializer->deserialize($data, 'App\Entity\Reservation', 'json');

       $r->setDateRes(new DateTime());

        $em= $this->getDoctrine()->getManager();
        $em->persist($r);
        $em->flush();
        $response = new Response('', Response::HTTP_CREATED);
        //Allow all websites
        $response->headers->set('Access-Control-Allow-Origin', '*');
        // You can set the allowed methods too, if you want
        $response->headers->set('Access-Control-Allow-Methods', 'POST');
        return $response;

    }

    /**
     * @Route("/delete/{id}", name="deletePageReservation", methods={"delete"})
     * @param $id
     * @return Response
     */
    public function delete ($id): Response
    {
        $em = $this->getDoctrine()->getManager();
        $reservation = $em->getRepository(Reservation::class)->find($id);
        $em->remove($reservation);
        $em->flush();
        $response = new Response('', Response::HTTP_OK);
        //Allow all websites
        $response->headers->set('Access-Control-Allow-Origin', '*');
        // You can set the allowed methods too, if you want
        $response->headers->set('Access-Control-Allow-Methods', 'DELETE');
        return $response;
    }

    /**
     * @Route ("/update/{id}", name="updatePageReservation", methods={"put"})
     */
    public function updateEvents (Request $request, Reservation $reservation,
                                  EntityManagerInterface $entityManager,SerializerInterface $serializer): Response
    {
        $serializer->deserialize($request->getContent(),Reservation::class,"json",[AbstractNormalizer::OBJECT_TO_POPULATE=>$reservation]);
        $entityManager->flush();
        $response = new Response('', Response::HTTP_OK);
        //Allow all websites
        $response->headers->set('Access-Control-Allow-Origin', '*');
        // You can set the allowed methods too, if you want
        $response->headers->set('Access-Control-Allow-Methods', 'PUT');
        return $response;
    }


}
