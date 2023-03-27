<?php

namespace App\Controller;

use App\Entity\Internship;
use App\Entity\Student;
use App\Entity\Company;
use App\Repository\CompanyRepository;
use App\Repository\InternshipRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class ApiInternshipController extends AbstractController
{
    /**
     * @Route(
     *  "/api/internship",
     *  name="app_api_internship",
     *  methods={"GET"}
     *  )
     */
    public function index(InternshipRepository $internshipRepository, NormalizerInterface $normalizer, SerializerInterface $serializer): JsonResponse
    {
        // Récupération des stages
        $internships = $internshipRepository->findAll();

        // Sérialisation au format JSON
        $json = json_encode($internships);

        // Référence utilisée pour récupérer le contexte
        // https://stackoverflow.com/questions/44286530/symfony-3-2-a-circular-reference-has-been-detected-configured-limit-1

        // Ne vas pas fonctionner car les attributs sont en 'private'
        // Il faut normaliser !
        $internshipsNormalized = $normalizer->normalize($internships,'json',['circular_reference_handler' => function ($object) { return $object->getId(); } ]);

        // Debug in Postman
        dd($internships, $internshipsNormalized, $json);

        // Renvoie d'une réponse au format JSON
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiStudentController.php',
        ]);
    }

    /**
     * @Route(
     *  "/api/internship",
     *  name="app_api_internship_add",
     *  methods={"POST"}
     *  )
     */
    public function add(Request $request, EntityManagerInterface $entity_manager): JsonResponse
    {
        // On attend une requête au formet JSON (Content-Type application/json)
        // Le content-type doit être vérifier


        // Récupération du 'body' que l'on transforme depuis du JSON en tableau
        // dd($request->toArray());

        // On affecte le 'body' de la requête dans $data_request
        $data_request = $request->toArray();

        // Référence utilisée pour récupérer les ID des classes
        // https://symfony.com/doc/current/doctrine.html -> chercher 'find'

        // Ici, les donées ont été vérifiées, on peut créer une nouvelle instance de Internship
        $internship = new Internship();
        $internship->setIdStudent($entity_manager->getRepository(Student::class)->find($data_request['student_id']));
        $internship->setIdCompany($entity_manager->getRepository(Company::class)->find($data_request['company_id']));
        $internship->setStartDate(new DateTime($data_request['start_date']));
        $internship->setEndDate(new DateTime($data_request['end_state']));

        // dd($internship);

        // Insertion en base de l'instance Company
        $entity_manager->persist($internship);
        $entity_manager->flush();

        return $this->json([
            'status' => 'OK'
        ]);
    }
}
