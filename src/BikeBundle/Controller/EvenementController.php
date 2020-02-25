<?php

namespace BikeBundle\Controller;

use BikeBundle\Entity\Evenement;
use BikeBundle\Entity\Notification;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Evenement controller.
 *
 */
class EvenementController extends Controller
{
    /**
     * Lists all evenement entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $evenements = $em->getRepository('BikeBundle:Evenement')->findAll();
        $notif = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        return $this->render('evenement/index.html.twig', array(
            'evenements' => $evenements,
            'notifications' => $notif
        ));
    }
    /**
     * Lists all evenement entities.
     *
     */
    public function indexfAction()
    {
        $em = $this->getDoctrine()->getManager();

        $evenements = $em->getRepository('BikeBundle:Evenement')->findAll();
        $notif = $this->getDoctrine()->getRepository(Notification::class)->findAll();

        return $this->render('evenement/showF.html.twig', array(
            'evenements' => $evenements,
            'notifications' => $notif
        ));
    }

    public function mailAction($event){
        $dest= array();
        $userManager = $this->get('fos_user.user_manager');
        $users=$userManager->findUsers();
        $from = $this->getUser();
        foreach ($users as $user)
        array_push($dest,$user->getEmail());
        $message = (new \Swift_Message('BIKEOPS'))
            ->setFrom('swiftmailer.test123456@gmail.com')
            ->setTo($dest)
            ->setDescription('Reservation BIKEOPS 5')
            ->setBody('Une reservation effectuée de l\'evenement '.$event.' Envoyé par : '.$from)


        ;

        $this->get('mailer')->send($message);

        return $this->redirectToRoute('evenement_indexf');
    }

    /**
     * Lists all evenement entities.
     *
     */
    public function calendrierAction()
    {
        $em = $this->getDoctrine()->getManager();

        $evenements = $em->getRepository('BikeBundle:Evenement')->findAll();
        $notif = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        return $this->render('evenement/calendrier.html.twig', array(
            'evenements' => $evenements,
            'notifications' => $notif
        ));
    }

    /**
     * Creates a new evenement entity.
     *
     */
    public function newAction(Request $request)
    {
        $evenement = new Evenement();
        $form = $this->createForm('BikeBundle\Form\EvenementType', $evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();

            return $this->redirectToRoute('evenement_show', array('id' => $evenement->getId()));
        }
        $notif = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        return $this->render('evenement/new.html.twig', array(
            'evenement' => $evenement,
            'form' => $form->createView(),
            'notifications' => $notif
        ));
    }

    /**
     * Finds and displays a evenement entity.
     *
     */
    public function showAction(Evenement $evenement)
    {
        $deleteForm = $this->createDeleteForm($evenement);
        $notif = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        return $this->render('evenement/show.html.twig', array(
            'evenement' => $evenement,
            'delete_form' => $deleteForm->createView(),
            'notifications' => $notif
        ));
    }

    /**
     * Displays a form to edit an existing evenement entity.
     *
     */
    public function editAction(Request $request, Evenement $evenement)
    {
        $deleteForm = $this->createDeleteForm($evenement);
        $editForm = $this->createForm('BikeBundle\Form\EvenementType', $evenement);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('evenement_edit', array('id' => $evenement->getId()));
        }
        $notif = $this->getDoctrine()->getRepository(Notification::class)->findAll();
        return $this->render('evenement/edit.html.twig', array(
            'evenement' => $evenement,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'notification' => $notif
        ));
    }

    /**
     * Deletes a evenement entity.
     *
     */
    public function deleteAction(Request $request, Evenement $evenement)
    {
        $form = $this->createDeleteForm($evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($evenement);
            $em->flush();
        }

        return $this->redirectToRoute('evenement_index');
    }

    /**
     * Creates a form to delete a evenement entity.
     *
     * @param Evenement $evenement The evenement entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Evenement $evenement)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('evenement_delete', array('id' => $evenement->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
