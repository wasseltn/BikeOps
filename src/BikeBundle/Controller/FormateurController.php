<?php

namespace BikeBundle\Controller;

use BikeBundle\Entity\Formateur;
use BikeBundle\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Formateur controller.
 *
 */
class FormateurController extends Controller
{
    /**
     * Lists all formateur entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $formateurs = $em->getRepository('BikeBundle:Formateur')->findAll();
        $notif = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        return $this->render('formateur/index.html.twig', array(
            'formateurs' => $formateurs,
            'notifications' => $notif
        ));
    }

    /**
     * Creates a new formateur entity.
     *
     */
    public function newAction(Request $request)
    {
        $formateur = new Formateur();
        $form = $this->createForm('BikeBundle\Form\FormateurType', $formateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($formateur);
            $em->flush();

            return $this->redirectToRoute('formateur_show', array('id' => $formateur->getId()));
        }
        $notif = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        return $this->render('formateur/new.html.twig', array(
            'formateur' => $formateur,
            'form' => $form->createView(),
            'notifications' => $notif
        ));
    }

    /**
     * Finds and displays a formateur entity.
     *
     */
    public function showAction(Formateur $formateur)
    {
        $deleteForm = $this->createDeleteForm($formateur);
        $notif = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        return $this->render('formateur/show.html.twig', array(
            'formateur' => $formateur,
            'delete_form' => $deleteForm->createView(),
            'notifications' => $notif
        ));
    }

    /**
     * Displays a form to edit an existing formateur entity.
     *
     */
    public function editAction(Request $request, Formateur $formateur)
    {
        $deleteForm = $this->createDeleteForm($formateur);
        $editForm = $this->createForm('BikeBundle\Form\FormateurType', $formateur);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('formateur_edit', array('id' => $formateur->getId()));
        }
        $notif = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        return $this->render('formateur/edit.html.twig', array(
            'formateur' => $formateur,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'notification' =>$notif
        ));
    }

    /**
     * Deletes a formateur entity.
     *
     */
    public function deleteAction(Request $request, Formateur $formateur)
    {
        $form = $this->createDeleteForm($formateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($formateur);
            $em->flush();
        }

        return $this->redirectToRoute('formateur_index');
    }

    /**
     * Creates a form to delete a formateur entity.
     *
     * @param Formateur $formateur The formateur entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Formateur $formateur)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('formateur_delete', array('id' => $formateur->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
