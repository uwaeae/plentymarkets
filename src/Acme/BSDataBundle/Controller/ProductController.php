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

    private  $limit = 50;
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
            $this->limit/*limit per page*/
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
            $this->limit/*limit per page*/
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
            $this->limit/*limit per page*/
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
            $this->limit/*limit per page*/
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

    public function createDummyAction(){
        $form = $this->buildDummyForm();

        return array(
            'form'   => $form->createView(),

        );

    }


    public function lableAction(){

        return $this->render('BSDataBundle:Product:lable.html.twig', array(
            'urlPDF'=> "/print.pdf",
        ));


    }

    public function printLableAction(){
        $request = $this->getRequest();

        $form = $request->request->get('labelform');
        $entity = new Product();
        $entity->setArticleId($form['articleid']);
        $entity->setArticleNo($form['articlecode']);
        $entity->setName($form['name']);
        $entity->setName2($form['name2']);
        $entity->setLabelText($form['description']);

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

        $pdf = $this->buildLable($pdf,$entity);


        $pdfUrl = "print/".$entity->getArticleNo().".pdf";
        $pdf->Output($pdfUrl, 'F');
        $result = array('pdfurl'=>  '/'.$pdfUrl);

        $response = new Response( json_encode($result));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    public function searchAction($search,$type){

        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->add('select', 'p')
            ->add('from', 'BSDataBundle:Product p');
        switch($type){
            case 'name':
                    $qb->add('where', $qb->expr()->like('p.name', '?1'))
                        ->setParameter('1', '%'.$search.'%');
                    break;
            case 'code':
                $qb->add('where',$qb->expr()->like('p.article_no', '?1'))
                    ->setParameter('1', $search.'%');
                break;
            case 'name2':
                $qb->add('where',$qb->expr()->like('p.name2', '?1'))
                    ->setParameter('1', $search.'%');
                break;

        }
       $result = $qb->getQuery()->getResult();

       return $this->createLabelJSON($result);

    }

    private function createLabelJSON($products){

        $result = array();
        $index = 1;

        foreach($products as $product){

            $item['articleid'] =  $product->getArticleId();
            $item['articlecode'] = $product->getArticleNo();
            $item['name'] = $product->getName();
            $item['name2'] = $product->getName2();
            $item['description'] = $product->getLabelText();
            $item['price'] = $product->getPrice();
            $item['picurl'] = $product->getPicurl();
            $result[$index] = $item;
            $index ++;
        }

        $response = new Response( json_encode($result));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    public function printAction(){
        $id = $this->getRequest()->get('id');
        $quantity = $this->getRequest()->get('quantity');
        $width = $this->getRequest()->get('width');
        $height = $this->getRequest()->get('height');

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
            $pdf = $this->buildLable($pdf,$entity,$width,$height);
        }


        $pdf->Output("print/".$entity->getArticleNo().".pdf", 'F');

         return $this->render('BSDataBundle:Product:print.html.twig', array(
            'urlPDF'=> "/print/".$entity->getArticleNo().".pdf",
        ));


    }

    public function printA6Action(){


        $em = $this->getDoctrine()->getEntityManager();

        $data = $this->get('request')->request->get('A6Lable');




        $pdf =  $this->get('io_tcpdf');
        /*$pdf->init(array(
            'Creator' => 'Blumenschule Schongau',
            'Author' => 'Florian Engler',
            'Title' => $entity->getArticleNo(),
            'Subject' => $entity->getName(),
        ));*/
        $pdf->SetAutoPageBreak(true, 0);
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->setCellPaddings(1, 1, 1, 1);
        $pdf->setCellMargins(6, 1, 1, 1);

        $pdf->AddPage('L');
        //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
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

//       $html ='<style>
//            table.rotate td{
//                -moz-transform:rotate(90deg); /* Firefox 3.6 Firefox 4 */
//                -webkit-transform:rotate(90deg); /* Safari */
//                -o-transform:rotate(90deg); /* Opera */
//                -ms-transform:rotate(90deg); /* IE9 */
//                transform:rotate(90deg); /* W3C */
//                width: 9cm;
//                height: 13cm;
//            }
//
//
//            </style>';



        $index = 0;

        foreach($data as $entity){

           /* $pdf->SetFont('helvetica', 'B', 10);
            //$pdf->Write(1,$entity->getName(),'',false,'L',1);
            //$pdf->Cell(2, 6, $entity->getName(),1,1);
            $pdf->Text(0, 0, $entity['name'],false,false,true,0,1);
            $pdf->SetFont('helvetica', 'B', 8);
            //$pdf->Cell(2, 6, $entity->getName2(),1,1);
            //$pdf->Write(1,$entity->getName(),'',false,'L',1);
            $pdf->Text(32, 5, $entity['name2'],false,false,true,0,1);

            //( 	code,	 	type,		x = '', 	y = '',	w = '',	h = '',xres = '',style = '',align = '')
            $pdf->SetFont('helvetica', '', 8);

            //$pdf->write1DBarcode( $entity->getArticleNo(), 'C128', 0, 8, 30, 15, 0.4, $style, 'T');

            $strings = $this->split_words($entity['description']);
            $line = 0;
            foreach($strings as $s){
                $pdf->Text(32,8 +$line, $s,false,false,true,0,1);
                $line += 3;
            }*/




            $html ='<h1> '.$entity['name'].'</h1>';
            $html .='<table border=0><tr><td style="width:130px;">';
            $html .='<img style="float:left; width: 120px ;max-height: 150px;" src="'.$entity['picurl'] .'">';
            $html .= '</td><td >';
            $html .= '<h2>'.$entity['name2'].'</h2>
                <p>'.$entity['description'].'</p>';
            $html .= '</td></tr></table>';
            //TCPDF::writeHTMLCell	(w,h,x,y,html = '',border = 0,ln = 0,fill = false,reseth = true,align = '',autopadding = true )
            $w = 140;
            $h = 95;

            $html.= '</div>';
            if($index&1)  {
                $pdf->writeHTMLCell($w,$h,'' ,'' ,$html,0,1,false,false,'',false);
            }else{
                $pdf->writeHTMLCell($w,$h,' ' ,'' ,$html,0,0,false,false,'',false);
            }
            $index++;



        }
        $html .= '</body></html>';


        //$response = new Response($html);
        //return $response;



       // $pdf->writeHTML($html, true, false, true, false, '');



        $date = date('U');
        $pdf->Output("print/".$date."_A6.pdf", 'F');

        return $this->render('BSDataBundle:Product:print.html.twig', array(
            'urlPDF'=> "/print/".$date."_A6.pdf",
            'back' =>   $this->getRequest()->server->get('HTTP_REFERER')
        ));

    }


    private function buildLable($pdf,Product $entity,$width = 95,$height= 25){

        $pdf->AddPage('L',array($width,$height));
        //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

        $pdf->SetFont('helvetica', 'B', 11);
        //$pdf->Write(1,$entity->getName(),'',false,'L',1);
        //$pdf->Cell(2, 6, $entity->getName(),1,1);
        $pdf->Text(0, 0, $entity->getName(),false,false,true,0,1);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Text(32, 5, $entity->getName2(),false,false,true,0,1);
        //$pdf->Cell(2, 6, $entity->getName2(),1,1);
        //$pdf->Write(1,$entity->getName(),'',false,'L',1);

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
            'text' => false,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 0
        );
        //( 	code,	 	type,		x = '', 	y = '',	w = '',	h = '',xres = '',style = '',align = '')

        $pdf->write1DBarcode( $entity->getArticleId(), 'EAN8', 1, 8, 30, 10, 0.5, $style, 'T');
        $pdf->Text(2, 17, $entity->getArticleNo(),false,false,true,0,1);
        $pdf->SetFont('helvetica', '', 7);
        $strings = $this->split_words($entity->getLabelText());
        $line = 0;
        foreach($strings as $s){
            $pdf->Text(32,9 +$line, $s,false,false,true,0,1);
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
