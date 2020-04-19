<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ApiGenreController extends AbstractController
{
    private $repo;
    private $serializer;
    private $em;
    private $validator;

    public function __construct(GenreRepository $repo, SerializerInterface $serializer,
                                EntityManagerInterface $em, ValidatorInterface $validator)
    {
        $this->repo = $repo;
        $this->serializer = $serializer;
        $this->em = $em;
        $this->validator = $validator;
    }

    /**
     * @Route("/api/genres", name="api_genres", methods={"GET"})
     */
    public function list(): Response
    {
        $genre = $this->repo->findAll();
        $resultat = $this->serializer->serialize(
            $genre,
            'json',
            [
                'groups' => ['listGenreFull'],
            ]
        );

        return new JsonResponse($resultat, 200, [], true);
    }

    /**
     * @Route("/api/genres/{id}", name="api_genres_show", methods={"GET"})
     */
    public function show(Genre $genre): Response
    {
        $resultat = $this->serializer->serialize(
            $genre,
            'json',
            [
                'groups' => ['listGenreSimple'],
            ]
        );

        return new JsonResponse($resultat, 200, [], true);
    }

    /**
     * @Route("/api/genres", name="api_genres_create", methods={"POST"})
     */
    public function create(Request $request): Response
    {
        $data = $request->getContent();
        $genre = $this->serializer->deserialize($data, Genre::class, 'json');

        // Gestion des erreurs
        $errors = $this->validator->validate($genre);
        if (count($errors)) {
            $errorJson = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorJson, Response::HTTP_BAD_REQUEST, [], true);
        }

        $this->em->persist($genre);
        $this->em->flush();

        return new JsonResponse(
            "Le genre a bien été crée",
            Response::HTTP_CREATED,
            [
                "location" => "api/genres/{$genre->getId()}"
            ],
            true);
    }

    /**
     * @Route("/api/genres/{id}", name="api_genres_update", methods={"PUT"})
     */
    public function update(Request $request, Genre $genre): Response
    {
        $data = $request->getContent();
        $this->serializer->deserialize($data, Genre::class, 'json', ['object_to_populate' => $genre]);

        // Gestion des erreurs
        $errors = $this->validator->validate($genre);
        if (count($errors)) {
            $errorJson = $this->serializer->serialize($errors, 'json');
            return new JsonResponse($errorJson, Response::HTTP_BAD_REQUEST, [], true);
        }

        $this->em->persist($genre);
        $this->em->flush();

        return new JsonResponse(
            "Le genre a bien été modifier",
            Response::HTTP_OK,
            [],
            true);
    }

    /**
     * @Route("/api/genres/{id}", name="api_genres_delete", methods="DELETE")
     */
    public function delete(Genre $genre): Response
    {
        $this->em->remove($genre);
        $this->em->flush();

        return new JsonResponse("Le genre a bien été supprimer", Response::HTTP_OK, []);
    }
}
