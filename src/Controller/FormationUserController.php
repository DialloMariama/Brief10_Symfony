<?php

namespace App\Controller;

use App\Entity\FormationUser;
use App\Repository\UserRepository;
use App\Repository\FormationRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\FormationUserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FormationUserController extends AbstractController
{
    #[IsGranted('ROLE_USER', message: 'Vous n\'avez pas les droits suffisants pour effectuer cette action')]

    #[Route('/api/candidature', name: 'candidature', methods: ['POST'])]

    public function candidater(SerializerInterface $serializer, Request $request, Security $security, FormationRepository $form, UserRepository $usersRepo, EntityManagerInterface $em): JsonResponse
    {
        $candidatures = $serializer->deserialize($request->getContent(), FormationUser::class, 'json');
        $userObjet = $security->getUser();
        $data = $request->toArray();
        $idFormation = $data['formationId'] ?? -1;
        $candidatures->setUser($userObjet);
        $candidatures->setFormation($form->find($idFormation));
        $em->persist($candidatures);
        $em->flush();

        $data = [];
        $data[] = [
            "id" => $candidatures->getId(),
            "nom" => $candidatures->getFormation()->getNom(),
            "id user" => $candidatures->getUser()->getId(),
            "Email" => $candidatures->getUser()->getEmail(),

        ];
        return new JsonResponse($serializer->serialize($data, 'json'), JsonResponse::HTTP_CREATED, [], true);

    }

    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour effectuer cette action')]

    #[Route('/api/FormationUser/refuser/{id}', name: 'refused_FormationUser', methods: ['PUT'])]

    public function refuser(FormationUser $candidature, EntityManagerInterface $em, SerializerInterface $sz): JsonResponse
    {
        $candidature->setEtat("refuser");

        $em->flush();
        return new JsonResponse(['message' => 'La candidature a été refusée'], JsonResponse::HTTP_OK);
    }

    #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour effectuer cette action')]

    #[Route('/api/FormationUser/accepter/{id}', name: 'refused_FormationUser', methods: ['PUT'])]

    public function accepter(FormationUser $candidature, EntityManagerInterface $em, SerializerInterface $sz): JsonResponse
    {
        $candidature->setEtat("accepter");

        $em->flush();
        return new JsonResponse(['message' => 'La candidature a été acceptée'], JsonResponse::HTTP_OK);
    }

    // #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour effectuer cette action')]
    // #[Route('/api/FormationUsers/accepter', name: 'FormationUser_accepted', methods: ['GET'])]
    // public function getAcceptedFormationUsers(FormationUserRepository $FormationUserRepository, SerializerInterface $serializer): JsonResponse
    // {
    //     $acceptedFormationUsers = $FormationUserRepository->findBy(['etat' => 'accepter']);

    //     $data = [];
    //     foreach ($acceptedFormationUsers as $candidature) {
    //         $data[] = [
    //             'id' => $candidature->getId(),
    //             'user' => [
    //                 'id' => $candidature->getUser()->getId(),
    //             ],
    //             'formation' => [
    //                 'nom' => $candidature->getFormation()->getNom(),
    //             ],
    //             'etat' => $candidature->getEtat(),
    //         ];
    //     }
    //     return new JsonResponse($serializer->serialize($data, 'json'), JsonResponse::HTTP_OK, [], true);
    // }

    // #[IsGranted('ROLE_ADMIN', message: 'Vous n\'avez pas les droits suffisants pour effectuer cette action')]
    // #[Route('/api/FormationUsers/refused', name: 'FormationUser_refused', methods: ['GET'])]
    // public function getRefusedFormationUsers(FormationUserRepository $FormationUserRepository, SerializerInterface $serializer): JsonResponse
    // {
    //     $acceptedFormationUsers = $FormationUserRepository->findBy(['etat' => 'refuser']);
    //     $data = [];
    //     foreach ($acceptedFormationUsers as $candidature) {
    //         $data[] = [
    //             'id' => $candidature->getId(),
    //             'user' => [
    //                 'id' => $candidature->getUser()->getId(),
    //             ],
    //             'formation' => [
    //                 'nom' => $candidature->getFormation()->getNom(),
    //             ],
    //             'etat' => $candidature->getEtat(),
    //         ];
    //     }
    //     return new JsonResponse($serializer->serialize($data, 'json'), JsonResponse::HTTP_OK, [], true);
    // }
}
