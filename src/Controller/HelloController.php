<?php

namespace App\Controller;

use App\Repository\TweetRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController extends AbstractController
{

    private TweetRepository $tweetRepository;

    public function __construct(TweetRepository $tweetRepository)
    {
        $this->tweetRepository = $tweetRepository;
    }

    #[Route('/conference', name: 'conference', methods: 'POST')]
    public function index(): Response
    {
        $tweets = $this->tweetRepository->findAll();
        foreach ($tweets as $tweet) {
            $this->tweetRepository->findBy(["user" => $tweet->getUser()]);
            $tweet->getUser()->getName();
        }
        return $this->render('conference/index.html.twig', [
            'controller_name' => 'ConferenceController',
        ]);
    }


}
