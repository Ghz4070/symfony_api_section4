<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiGenreController extends AbstractController
{
    /**
     * @Route("/api/genres", name="api_genres", methods={"GET"})
     */
    public function list(GenreRepository $repo, SerializerInterface $serializer)
    {
        $genre = $repo->findAll();
        $resultat = $serializer->serialize(
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
    public function show(Genre $genre, SerializerInterface $serializer)
    {
        $resultat = $serializer->serialize(
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
    public function create(Request $request, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $data = $request->getContent();
        $genre = $serializer->deserialize($data, Genre::class, 'json');
        $em->persist($genre);
        $em->flush();

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
    public function update(Genre $genre, Request $request, EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $data = $request->getContent();
        $serializer->deserialize($data, Genre::class, 'json', ['object_to_populate' => $genre]);
        $em->persist($genre);
        $em->flush();

        return new JsonResponse(
            "Le genre a bien été modifier",
            Response::HTTP_OK,
            [],
            true);
    }

    /**
     * @Route("/api/genres/{id}", name="api_genres_delete", methods="DELETE")
     */
    public function delete(Genre $genre, EntityManagerInterface $em)
    {
        $em->remove($genre);
        $em->flush();

        return new JsonResponse("Le genre a bien été supprimer", Response::HTTP_OK, []);
    }
}
