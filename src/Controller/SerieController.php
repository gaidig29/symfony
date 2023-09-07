<?php

namespace App\Controller;

use App\Entity\Serie;
use App\Form\SerieType;
use App\Repository\SerieRepository;
use App\utils\Uploader;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/serie', name: 'serie_')]
class SerieController extends AbstractController
{
    #[Route('/list/{page}', name: 'list')]
    public function list(SerieRepository $serieRepository, int $page = 1): Response
    {
//        $series = $serieRepository->findBestSeries();
//        dump($series);
        if ($page < 1) {
            $page = 1;
        }
        $totalSeries = $serieRepository->count([]);
        $maxPage = ceil($totalSeries / 50);
        if ($page <= $maxPage) {
            $series = $serieRepository->findSeriesWithPagination($page);
        }else{
            throw $this->createNotFoundException("Page not found !");
        }

        //TODO renvoyer la liste de toutes les séries
        return $this->render('serie/list.html.twig', [
            "series" => $series,
            "currentPage" => $page,
            "maxPage" => $maxPage
        ]);
    }

    #[Route('/{id}', name: 'show', requirements: ['id' => '\d+'])]
    public function show(int $id, SerieRepository $serieRepository): Response
    {
        dump($id);
        $serie = $serieRepository->find($id);

        if (!$serie) {
            throw $this->createNotFoundException("Oops ! Serie not found !");
        }

        //TODO renvoyer les informations de la série
        return $this->render('serie/show.html.twig', [
            "serie" => $serie
        ]);
    }

    #[Route('/new', name: 'new')]
    #[IsGranted("ROLE_USER")]
    public function new(Uploader $uploader, EntityManagerInterface $entityManager, Request $request): Response
    {

        $serie = new Serie();
        $serieForm = $this->createForm(SerieType::class, $serie);
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {
            /**
             * @var UploadedFile $image
             */
            $serie->setPoster($uploader->upload($serieForm->get('poster')->getData(),$this->getParameter('upload_poster_serie_dir'),$serie->getName()));
            $serie->setBackdrop($uploader->upload($serieForm->get('backdrops')->getData(),$this->getParameter('upload_backdrop_serie_dir'),$serie->getName()));

            $image = $serieForm->get('poster')->getData();
            if ($image) {
                $newFileName = $serie->getName() . "-" . uniqid() . "-" . $image->guessExtension();
                $image->move("img/posters/series", $newFileName);
                $serie->setPoster($newFileName);
            }

            $serie->setDateCreated(new \DateTime());
            $entityManager->persist($serie);
            $entityManager->flush();
            $this->addFlash("success", "Serie" . $serie->getName() . "added !");
            return $this->redirectToRoute("serie_show", ['id' => $serie->getId()]);
        }

        return $this->render('serie/new.html.twig', [
            "serieForm" => $serieForm->createView()
        ]);
//        $serie
//            ->setBackdrop("backdrop")
//            ->setDateCreated(new \DateTime())
//            ->setGenres("SF")
//            ->setName("X-Files")
//            ->setFirstAirDate(new \DateTime("-10 year"))
//            ->setPopularity(500)
//            ->setPoster('poster.png')
//            ->setStatus('ending')
//            ->setTmdbId(1234)
//            ->setVote(8);
//
//        dump($serie);
//
//        $entityManager->persist($serie);
//        $entityManager->flush();
//
//        //update du nom de la série
//        $serie->setName("Code Quantum");
//        dump($serie);
//        //ça lance un update et non une nouvelle insertion
//        $entityManager->persist($serie);
//        $entityManager->flush();
//
//        dump($serie);
//
//        $entityManager->remove($serie);
//        $entityManager->flush();
//
//        dump($serie);
//
//        //TODO renvoyer un formulaire d'ajout de série
//        return $this->render('serie/new.html.twig');
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(int $id, EntityManagerInterface $entityManager, SerieRepository $serieRepository, Request $request): Response
    {
        $serie = $serieRepository->find($id);
        $serieForm = $this->createForm(SerieType::class, $serie);
        $serieForm->handleRequest($request);

        if ($serieForm->isSubmitted() && $serieForm->isValid()) {
            $serie->setDateModified(new \DateTime());
            $entityManager->persist($serie);
            $entityManager->flush();
            $this->addFlash("success", "Serie" . $serie->getName() . "updated !");
            return $this->redirectToRoute("serie_show", ['id' => $serie->getId()]);
        }

        return $this->render('serie/new.html.twig', [
            "serieForm" => $serieForm->createView()
        ]);

    }
    #[Route('/{id}/delete', name: 'delete',requirements: ['id'=>'\d+'])]
    #[IsGranted("SERIE_DELETE", "serie", "No permission")]
    public function delete(Serie $serie, EntityManagerInterface $entityManager, SerieRepository $serieRepository, Request $request): Response
    {
//        $serie=$serieRepository->find($id);
        $entityManager->remove($serie);
        $entityManager->flush();

        $this->addFlash('success','Serie '.$serie->getName().' deleted !');
        return $this->redirectToRoute("serie_list");
    }

}
