<?php

namespace Acme\BSDataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\BSDataBundle\Entity\StockGround;
use Acme\BSDataBundle\Form\StockGroundType;

/**
 * StockGround controller.
 *
 * @Route("/PlentyMarketsOrder_stockground")
 */
class StockGroundController extends Controller
{
    /**
     * Lists all StockGround entities.
     *
     * @Route("/", name="PlentyMarketsOrder_stockground")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('BSDataBundle:StockGround')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a StockGround entity.
     *
     * @Route("/{id}/show", name="PlentyMarketsOrder_stockground_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BSDataBundle:StockGround')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StockGround entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new StockGround entity.
     *
     * @Route("/new", name="PlentyMarketsOrder_stockground_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new StockGround();
        $form   = $this->createForm(new StockGroundType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new StockGround entity.
     *
     * @Route("/create", name="PlentyMarketsOrder_stockground_create")
     * @Method("post")
     * @Template("PlentyMarketsOrderBundle:StockGround:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new StockGround();
        $request = $this->getRequest();
        $form    = $this->createForm(new StockGroundType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('BSDataBundle_stockground_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing StockGround entity.
     *
     * @Route("/{id}/edit", name="PlentyMarketsOrder_stockground_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BSDataBundle:StockGround')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StockGround entity.');
        }

        $editForm = $this->createForm(new StockGroundType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing StockGround entity.
     *
     * @Route("/{id}/update", name="PlentyMarketsOrder_stockground_update")
     * @Method("post")
     * @Template("PlentyMarketsOrderBundle:StockGround:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BSDataBundle:StockGround')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find StockGround entity.');
        }

        $editForm   = $this->createForm(new StockGroundType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('BSDataBundle_stockground_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a StockGround entity.
     *
     * @Route("/{id}/delete", name="PlentyMarketsOrder_stockground_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('BSDataBundle:StockGround')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find StockGround entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('BSDataBundle_stockground'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
