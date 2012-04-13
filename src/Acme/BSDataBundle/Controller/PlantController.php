<?php

namespace Acme\BSDataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\BSDataBundle\Entity\Plant;
use Acme\BSDataBundle\Form\PlantType;

/**
 * Plant controller.
 *
 * @Route("/BSData/plant")
 */
class PlantController extends Controller
{
    /**
     * Lists all Plant entities.
     *
     * @Route("/", name="BSData_plant")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->get('doctrine.orm.entity_manager');
        $dql = "SELECT a FROM BSDataBundle:Plant a";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $this->get('request')->query->get('page', 1),
            10
        );
        return array('pagination' => $pagination);

    }

    /**
     * Finds and displays a Plant entity.
     *
     * @Route("/{id}/show", name="BSData_plant_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BSDataBundle:Plant')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plant entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new Plant entity.
     *
     * @Route("/new", name="BSData_plant_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Plant();
        $form   = $this->createForm(new PlantType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Plant entity.
     *
     * @Route("/create", name="BSData_plant_create")
     * @Method("post")
     * @Template("BSDataBundle:Plant:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Plant();
        $request = $this->getRequest();
        $form    = $this->createForm(new PlantType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('BSData_plant_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Plant entity.
     *
     * @Route("/{id}/edit", name="BSData_plant_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BSDataBundle:Plant')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plant entity.');
        }

        $editForm = $this->createForm(new PlantType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Plant entity.
     *
     * @Route("/{id}/update", name="BSData_plant_update")
     * @Method("post")
     * @Template("BSDataBundle:Plant:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BSDataBundle:Plant')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Plant entity.');
        }

        $editForm   = $this->createForm(new PlantType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('BSData_plant_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Plant entity.
     *
     * @Route("/{id}/delete", name="BSData_plant_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('BSDataBundle:Plant')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Plant entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('BSData_plant'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
