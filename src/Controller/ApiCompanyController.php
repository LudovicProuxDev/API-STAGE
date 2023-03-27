<?php

namespace App\Controller;

use App\Entity\Company;
use App\Repository\CompanyRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ApiCompanyController extends AbstractController
{
    /**
     * @Route(
     *  "/api/company",
     *  name="app_api_company",
     *  methods={"GET"}
     *  )
     */
    public function index(CompanyRepository $companyRepository, NormalizerInterface $normalizer): JsonResponse
    {
        // Récupération des entreprises
        $companies = $companyRepository->findAll();

        // Sérialisation au format JSON
        $json = json_encode($companies);

        // Référence utilisée pour récupérer le contexte
        // https://stackoverflow.com/questions/44286530/symfony-3-2-a-circular-reference-has-been-detected-configured-limit-1

        // Ne vas pas fonctionner car les attributs sont en 'private'
        // Il faut normaliser !
        // $companiesNormalized = $normalizer->normalize($companies);
        $companiesNormalized = $normalizer->normalize($companies,'json',['circular_reference_handler' => function ($object) { return $object->getId(); } ]);

        // Debug in Postman
        dd($companies, $companiesNormalized, $json);

        // Renvoie d'une réponse au format JSON
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ApiStudentController.php',
        ]);
    }

    /**
     * @Route(
     *  "/api/company",
     *  name="app_api_company_add",
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

        // Ici, les donées ont été vérifiées, on peut créer une nouvelle instance de Company
        $company = new Company();
        $company->setName($data_request['name']);
        $company->setStreet($data_request['street']);
        $company->setZipcode($data_request['zipcode']);
        $company->setCity($data_request['city']);
        $company->setWebsite($data_request['website']);

        // dd($company);

        // Insertion en base de l'instance Company
        $entity_manager->persist($company);
        $entity_manager->flush();

        return $this->json([
            'status' => 'OK'
        ]);
    }
}
