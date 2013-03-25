<?php

namespace Acme\BSCheckoutBundle\Controller;

use Acme\BSCheckoutBundle\Entity\checkout;
use Acme\BSCheckoutBundle\Entity\checkoutItem;
use Acme\BSCheckoutBundle\Entity\quickbutton;
use Acme\BSCheckoutBundle\Form\quickbuttonType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Acme\BSCheckoutBundle\Entity\cashbox;
use Acme\BSCheckoutBundle\Form\cashboxType;

/**
 * cashbox controller.
 *
 * @Route("/cashbox")
 */
class cashboxController extends Controller
{
    /**
     * Lists all cashbox entities.
     *
     * @Route("/", name="checkout_cashbox")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('BSCheckoutBundle:cashbox')->findAll();

        return array('entities' => $entities);
    }
    /**
     * Lists all cashbox entities.
     *
     * @Route("/{id}/close", name="BSCashbox_close")
     * @Template()
     */
    public function closeAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $cashbox =  $em->getRepository('BSCheckoutBundle:cashbox')->find($id);

        if (!$cashbox) {
            throw $this->createNotFoundException('Kann die Kasse nicht finden');
        }

        $checkouts = $em->getRepository('BSCheckoutBundle:checkout')->getHistory($cashbox->getID(),'now');


        


        return array('checkouts' => $checkouts);


    }

    /**
     * Finds and displays a cashbox entity.
     *
     * @Route("/{id}/show", name="checkout_cashbox_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BSCheckoutBundle:cashbox')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find cashbox entity.');
        }

        /*$config_form = $this->createFormBuilder()
            ->add('title','text',array('label'=>'Titel'))
            ->add('code','text',array('label'=>'Code'))
            ->add('price','text',array('label'=>'Preis','required'=>false))
            ->add('key','text',array('label'=>'Tastencode','required'=>false));
        */
        $quickbutton = new quickbutton();
        $quickbutton->setCashbox($entity);
        $quickbutton_form   = $this->createForm(new quickbuttonType(), $quickbutton);
        $quickbuttons = $em->getRepository('BSCheckoutBundle:quickbutton')->getQuickbuttons($entity->getID());



        return array(
            'entity'      => $entity,
            'quickbuttons' => $quickbuttons,
            'quickbutton_form' => $quickbutton_form->createView()
        );
    }
    /**
     * Finds and displays a cashbox entity.
     *
     * @Route("/{id}/button/add", name="checkout_cashbox_button_add")

     */
    public function button_addAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BSCheckoutBundle:cashbox')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find cashbox entity.');
        }
        $request = $this->getRequest();
        $form = $request->request->get('form');



        $buttons = $entity->getQuickbuttons();

        $buttons[] = array(
            'title'=>$form['title'],
            'code'=>$form['code'],
            'price'=>$form['price'],
            'key'=>$form['key'],
        );
        $entity->setQuickbuttons($buttons);
        $em->persist($entity);
        $em->flush();


        return $this->redirect($this->generateUrl('checkout_cashbox_show',array('id'=>$id)));
    }

    /**
     * Finds and displays a cashbox entity.
     *
     * @Route("/{id}/button/delete/{itemid}", name="checkout_cashbox_button_delete")

     */
    public function button_deleteAction($id,$itemid)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BSCheckoutBundle:cashbox')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find cashbox entity.');
        }




        $buttons = $entity->getQuickbuttons();
        unset($buttons[$itemid]);

        $entity->setQuickbuttons($buttons);
        $em->persist($entity);
        $em->flush();


        return $this->redirect($this->generateUrl('checkout_cashbox_show',array('id'=>$id)));
    }





    /**
     * Displays a form to create a new cashbox entity.
     *
     * @Route("/new", name="checkout_cashbox_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new cashbox();
        $form   = $this->createForm(new cashboxType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new cashbox entity.
     *
     * @Route("/create", name="checkout_cashbox_create")
     * @Method("post")
     * @Template("BSCheckoutBundle:cashbox:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new cashbox();
        $request = $this->getRequest();
        $form    = $this->createForm(new cashboxType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('checkout_cashbox_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing cashbox entity.
     *
     * @Route("/{id}/edit", name="checkout_cashbox_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BSCheckoutBundle:cashbox')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find cashbox entity.');
        }

        $editForm = $this->createForm(new cashboxType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing cashbox entity.
     *
     * @Route("/{id}/update", name="checkout_cashbox_update")
     * @Method("post")
     * @Template("BSCheckoutBundle:cashbox:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BSCheckoutBundle:cashbox')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find cashbox entity.');
        }

        $editForm   = $this->createForm(new cashboxType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('checkout_cashbox_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a cashbox entity.
     *
     * @Route("/{id}/delete", name="checkout_cashbox_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('BSCheckoutBundle:cashbox')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find cashbox entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('checkout_cashbox'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    /**
     * @Route("/{id}/print", name="checkout_cashbox_print")
     * @Method({ "GET"})
     * @Template()
     */
    public function printAction($id,$date = 'now'){



        $em = $this->getDoctrine()->getEntityManager();
        $Baskets = $em->getRepository('BSCheckoutBundle:checkout')->getHistory($id,$date);

        $summary = array();
        $article = array();

        foreach($Baskets as $basket){
           // $basket = new checkout();
            $payment = $basket->getPayment();

            foreach($basket->getCheckoutItems() as $item){
                //$item = new checkoutItem();

                if(isset( $article[$item->getArticleCode()])){
                    $summary[$payment][$item->getVAT()] +=$item->getPrice() + $item->getQuantity() ;
                }else{
                    $summary[$payment][$item->getVAT()] = $item->getPrice() + $item->getQuantity() ;
                }

                if(isset( $article[$item->getArticleCode()])){
                    $article[$item->getArticleCode()] +=  $item->getQuantity();
                }else{
                    $article[$item->getArticleCode()] =  $item->getQuantity();
                }

            }
            // TODO Florian Kassenbericht Fertig machen


        }




        $pdf =  $this->get('io_tcpdf');
        /*$pdf->init(array(
            'Creator' => 'Blumenschule Schongau',
            'Author' => 'Florian Engler',
            'Title' => $entity->getArticleNo(),
            'Subject' => $entity->getName(),
        ));*/
        $pdf->SetAutoPageBreak(false, 0);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->setCellPaddings(1, 1, 1, 1);
        $pdf->setCellMargins(1, 1, 1, 1);
        $pdf->AddPage('L');
        //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

        $pdf->SetFont('helvetica', 'B', 10);
        //$pdf->Write(1,$entity->getName(),'',false,'L',1);
        //$pdf->Cell(2, 6, $entity->getName(),1,1);
        //$pdf->Text(0, 0, $entity->getName(),false,false,true,0,1);
        //$pdf->SetFont('helvetica', 'B', 8);
        //$pdf->Cell(2, 6, $entity->getName2(),1,1);
        //$pdf->Write(1,$entity->getName(),'',false,'L',1);
        //$pdf->Text(32, 5, $entity->getName2(),false,false,true,0,1);



        $pdf->Output("print/".$entity->getArticleNo().".pdf", 'F');

        return $this->render('BSDataBundle:Product:print.html.twig', array(
            'urlPDF'=> "/print/".$entity->getArticleNo().".pdf",
        ));


    }



}
