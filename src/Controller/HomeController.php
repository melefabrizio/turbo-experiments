<?php

namespace App\Controller;

use App\Entity\Bid;
use App\Entity\Lot;
use App\Repository\LotRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\UX\Turbo\TurboBundle;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function bid(
        Request                $request,
        LotRepository          $lotRepository,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $lots = $lotRepository->listAllLots();
        $form = $this->createFormBuilder()
            ->add('amount', NumberType::class, ['required' => true])
            ->add('bidder', TextType::class, ['required' => true])
            ->add('lot', EntityType::class, [
                'class' => Lot::class,
                'choice_label' => 'name',
                'required' => true
            ])
            ->add('save', SubmitType::class)
            ->getForm();

        $emptyForm = clone $form;

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $bidDto = $form->getData();
            $bid = new Bid();
            $bid->setAmount($bidDto['amount']);
            $bid->setBidder($bidDto['bidder']);
            $lot = $lotRepository->find($bidDto['lot']);
            $lot->addBid($bid);
            $lot->setLastBidAmount($bid->getAmount());
            $lot->setLastBidder($bid->getBidder());
            $entityManager->persist($lot);
            $entityManager->flush();
            $lots = $lotRepository->listAllLots();


            if (TurboBundle::STREAM_FORMAT === $request->getPreferredFormat()) {
                // If the request comes from Turbo, set the content type as text/vnd.turbo-stream.html and only send the HTML to update
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->renderBlock('home/index.html.twig',
                    'success_stream',
                    ['lots' => $lots, 'form' => $emptyForm]);
            }

        }


        return $this->render('home/index.html.twig', [
            'lots' => $lots,
            'form' => $form,
        ]);
    }
}
