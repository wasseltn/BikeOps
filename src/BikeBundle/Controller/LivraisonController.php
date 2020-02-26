<?php

namespace BikeBundle\Controller;

use BikeBundle\Entity\Livraison;
use BikeBundle\Entity\Notification;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Livraison controller.
 *
 */
class LivraisonController extends Controller
{
    /**
     * Lists all livraison entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $livraisons = $em->getRepository('BikeBundle:Livraison')->findAll();
        $notif = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        return $this->render('livraison/index.html.twig', array(
            'livraisons' => $livraisons,
            'notifications' => $notif
        ));
    }
    /**
     * page pdf
     *
     */
    public function pdfAction()
    {
        $em = $this->getDoctrine()->getManager();

        $livraisons = $em->getRepository('BikeBundle:Livraison')->findAll();

        return $this->render('livraison/pdf.html.twig', array(
            'livraisons' => $livraisons,
        ));
    }
    public function impAction()
    {
        $em = $this->getDoctrine()->getManager();

        $l = $em->getRepository('BikeBundle:Livraison')->findAll();

        $pageUrl = $this->render('livraison/pdf.html.twig', array('livraisons'=>$l));
        return new PdfResponse(
            $this->get('knp_snappy.pdf')->getOutputFromHtml($pageUrl),
            'livraisons.pdf'
        );

    }




    public function mapAction()
    {
        $em = $this->getDoctrine()->getManager();
        $notif = $this->getDoctrine()->getRepository(Notification::class)->findAll();
       // $livraisons = $em->getRepository('BikeBundle:Livraison')->findAll();

        return $this->render('livraison/map.html.twig', array('notifications' => $notif));
    }


    /**
     * Creates a new livraison entity.
     *
     */
    public function newAction(Request $request)
    {
        $livraison = new Livraison();
        $form = $this->createForm('BikeBundle\Form\LivraisonType', $livraison);
        $notif = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($livraison);
            $em->flush();

            return $this->redirectToRoute('livraison_show', array('id' => $livraison->getId()));
        }

        return $this->render('livraison/new.html.twig', array(
            'livraison' => $livraison,
            'form' => $form->createView(),
            'notifications' => $notif
        ));
    }

    /**
     * Finds and displays a livraison entity.
     *
     */
    public function showAction(Livraison $livraison)
    {
        $deleteForm = $this->createDeleteForm($livraison);
        $notif = $this->getDoctrine()->getRepository(Notification::class)->findAll();

        return $this->render('livraison/show.html.twig', array(
            'livraison' => $livraison,
            'delete_form' => $deleteForm->createView(),
            'notifications' => $notif
        ));
    }

    /**
     * Displays a form to edit an existing livraison entity.
     *
     */
    public function editAction(Request $request, Livraison $livraison)
    {
        $deleteForm = $this->createDeleteForm($livraison);
        $editForm = $this->createForm('BikeBundle\Form\LivraisonType', $livraison);
        $editForm->handleRequest($request);
        $notif = $this->getDoctrine()->getRepository(Notification::class)->findAll();

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('livraison_edit', array('id' => $livraison->getId()));
        }

        return $this->render('livraison/edit.html.twig', array(
            'livraison' => $livraison,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'notifications' => $notif
        ));
    }

    /**
     * Deletes a livraison entity.
     *
     */
    public function deleteAction(Request $request, Livraison $livraison)
    {
        $form = $this->createDeleteForm($livraison);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($livraison);
            $em->flush();
        }

        return $this->redirectToRoute('livraison_index');
    }

    /**
     * Creates a form to delete a livraison entity.
     *
     * @param Livraison $livraison The livraison entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Livraison $livraison)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('livraison_delete', array('id' => $livraison->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }


    /**
     * Finds and displays a livraison entity.
     *
     */
    public function villeAction($ville)
    {
        $v=$this->getDoctrine()->getRepository(Livraison::class)->findBy(array('ville'=>$ville));
        $notif = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        return $this->render('livraison/showliv.html.twig', array(
            'livraisons' => $v,
            'notifications' => $notif
        ));
    }

}
