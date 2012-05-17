<?php

namespace Acme\BSDataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\BSDataBundle\Entity\PaymentMethods;
use Acme\BSDataBundle\Form\PaymentMethodsType;
use Acme\PlentyMarketsBundle\Controller\PlentySoapClient;

/**
 * PaymentMethods controller.
 *
 * @Route("/BSData/paymentmethods")
 */
class PaymentMethodsController extends Controller
{
    /**
     * Lists all PaymentMethods entities.
     *
     * @Route("/", name="BSData_paymentmethods")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('BSDataBundle:PaymentMethods')->findAll();

        return array('entities' => $entities);
    }

    /**
     * Finds and displays a PaymentMethods entity.
     *
     * @Route("/{id}/show", name="BSData_paymentmethods_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BSDataBundle:PaymentMethods')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PaymentMethods entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        );
    }

    /**
     * Displays a form to create a new PaymentMethods entity.
     *
     * @Route("/new", name="BSData_paymentmethods_new")
     * @Template()
     */
    public function newAction()
    {
      /*  $entity = new PaymentMethods();
        $form   = $this->createForm(new PaymentMethodsType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
       */
        $oPlentySoapClient	=	new PlentySoapClient($this,$this->getDoctrine() );
        $oPlentySoapClient->doGetMethodOfPayments();

        return $this->redirect($this->generateUrl('BSData_paymentmethods'));

    }

    /**
     * Creates a new PaymentMethods entity.
     *
     * @Route("/create", name="BSData_paymentmethods_create")
     * @Method("post")
     * @Template("BSDataBundle:PaymentMethods:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new PaymentMethods();
        $request = $this->getRequest();
        $form    = $this->createForm(new PaymentMethodsType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('BSData_paymentmethods_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing PaymentMethods entity.
     *
     * @Route("/{id}/edit", name="BSData_paymentmethods_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BSDataBundle:PaymentMethods')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PaymentMethods entity.');
        }

        $editForm = $this->createForm(new PaymentMethodsType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing PaymentMethods entity.
     *
     * @Route("/{id}/update", name="BSData_paymentmethods_update")
     * @Method("post")
     * @Template("BSDataBundle:PaymentMethods:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BSDataBundle:PaymentMethods')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find PaymentMethods entity.');
        }

        $editForm   = $this->createForm(new PaymentMethodsType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('BSData_paymentmethods_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a PaymentMethods entity.
     *
     * @Route("/{id}/delete", name="BSData_paymentmethods_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('BSDataBundle:PaymentMethods')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find PaymentMethods entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('BSData_paymentmethods'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
