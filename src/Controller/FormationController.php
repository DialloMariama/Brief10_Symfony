<?php

namespace App\Controller;

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationController extends AbstractController
{
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour effectuer cette action')]
    #[Route('/api/formation/create', name: 'create_formation', methods: ['POST'])]
    public function register(EntityManagerInterface $em, Request $request, SerializerInterface $serializer, ValidatorInterface $validator): JsonResponse
    {
        $formation = $serializer->deserialize($request->getContent(), Formation::class, 'json');

        $errors = $validator->validate($formation);

        if ($errors->count() > 0) {
            return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
        } else {
            $em->persist($formation);
            $em->flush();
            return new JsonResponse($serializer->serialize($formation, 'json'), JsonResponse::HTTP_CREATED, [], true);
        }
    }

    #[Route('/api/formation/index', name: "allFormation", methods: ['GET'])]
    public function index(FormationRepository $formationRepository, SerializerInterface $serializer, ): JsonResponse
    {
        $formations = $formationRepository->findBy(['etat' => "en_cours"]);
      
        
        // $jsonFormList = $serializer->serialize($formations, 'json', ['groups' => 'formation']);
        $data = [];
        foreach($formations as $formation){
            $data[]=[
                "id"=>$formation->getID(),
                "nom"=>$formation->getNom(),
                "description"=>$formation->getDescription(),
                "etat"=>$formation->getEtat(),
                "duree"=>$formation->getDuree()
        ];
        }
        return new JsonResponse($serializer->serialize($data,'json'), JsonResponse::HTTP_OK, [], true);

    }

    #[Route('/api/formations/{id}', name: "showFormation", methods: ['GET'])]

    public function show(Formation $formation, SerializerInterface $serializer): JsonResponse
    {
        $data=[];
        $data[]=[
            "id"=>$formation->getID(),
            "nom"=>$formation->getNom(),
            "description"=>$formation->getDescription(),
            "etat"=>$formation->getEtat(),
            "duree"=>$formation->getDuree()


    ];
    
    return new JsonResponse($serializer->serialize($data,'json'), JsonResponse::HTTP_OK, [], true);

    }
     #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour effectuer cette action')]
    #[Route('/api/formations/delete/{id}', name: "deleteFormation", methods: ['delete'])]

    public function delete(Formation $formation, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($formation);
        $em->flush();

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour effectuer cette action')]
    #[Route('/api/formations/update/{id}', name: "modifierFormation", methods: ['update'])]
    public function update(Formation $formation, Request $req, ValidatorInterface $validator, SerializerInterface $serializer, EntityManagerInterface $em): JsonResponse
    {
        if ($formation) {
            $data = $serializer->deserialize(
                $req->getContent(),
                Formation::class,
                'json',
                [AbstractNormalizer::OBJECT_TO_POPULATE => $formation]
            );

            $errors = $validator->validate($data);
            if ($errors->count() > 0) {
                return new JsonResponse($serializer->serialize($errors, 'json'), JsonResponse::HTTP_BAD_REQUEST, [], true);
            }
            $em->persist($data);
            $em->flush();
            return new JsonResponse($serializer->serialize($data, 'json'), Response::HTTP_OK, ['accept' => 'json'], true);
        } else {
            return new JsonResponse("Cette formation n'existe pas.", Response::HTTP_NOT_FOUND);
        }
    }
}
