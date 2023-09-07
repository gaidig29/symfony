<?php

namespace App\Controller\Api;

use App\Entity\Serie;
use App\Repository\SerieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

#[Route('/api/serie', name: 'api_serie_')]
class SerieController extends AbstractController
{
    #[Route('', name: 'list',methods:['GET'])]
    public function list(SerieRepository $serieRepository): Response
    {
        $series =$serieRepository->findAll();
        return $this->json($series,200,[],['groups'=>'serie_api']);

    }
    #[Route('/{id}', name: 'detail',methods:['GET'],requirements: ['id'=>'\d+'])]
    public function detail(int $id,SerieRepository $serieRepository): Response
    {
        $series =$serieRepository->find($id);
        return $this->json($series,200,[],['groups'=>'serie_api']);

    }
    #[Route('', name: 'add',methods:['POST'],requirements: ['id'=>'\d+'])]
    public function add(Request $request,SerializerInterface $serializer): Response
    {
        $data = $request->getContent();
        $serie = $serializer->deserialize($data,Serie::class,'json');
        dd($serie);

    }
    #[Route('/{id}', name: 'update',methods:['PUT','PATCH'],requirements: ['id'=>'\d+'])]
    public function update(): Response
    {
    }
    #[Route('/{id}', name: 'delete',methods:['DELETE'],requirements: ['id'=>'\d+'])]
    public function delete(): Response
    {
    }
}
