<?php

namespace App\Controller;

use App\Entity\Tweet;
use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Form\TweetFormType;
use App\Repository\TweetRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class TweetController extends AbstractController
{
    private TweetRepository $tweetRepository;
    private UserRepository $userRepository;

    public function __construct(TweetRepository $tweetRepository, UserRepository $userRepository)
    {
        $this->tweetRepository = $tweetRepository;
        $this->userRepository = $userRepository;
    }


    /**
     * @Route("/tweets", name="index", methods={"GET"})
     */
    public function index()
    {
        $user = $this->getUser();
        //$tweets = $this->tweetRepository->findBy(["user" => $user]);
        $tweets = $this->tweetRepository->findAll();
        return $this->render("tweet/tweets.html.twig", ["tweets" => $tweets]);

    }

    #[Route('/tweets/add', name:'new_tweet', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager) {
        $tweet = new Tweet();

        $form = $this->createFormBuilder($tweet)
            ->add('text', TextareaType::class, array(
                'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array(
                'label' => 'Create',
                'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $tweet = $form->getData();
            $tweet->setUser($user);
            $entityManager->persist($tweet);
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('tweet/add_tweet.html.twig', array(
            'form' => $form->createView()
        ));

    }

    /**
     * @Route("/tweets/edit/{id}", name="edit_tweet", methods={"POST"})
     */
    public function edit(Request $request, int $id) {

        $tweet = $this->getDoctrine()->getRepository(Tweet::class)->find($id);

        $form = $this->createFormBuilder($tweet)
            ->add('text', TextareaType::class, array(
                'attr' => array('class' => 'form-control')))
            ->add('save', SubmitType::class, array(
                'label' => 'Update',
                'attr' => array('class' => 'btn btn-primary mt-3')))
            ->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('index');
        }

        return $this->render('tweet/edit.html.twig', array(
            'form' => $form->createView()
        ));

    }

    /**
     * @Route("/tweets/delete/{id}", name="delete_tweet", methods={"POST", "DELETE"}, requirements={"id"= "\d+"})
     */
    public function delete(Request $request, int $id) {
        $tweet = $this->getDoctrine()->getRepository(Tweet::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($tweet);
        $entityManager->flush();

        return $this->redirectToRoute('index');
    }
}










