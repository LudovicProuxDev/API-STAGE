<?php

namespace App\Controller;

use App\Entity\Student;
use App\Repository\StudentRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiStudentController extends AbstractController
{
    /**
     * @Route(
     *  "/api/student",
     *  name="app_api_student",
     *  methods={"GET"}
     *  )
     */
    // Dependancy Injection = DI
    public function index(StudentRepository $studentRepository, NormalizerInterface $normalizer): JsonResponse
    {
        // Récupération des étudiants
        $students = $studentRepository->findAll();

        // Sérialisation au format JSON
        $json = json_encode($students);

        // Référence utilisée pour récupérer le contexte
        // https://stackoverflow.com/questions/44286530/symfony-3-2-a-circular-reference-has-been-detected-configured-limit-1

        // Ne vas pas fonctionner car les attributs sont en 'private'
        // Il faut normaliser !
        // $studentsNormalized = $normalizer->normalize($students);
        $studentsNormalized = $normalizer->normalize($students,'json',['circular_reference_handler' => function ($object) { return $object->getId(); } ]);

        // Debug in Postman
        dd($students, $studentsNormalized, $json);

        // Renvoie d'une réponse au format JSON
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiStudentController.php',
        ]);
    }

    /**
     * @Route(
     *  "/api/student",
     *  name="app_api_student_add",
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

        // Ici, les donées ont été vérifiées, on peut créer une nouvelle instance de Student
        $student = new Student();
        $student->setName($data_request['name']);
        $student->setFirstname($data_request['firstname']);
        $student->setPicture($data_request['picture']);
        $student->setDateOfBirth(new DateTime($data_request['date_of_birth']));
        $student->setGrade($data_request['grade']);

        // dd($student);

        // Insertion en base de l'instance Student
        $entity_manager->persist($student);
        $entity_manager->flush();

        return $this->json([
            'status' => 'OK'
        ]);
    }
}
