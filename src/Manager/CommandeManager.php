<?php

namespace App\Manager;

use App\Entity\Commandes;
use App\Services\StripeService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class CommandeManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var StripeService
     */
    protected $stripeService;

    /**
     * @param EntityManagerInterface $entityManager
     * @param StripeService $stripeService
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        StripeService $stripeService
    ) {
        $this->em = $entityManager;
        $this->stripeService = $stripeService;
    }

    /*public function getCommandes()
    {
        return $this->em->getRepository(Commandes::class)
            ->findAll();
    }/*/

  /*
    public function countSoldecommande(User $user)
    {
        return $this->em->getRepository(commande::class)
            ->countSoldecommande($user);
    }

    public function getCommandes(Commandes $commande)
    {
        return $this->em->getRepository(Commande::class)
            ->findById(1);
    }
*/
    public function intentSecret(Commandes $commande)
    {
        $intent = $this->stripeService->paymentIntent($commande);

        return $intent['client_secret'] ?? null;
    }

    public function stripe(array $stripeParameter, Commandes $commande)
    {
        $resource = null;
        $data = $this->stripeService->stripe($stripeParameter, $commande);

        if($data) {
            $resource = [
                'stripeBrand' => $data['charges']['data'][0]['payment_method_details']['card']['brand'],
                'stripeLast4' => $data['charges']['data'][0]['payment_method_details']['card']['last4'],
                'stripeId' => $data['charges']['data'][0]['id'],
                'stripeStatus' => $data['charges']['data'][0]['status'],
                'stripeToken' => $data['client_secret']
            ];
        }

        return $resource;
    }
/*
    public function subscription(
        Product $product,
        Request $request,
        ProductManager $productManager
    ){
        $user = $this->getUser();

        if($request->getMethod() === "POST") {
            $resource = $productManager->stripe($_POST, $product);

            if(null !== $resource) {
                $productManager->create_subscription($resource, $product, $user);

                return $this->render('user/reponse.html.twig', [
                    'product' => $product
                ]);
            }
        }

        return $this->redirectToRoute('payment', ['id' => $product->getId()]);
    }
*/
  /*
    public function create_subscription(array $resource, Commandes $commande /*User $user)
    {
        /*$commande = new Commandes();
        //$commande->setUser($user);
        //$commande->setProduct($product);
        //$commande->setPrice($product->getPrice());
        $commande->setReference(uniqid('', false));
        $commande->setBrandStripe($resource['stripeBrand']);
        $commande->setLast4Stripe($resource['stripeLast4']);
        $commande->setIdChargeStripe($resource['stripeId']);
        $commande->setStripeToken($resource['stripeToken']);
        $commande->setStatusStripe($resource['stripeStatus']);
        $commande->setUpdatedAt(new \Datetime());
        $commande->setCreatedAt(new \Datetime());
        $this->em->persist($commande);
        $this->em->flush();
    }*/
}