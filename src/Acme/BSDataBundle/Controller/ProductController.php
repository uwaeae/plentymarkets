<?php

namespace Acme\BSDataBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

use Symfony\Component\HttpFoundation\Response;
use Acme\BSDataBundle\Entity\Product;
use Acme\BSDataBundle\Form\ProductType;

/**
 * Product controller.
 *
 * @Route("/product")
 */
class ProductController extends Controller
{
    /**
     * Lists all Product entities.
     *
     * @Route("/", name="product")
     * @Template()
     */
    public function indexAction($page)
    {
        $em = $this->getDoctrine()->getEntityManager();

        //$entities = $em->getRepository('PlentyMarketsOrderBundle:Product')->findAll();

        //return array('entities' => $entities);
        $dql = "SELECT a FROM BSDataBundle:Product a";
        $query = $em->createQuery($dql);

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $page,//$this->get('request')->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );

        // parameters to template
        return compact('pagination');


    }

    /**
     * Search and displays a Product entity.
     *
     * @Route("/{id}/show", name="product_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BSDataBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),  );
    }


    /**
     * Finds and displays a Products by Article_No.
     *
     */





    /**
     * Finds and displays a list of Products by matching Article_No.
     *
     * @Route("/{id}/show", name="product_show")
     * @Template()
     */
    public function searchCodeAction($page,$search)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 'p')
            ->add('from', 'BSDataBundle:Product p')
            ->add('where',
            $qb->expr()->like('p.article_no', '?1')
        )->setParameter('1', $search.'%');


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $page,//$this->get('request')->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );
    return $this->render('BSDataBundle:Product:index.html.twig', array(
            'pagination'=>$pagination  ));
    }
    /**
     * Finds and displays a Products by Article_Name.
     *
     * @Route("/{id}/show", name="product_show")
     * @Template()
     */
    public function searchNameAction($page,$search)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 'p')
            ->add('from', 'BSDataBundle:Product p')
            ->add('where',
            $qb->expr()->like('p.name', '?1')
        )->setParameter('1', '%'.$search.'%' );





        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $page,//$this->get('request')->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('BSDataBundle:Product:index.html.twig', array(
            'pagination'=>$pagination  ));
        //return compact('pagination');

    }

    /**
     * Finds and displays a Products by Article_Name2.
     *
     * @param $page
     * @param $search
     * @return \Symfony\Component\HttpFoundation\Response
     *
     */
    public function searchLateinAction($page,$search)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 'p')
            ->add('from', 'BSDataBundle:Product p')
            ->add('where',
            $qb->expr()->like('p.name2', '?1')
        )->setParameter('1', '%'.$search.'%' );





        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $page,//$this->get('request')->query->get('page', 1)/*page number*/,
            10/*limit per page*/
        );


        return $this->render('BSDataBundle:Product:index.html.twig', array(
            'pagination'=>$pagination  ));
        //return compact('pagination');

    }



    /**
     * Displays a form to create a new Product entity.
     *
     * @Route("/new", name="product_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Product();
        $form   = $this->createForm(new ProductType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Creates a new Product entity.
     *
     * @Route("/create", name="product_create")
     * @Method("post")
     * @Template("PlentyMarketsOrderBundle:Product:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Product();
        $request = $this->getRequest();
        $form    = $this->createForm(new ProductType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('BSDataBundle_product_show', array('id' => $entity->getId())));
            
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView()
        );
    }

    /**
     * Displays a form to edit an existing Product entity.
     *
     * @Route("/{id}/edit", name="product_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BSDataBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $editForm = $this->createForm(new ProductType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Product entity.
     *
     * @Route("/{id}/update", name="product_update")
     * @Method("post")
     * @Template("PlentyMarketsOrderBundle:Product:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BSDataBundle:Product')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Product entity.');
        }

        $editForm   = $this->createForm(new ProductType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('product_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Product entity.
     *
     * @Route("/{id}/delete", name="product_delete")
     * @Method("post")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('BSDataBundle:Product')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Product entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('product'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }

    public function printAction(){
        $id = $this->getRequest()->get('id');
        $quantity = $this->getRequest()->get('quantity');

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BSDataBundle:Product')->find($id);
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
        for($i = 0;$i < $quantity;$i ++){
            $pdf = $this->buildLable($pdf,$entity);
        }


        $pdf->Output("print/".$entity->getArticleNo().".pdf", 'F');

         return $this->render('BSDataBundle:Product:print.html.twig', array(
            'urlPDF'=> "/print/".$entity->getArticleNo().".pdf",
        ));


    }

    private function buildLable($pdf,$entity){

        $pdf->AddPage('L',array(95,25));
        //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

        $pdf->SetFont('helvetica', 'B', 10);
        //$pdf->Write(1,$entity->getName(),'',false,'L',1);
        //$pdf->Cell(2, 6, $entity->getName(),1,1);
        $pdf->Text(0, 0, $entity->getName(),false,false,true,0,1);
        $pdf->SetFont('helvetica', 'B', 8);
        //$pdf->Cell(2, 6, $entity->getName2(),1,1);
        //$pdf->Write(1,$entity->getName(),'',false,'L',1);
        $pdf->Text(32, 5, $entity->getName2(),false,false,true,0,1);
        $style = array(
            'position' => '',
            'align' => 'L',
            'stretch' => false,
            'fitwidth' => false,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => '0',
            'vpadding' => '0',
            'fgcolor' => array(0,0,0),
            'bgcolor' => false, //array(255,255,255),
            'text' => true,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 0
        );
        //( 	code,	 	type,		x = '', 	y = '',	w = '',	h = '',xres = '',style = '',align = '')
        $pdf->SetFont('helvetica', '', 8);

        $pdf->write1DBarcode( $entity->getArticleNo(), 'C128', 0, 8, 30, 15, 0.4, $style, 'T');

        $strings = $this->split_words($entity->getLabelText());
        $line = 0;
        foreach($strings as $s){
            $pdf->Text(32,8 +$line, $s,false,false,true,0,1);
            $line += 3;
        }



        //$pdf->Cell(15, 2,  $strings[0],0,1);
        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
        //$pdf->MultiCell(58, 30, ,0,'L',0,1,'','');

        return $pdf;

    }

    function split_words($string, $max = 55)
    {
        $words = preg_split('/\s/', $string);
        $lines = array();
        $line = '';

        foreach ($words as $k => $word) {
            $length = strlen($line . ' ' . $word);
            if ($length <= $max) {
                $line .= ' ' . $word;
            } else if ($length > $max) {
                if (!empty($line)) $lines[] = trim($line);
                $line = $word;
            } else {
                $lines[] = trim($line) . ' ' . $word;
                $line = '';
            }
        }
        $lines[] = ($line = trim($line)) ? $line : $word;

        return $lines;
    }



}
