<?php

namespace BikeBundle\Controller;

use BikeBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Produit controller.
 *
 */
class ProduitController extends Controller
{
    /**
     * Lists all produit entities.
     *
     */
    public function indexAction(Request $request)
    {
        /*
        $em = $this->getDoctrine()->getManager();

        $produits = $em->getRepository('BikeBundle:Produit')->findAll();

        return $this->render('produit/index.html.twig', array(
            'produits' => $produits,
        ));*/

        $paginator=$this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $prodRep = $em->getRepository(Produit::class);
        $allProdsQuery = $prodRep->createQueryBuilder('p')->getQuery();

        $produits = $paginator->paginate(
            $allProdsQuery,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('produit/index2.html.twig', ['pagination' => $produits]);





    }


    public function statAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $velos = $em->getRepository('BikeBundle:Produit')->findBy(array('categorie'=>1));
        $accs = $em->getRepository('BikeBundle:Produit')->findBy(array('categorie'=>3));
        $pis = $em->getRepository('BikeBundle:Produit')->findBy(array('categorie'=>2));
        $l=count($velos);
        $l1=count($accs);
        $l2=count($pis);
        return $this->render('produit/stat.html.twig',array('l'=>$l,'l1'=>$l1,'l2'=>$l2));





    }



    public function frontAction(Request $request)
    {
        /*
        $em = $this->getDoctrine()->getManager();

        $produits = $em->getRepository('BikeBundle:Produit')->findAll();

        return $this->render('produit/index.html.twig', array(
            'produits' => $produits,
        ));*/

        $paginator=$this->get('knp_paginator');
        $em = $this->getDoctrine()->getManager();
        $prodRep = $em->getRepository(Produit::class);
        $allProdsQuery = $prodRep->createQueryBuilder('p')->getQuery();

        $produits = $paginator->paginate(
            $allProdsQuery,
            $request->query->getInt('page', 1),
            3
        );
        return $this->render('produit/index.html.twig', ['pagination' => $produits]);





    }

    /**
     * Creates a new produit entity.
     *
     */
    public function newAction(Request $request)
    {
        $produit = new Produit();
        $form = $this->createForm('BikeBundle\Form\ProduitType', $produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($produit);
            $em->flush();

            return $this->redirectToRoute('produit_show', array('id' => $produit->getId()));
        }

        return $this->render('produit/new.html.twig', array(
            'produit' => $produit,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a produit entity.
     *
     */
    public function showAction(Produit $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);

        return $this->render('produit/show.html.twig', array(
            'produit' => $produit,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing produit entity.
     *
     */
    public function editAction(Request $request, Produit $produit)
    {
        $deleteForm = $this->createDeleteForm($produit);
        $editForm = $this->createForm('BikeBundle\Form\ProduitType', $produit);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('produit_edit', array('id' => $produit->getId()));
        }

        return $this->render('produit/edit.html.twig', array(
            'produit' => $produit,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a produit entity.
     *
     */
    public function deleteAction(Request $request, Produit $produit)
    {
        $form = $this->createDeleteForm($produit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($produit);
            $em->flush();
        }

        return $this->redirectToRoute('produit_index');
    }

    /**
     * Creates a form to delete a produit entity.
     *
     * @param Produit $produit The produit entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Produit $produit)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('produit_delete', array('id' => $produit->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
