<?php

namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiAuthorController extends AbstractController
{
    private $repo;
    private $serializer;
    private $em;
    private $validator;

    public function __construct(AuthorRepository $repo, SerializerInterface $serializer,
                                EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->repo = $repo;
        $this->serializer = $serializer;
        $this->em = $em;
        $this->validator = $validator;
    }

    /**
     * @Route("/api/authors", name="api_authors", methods="GET")
     */
    public function list(): Response
    {
        $author = $this->repo->findAll();
        $resultat = $this->serializer->serialize(
            $author,
            'json',
            [
                'groups' => ['listAuthorFull'], // Annotations dans entity @Groups({"listAuthorFull"})
            ]
        );

        return new JsonResponse($resultat, 200, [], true);
    }

    /**
     * @Route("/api/authors/{id}", name="api_authors_show", methods="GET")
     */
    public function show(Author $author): Response
    {
        $resultat = $this->serializer->serialize(
            $author,
            'json',
            [
                'groups' => ['listAuthorSimple'],
            ]
        );

        return new JsonResponse($resultat, 200, [], true);
    }

    /**
     * @Route("/api/authors", name="api_authors_create", methods="POST")
     */
    public function create(Request $request): Response
    {
        $data = $request->getContent();
        $author = $this->serializer->deserialize($data, Author::class, 'json');

        // Gestion des erreurs / Bien ajouter les Assert pour les verifications
        $errors = $this->validator->validate($author);
        if (count($errors)) {
            $errorJson = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorJson, Response::HTTP_BAD_REQUEST, [], true);
        }

        $this->em->persist($author);
        $this->em->flush();

        return new JsonResponse(
            "Le author a bien été crée",
            Response::HTTP_CREATED,
            [
                "location" => "api/authors/{$author->getId()}"
            ],
            true);
    }

    /**
     * @Route("/api/authors/{id}", name="api_authors_update", methods="PUT")
     */
    public function update(Request $request, Author $author): Response
    {
        $data = $request->getContent();
        $this->serializer->deserialize($data, Author::class, 'json', ['object_to_populate' => $author]);

        // Gestion des erreurs
        $errors = $this->validator->validate($author);
        if (count($errors)) {
            $errorJson = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorJson, Response::HTTP_BAD_REQUEST, [], true);
        }

        $this->em->persist($author);
        $this->em->flush();

        return new JsonResponse(
            "Le author a bien été modifier",
            Response::HTTP_OK,
            [],
            true);
    }

    /**
     * @Route("/api/authors/{id}", name="api_authors_delete", methods="DELETE")
     */
    public function delete(Author $author): Response
    {
        $this->em->remove($author);
        $this->em->flush();

        return new JsonResponse("Le author a bien été supprimer", Response::HTTP_OK, []);
    }
}
