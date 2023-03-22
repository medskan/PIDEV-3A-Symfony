<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\OrderDetail;
use App\Entity\Produit;
use App\Repository\OrderDetailRepository;
use App\Repository\OrderRepository;
use App\Repository\ProduitRepository;
use Psr\Container\ContainerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;



/**
 * @Route("/cart", name="cart_")
 */
class CartController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(SessionInterface $session, ProduitRepository $productsRepository)
    {
        $panier = $session->get("panier", []);

        // On "fabrique" les données
        $dataPanier = [];


        foreach($panier as $id => $quantite){
            $product = $productsRepository->find($id);
            $dataPanier[] = [
                "produit" => $product,
                "quantite" => $quantite
            ];

        }

        return $this->render('cart/index.html.twig', compact("dataPanier"));
    }

    /**
     * @Route("/add/{id}", name="add")
     */
    public function add(Produit $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getIdProduit();

        if(!empty($panier[$id])){
            $panier[$id]++;
        }else{
            $panier[$id] = 1;
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);



        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/remove/{id}", name="remove")
     */
    public function remove(Produit $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getIdProduit();

        if(!empty($panier[$id])){
            if($panier[$id] > 1){
                $panier[$id]--;
            }else{
                unset($panier[$id]);
            }
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);



        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/delete/{id}", name="delete")
     */
    public function delete(Produit $product, SessionInterface $session)
    {
        // On récupère le panier actuel
        $panier = $session->get("panier", []);
        $id = $product->getIdProduit();

        if(!empty($panier[$id])){
            unset($panier[$id]);
        }

        // On sauvegarde dans la session
        $session->set("panier", $panier);

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/delete", name="delete_all")
     */
    public function deleteAll(SessionInterface $session)
    {
        $session->remove("panier");

        return $this->redirectToRoute("cart_index");
    }

    /**
     * @Route("/valider", name="valider")
     * @param ProduitRepository $produits
     * @param SessionInterface $session
     */
    public function valider(ProduitRepository $produits, SessionInterface $session, MailerInterface $mailer)
    {
        $panier = $session->get('panier', []);



        $data = [];

        foreach($panier as $id => $quantity){
            $data[] = [
                'product' => $produits->find($id),
                'quantity' => $quantity
            ];
        }

        $total = 0;

        foreach($data as $item){
            $totalitem = $item['product']->getPrixProduit() * $item['quantity'];
            $total += $totalitem;
        }




        $order = $this->getDoctrine()
            ->getRepository(Order::class)
            ->findAll();

        $orderDetail = $this->getDoctrine()
            ->getRepository(OrderDetail::class)
            ->findAll();



        $objectManager = $this->getDoctrine()->getManager();


        $numero_commande = rand(1000 , 10000);


        $order = new Order();
        $order->setDate(new \DateTime('now'));
        $order->setnumeroCommande($numero_commande);
        $nom="Line Kazdaghli";
$order->setIdC($nom);
        $order->setTotal($total);

        $objectManager->persist($order);


        for ($i=0; $i < count($data); $i++) {
            $orderDetail = new OrderDetail();
            $orderDetail->setOrderId($order);
            $orderDetail->setProduit($data[$i]['product']);
            $q=$data[$i]['product']->getQuantite() ;
            $data[$i]['product']->setQuantite($q-$data[$i]['quantity']);
            $orderDetail->setQuantity($data[$i]['quantity']);
            $orderDetail->setPrix($data[$i]['quantity'] * $data[$i]['product']->getPrixProduit()*$data[$i]['product']->getPromotion());
            $objectManager->persist($orderDetail);


        }





        $panier = $session->get('panier', []);

        foreach ($panier as $key => $value) {

            if(!empty($panier[$key])){
                unset($panier[$key]);
            }
        }
        $session->set('panier', $panier);
        $mail = (new TemplatedEmail())
            ->from('kazdaghli86@gmail.com')
            ->to('line.kazdaghli@esprit.tn')
            ->subject('Confiramation de Commande')
            ->htmlTemplate('cart/1.html.twig');
        ;

        $mailer->send($mail);


        $objectManager->flush();


        return $this->redirectToRoute("cart_index");

    }



    /**
     * @Route("/list", name="list")
     */
    public function AfficheC(OrderRepository $repository){

        $commandes=$repository->findAll();
        return $this->render('cart/list.html.twig',
            ['commandes'=>$commandes]);
    }
    /**
     * @Route("/listC", name="listC")
     */
    public function AfficheCommande(OrderRepository $repository){

        $commandes=$repository->findAll();
        return $this->render('cart/listclient.html.twig',
            ['commandes'=>$commandes]);
    }
    /**
     * @Route("/deleteC/{id}", name="deleteC")
     */
    public function DeleteC($id,OrderRepository  $repository,OrderDetailRepository $orderDetailRepository){

        $commandes=$repository->find($id);
        $commandesdetails=$orderDetailRepository->find($id);
        $em=$this->getDoctrine()->getManager();
        $em->remove($commandes);
        $em->remove($commandesdetails);
        $em->flush();
        return $this->redirectToRoute('cart_list');
    }
    /**
     * @Route("/deleteCom/{id}", name="deleteCommande")
     */
    public function DeleteCommande($id,OrderRepository  $repository,OrderDetailRepository $orderDetailRepository){

        $commandes=$repository->find($id);
        $commandesdetails=$orderDetailRepository->find($id);
        $em=$this->getDoctrine()->getManager();


        $em->remove($commandes);
        $em->remove($commandesdetails);
        $em->flush();

        return $this->redirectToRoute('cart_listC');

    }
  }
