<?php

namespace App\Controller;


use App\Entity\Events;
use App\Repository\EventsRepository;


use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class EventsController
 * @package App\Controller
 * @Route ("/events")
 */
class EventsController extends AbstractController
{
    /**
     * @Route("/index", name="events")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/EventsController.php',
        ]);
    }

    /**
     * @Route ("/getAll", name="pageEvent", methods={"get"})
     * @param EventsRepository $eventsRepository
     * @return Response
     */
    public function getAll(EventsRepository $eventsRepository){
        $list=$eventsRepository->findAll();

        //$list= $this->getDoctrine()
        //->getRepository(Event::class)->findAll();
        $encoders = array(new JsonEncode());
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
     * @Route ("/addAll", name="AddpageEvent", methods={"post"})
     * @param Request $request
     * @return Response
     */
    public function addEvents(Request $request): Response
    {

        $data = $request->getContent();
       // dd($data);

        $encoders = array(new JsonEncoder());
        $serializer = new Serializer([new ObjectNormalizer()], $encoders);
        $p = $serializer->deserialize($data, 'App\Entity\Events', 'json');
        $p->setDateEvent(new \DateTime());


        $em= $this->getDoctrine()->getManager();
        $em->persist($p);
        $em->flush();
        $response = new Response('', Response::HTTP_CREATED);
        //Allow all websites
        $response->headers->set('Access-Control-Allow-Origin', '*');
        // You can set the allowed methods too, if you want
        $response->headers->set('Access-Control-Allow-Methods', 'POST');
        return $response;

    }

    /**
     * @Route ("/update/{id}", name="updatePage", methods={"put"})
     */
    public function updateEvents (Request $request, Events $events,
                                  EntityManagerInterface $entityManager,SerializerInterface $serializer): Response
    {
       $serializer->deserialize($request->getContent(),Events::class,"json",[AbstractNormalizer::OBJECT_TO_POPULATE=>$events]);
        $entityManager->flush();
        $response = new Response('', Response::HTTP_OK);
        //Allow all websites
        $response->headers->set('Access-Control-Allow-Origin', '*');
        // You can set the allowed methods too, if you want
        $response->headers->set('Access-Control-Allow-Methods', 'PUT');
        return $response;
    }
    /**
     * @Route ("/{id}/delete",name="api_delete", methods={"delete"})
     * @return Response
     *
     */
    public function deleteEvents($id):Response
    {
        $em = $this->getDoctrine()->getManager();
        $p = $em->getRepository(Events::class)->find($id);
        $em->remove($p);
        $em->flush();
        $response = new Response('', Response::HTTP_OK);
        //Allow all websites
        $response->headers->set('Access-Control-Allow-Origin',
            '*');
        // You can set the allowed methods too, if you want
        $response->headers->set('Access-Control-Allow-Methods', 'DELETE');
        return $response;
    }

}
