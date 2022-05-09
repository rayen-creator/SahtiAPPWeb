<?php

namespace App\Controller;

use App\Form\SearchType;
use App\Services\QrcodeService;
use App\Entity\Commandes;
use App\Repository\CommandesRepository;
use App\Repository\ProduitRepository;
use App\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\FactureRepository;
use App\Services\MailerService;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\LigneCommandeRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Dompdf\Options;
use Dompdf\Dompdf;
use Symfony\Component\Mailer\MailerInterface;

/**
 * @Route("/facture")
 */
class FactureController extends AbstractController
{
    private $qrCode = null;
    private $numeroFacture;
    private $produit=array();
    /**
     * @Route("/{id}", name="factureClient")
     */
    public function index(Request $request, QrcodeService $qrcodeService,MailerInterface $mailer ,FactureRepository $factureRepository,LigneCommandeRepository $cmd, CommandesRepository $commande, ProduitRepository $pr,MailerService $mailerService): Response
    {
        
        $id = $request->get('id');
        $idsCmd = $cmd->findBy(array("idCmd"=>$id));
        //dd($commande->count());
        //for($i=0; i<)
        //dd($commande);
        $produit = array();
        $i = 0;
        foreach($idsCmd as $c){
            $produit[$i] = $pr->findOneBy(array("idProd" => $c->getIdProd()));
            //$produit[$i] = $commande->getProduit()[$i];
            $i++;

        }
        $montantTotal = $commande->findOneBy(array("id"=>$id))->getMontantCmd();
        $nbFact = $factureRepository->getNbFacture();
        //dd($nbFact);
        
        $numeroFacture = "00001";
        if ($nbFact != 0){
            $numeroFacture = $factureRepository->findBy(array(), array('id' => 'DESC'),1);
            $numeroFacture = str_replace("0","",$numeroFacture);
            $numeroFacture = intval($numeroFacture)+1;
            $numeroFacture = strval($numeroFacture);
           
        }
        //composer require endroid/qr-code-bundle
        $qrCode = null;       
        $qrcodeContent = "Numéro facture:".$numeroFacture." \n Montant total:".strval($montantTotal);
        $qrCode = $qrcodeService->qrcode($qrcodeContent);
       
        /*$mailerService->send(
            "Votre Facture d'achat SAHTI",
            "iheb.akr.imi@esprit.tn",
            "iheb.akrimi@esprit.tn",            
            "facture/index.html.twig",                
                ['qrCode' => $qrCode,
                'produits' => $produit,
                'numeroFacture' => $numeroFacture
                
            ]);   */      


        /*return $this->render('facture/index.html.twig', [            
            'qrCode' => $qrCode,
            'produits' => $produit,
            'numeroFacture' => $numeroFacture
            
        ]);*/
        //$pdfOptions = new Options();
        //$pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $email = (new  TemplatedEmail())
        ->from('noreplysahti@gmail.com')
        // On attribue le destinataire
        ->to("iheb.akrimi@esprit.tn")
        // On crée le texte avec la vue
        ->subject('Facture')
        ->htmlTemplate('facture/myPdf.html.twig') 
        ->context([
            'f' => $factureRepository->findAll(),                       
            'qrCode' => $qrCode,
            'produits' => $produit,
            'numeroFacture' => $numeroFacture
        ]);
    $mailer->send($email);
        $dompdf = new Dompdf();

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('facture/myPdf.html.twig', [
            'f' => $factureRepository->findAll(),                       
                'qrCode' => $qrCode,
                'produits' => $produit,
                'numeroFacture' => $numeroFacture
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }
    /**
     * @Route("/facturation/pdf", name="pdf", methods={"GET","POST"})
     */
    public function offrepdf(FactureRepository $factureRepository)
    {
           
        // Configure Dompdf according to your needs
        $pdfOptions = new Options();
        $pdfOptions->set('defaultFont', 'Arial');

        // Instantiate Dompdf with our options
        $dompdf = new Dompdf($pdfOptions);

        // Retrieve the HTML generated in our twig file
        $html = $this->renderView('facture/myPdf.html.twig', [
            'f' => $factureRepository->findAll(),                       
                'qrCode' => $qrCode,
                'produits' => $produit,
                'numeroFacture' => $numeroFacture
        ]);

        // Load HTML to Dompdf
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation 'portrait' or 'portrait'
        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        // Output the generated PDF to Browser (inline view)
        $dompdf->stream("mypdf.pdf", [
            "Attachment" => false
        ]);
    }



   
   
}
