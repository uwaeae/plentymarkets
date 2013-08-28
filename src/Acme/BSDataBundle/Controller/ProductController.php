<?php

namespace Acme\BSDataBundle\Controller;

use Acme\PlentyMarketsBundle\Controller\PlentySoapClient;
use Doctrine\ORM\Query\ResultSetMapping;
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

    private $limit = 50;

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
            $page, //$this->get('request')->query->get('page', 1)/*page number*/,
            $this->limit/*limit per page*/
        );

        // parameters to template
        return compact('pagination');
    }


    public function stockAction($id)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('BSDataBundle:Product')->find($id);

        $form = $this->createFormBuilder($entity)
            ->add('Stock', 'entity', array(
                'class' => 'BSDataBundle:Stock',
                'data' => $entity->getStock()
            ))
            //->add('id','hidden',array('label'=>'ID','read_only'=>true,'data'=>$id))
            ->getForm();;
        $request = $this->getRequest();
        if ('POST' === $request->getMethod()) {
            $form->bindRequest($request);
            if ($form->isValid()) {
                $data = $form->getData();
                $em->persist($data);
                $em->flush();

            }
        }
        return $this->render('BSDataBundle:Product:stock.html.twig', array(
            'form' => $form->createView(),
            'entity' => $entity
        ));


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
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),);

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
    public function searchCodeAction($page, $search)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 'p')
            ->add('from', 'BSDataBundle:Product p')
            ->add('where',
                $qb->expr()->like('p.article_no', '?1')
            )->setParameter('1', $search . '%');


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $page, //$this->get('request')->query->get('page', 1)/*page number*/,
            $this->limit/*limit per page*/
        );
        return $this->render('BSDataBundle:Product:index.html.twig', array(
            'pagination' => $pagination));
    }

    /**
     * Finds and displays a Products by Article_Name.
     *
     * @Route("/{id}/show", name="product_show")
     * @Template()
     */
    public function searchNameAction($page, $search)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 'p')
            ->add('from', 'BSDataBundle:Product p')
            ->add('where',
                $qb->expr()->like('p.name', '?1')
            )->setParameter('1', '%' . $search . '%');

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $page, //$this->get('request')->query->get('page', 1)/*page number*/,
            $this->limit/*limit per page*/
        );

        return $this->render('BSDataBundle:Product:index.html.twig', array(
            'pagination' => $pagination));
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
    public function searchLateinAction($page, $search)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->add('select', 'p')
            ->add('from', 'BSDataBundle:Product p')
            ->add('where',
                $qb->expr()->like('p.name2', '?1')
            )->setParameter('1', '%' . $search . '%');


        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $page, //$this->get('request')->query->get('page', 1)/*page number*/,
            $this->limit/*limit per page*/
        );


        return $this->render('BSDataBundle:Product:index.html.twig', array(
            'pagination' => $pagination));
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
        $form = $this->createForm(new ProductType(), $entity);

        return array(
            'entity' => $entity,
            'form' => $form->createView()
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
        $entity = new Product();
        $request = $this->getRequest();
        $form = $this->createForm(new ProductType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('BSDataBundle_product_show', array('id' => $entity->getId())));

        }

        return array(
            'entity' => $entity,
            'form' => $form->createView()
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
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
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

        $editForm = $this->createForm(new ProductType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('BSData_product_edit', array('id' => $id)));
        }

        return array(
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
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
            ->getForm();
    }

    public function createDummyAction()
    {
        $form = $this->buildDummyForm();

        return array(
            'form' => $form->createView(),

        );

    }


    public function lableAction()
    {

        return $this->render('BSDataBundle:Product:lable.html.twig', array(
            'urlPDF' => "/print.pdf",
        ));


    }

    public function printLableAction()
    {
        $request = $this->getRequest();

        $form = $request->request->get('labelform');
        $entity = new Product();
        $entity->setArticleId($form['articleid']);
        $entity->setArticleNo($form['articlecode']);
        $entity->setName($form['name']);
        $entity->setName2($form['name2']);
        $entity->setLabelText($form['description']);
        $entity->setDescriptionShort($form['descriptionShort']);
        $pdf = $this->get('io_tcpdf');
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

        $pdf = $this->buildLable($pdf, $entity);


        //$pdfUrl = "print/".$entity->getArticleNo().".pdf";
        $pdfUrl = "print/lable.pdf";
        $pdf->Output($pdfUrl, 'F');
        $result = array('pdfurl' => '/' . $pdfUrl);

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }

    public function searchAction($search)
    {
        $em = $this->getDoctrine()->getEntityManager();


       $rsm = new ResultSetMapping();
        $rsm->addEntityResult('BSDataBundle:Product', 'p');
        $rsm->addFieldResult('p', 'article_id', 'article_id');
        $rsm->addFieldResult('p', 'article_no', 'article_no');
        $rsm->addFieldResult('p', 'EAN', 'EAN');
        $rsm->addFieldResult('p', 'name', 'name');
        $rsm->addFieldResult('p', 'name2', 'name2');
        $rsm->addFieldResult('p', 'd', 'label_text');
        $rsm->addFieldResult('p', 'd2', 'description_short');
        $rsm->addFieldResult('p', 'picurl', 'picurl');
        $query = $em->createNativeQuery('
        SELECT
         null article_id,
          a.code article_no,
          null EAN,
          a.name name,
          a.latein name2,
          a.instructions d,
          a.labeltext d2,
          null picurl
        FROM Plant a
        where
            a.name like "%' . $search . '%" or
            a.code like "' . $search . '%" or
            a.latein like "' . $search . '%"
        Union
        SELECT
          b.article_id article_id,
          b.article_no article_no,
          b.EAN ,
          b.name name,
          b.name2 name2,
          b.description d,
          b.picurl picurl,
          b.description_short d2
        FROM  Product b
        where
            b.name like "%' . $search . '%" or
            b.article_no like "' . $search . '%" or
            b.name2 like "' . $search . '%"
        order by name
            ', $rsm);

        $result = $query->getArrayResult();

        $response = new Response(json_encode($result));
        $response->headers->set('Content-Type', 'application/json');

        return $response;


    }

    public function syncAction($code)
    {

        $oPlentySoapClient = new PlentySoapClient($this, $this->getDoctrine());
        $oPlentySoapClient->doGetItemsBaseByOptions(array('ItemNo' => $code));

        $em = $this->getDoctrine()->getEntityManager();

        $product = $em->getRepository('BSDataBundle:Product')->findOneBy(array('article_no' => $code));

        $item = array();
        $item['article_id'] = $product->getArticleId();
        $item['article_no'] = $product->getArticleNo();
        $item['name'] = $product->getName();
        $item['name2'] = $product->getName2();
        $item['label_text'] = $product->getLabelText();
        $item['description_short'] = $product->getDescriptionShort();
        $item['price'] = $product->getPrice();
        $item['picurl'] = $product->getPicurl();
        $response = new Response(json_encode($item));
        $response->headers->set('Content-Type', 'application/json');

        return $response;

    }


    private function createLabelJSON($products)
    {


    }


    public function printAction()
    {
        $id = $this->getRequest()->get('id');
        $quantity = $this->getRequest()->get('quantity');
        $width = $this->getRequest()->get('width');
        $height = $this->getRequest()->get('height');

        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BSDataBundle:Product')->find($id);
        $pdf = $this->get('io_tcpdf');
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
        for ($i = 0; $i < $quantity; $i++) {
            $pdf = $this->buildLable($pdf, $entity, $width, $height);
        }


        //$pdf->Output("print/".$entity->getArticleNo().".pdf", 'F');
        $pdf->Output("print/lable.pdf", 'F');

        return $this->render('BSDataBundle:Product:print.html.twig', array(
            'urlPDF' => "/print/lable.pdf",
        ));


    }

    public function printA6Action()
    {


        $em = $this->getDoctrine()->getEntityManager();

        $data = $this->get('request')->request->get('A6Lable');


        $pdf = $this->get('io_tcpdf');
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
        $pdf->setCellMargins(15, 5, 1, 1);

        $pdf->AddPage('L');

        $style = array(
            'position' => '',
            'align' => 'L',
            'stretch' => false,
            'fitwidth' => false,
            'cellfitalign' => '',
            'border' => false,
            'hpadding' => '0',
            'vpadding' => '0',
            'fgcolor' => array(0, 0, 0),
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
        $pos_x = 0;
        $pos_y = 0;
        foreach ($data as $entity) {

            if ($index == 1) {
                $pos_x += 150;
            }
            if ($index == 2) {
                $pos_x = 0;
                $pos_y += 100;
            }
            if ($index == 3) {

                $pos_x += 150;
            }

            $pdf->SetFont('helvetica', 'B', 16);

            // Image($file, $x='', $y='', $w=0, $h=0, $type='', $link='', $align='', $resize=false, $dpi=300, $palign='', $ismask=false, $imgmask=false, $border=0, $fitbox=false, $hidden=false, $fitonpage=false)
            if (strlen($entity['picurl']) > 5) $pdf->Image($entity['picurl'], $x = $pos_x + 5, $y = $pos_y + 5, $w = 40, $h = 40, $type = '', $link = '', $align = '', $resize = true, $dpi = 300, $palign = '', $ismask = false, $imgmask = false, $border = 0, $fitbox = false, $hidden = false, $fitonpage = false);
            //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')
            //$pdf->Cell(2, 6, $entity->getName(),1,1);

            //$pdf->MultiCell(55, 5,  $entity['name'] , 1, 'L', 1, 0, '', '', true);
            $pdf->Text($pos_x + 30, $pos_y, $entity['name'], false, false, true, 0, 1, "L");
            $pdf->SetFont('helvetica', 'B', 10);
            //$pdf->Cell(2, 6, $entity->getName2(),1,1);
            //$pdf->Write(1,$entity->getName(),'',false,'L',1);

            $pdf->Text($pos_x + 30, $pos_y + 5, '' . $entity['name2'], false, false, true, 0, 1, "L");

            //( 	code,	 	type,		x = '', 	y = '',	w = '',	h = '',xres = '',style = '',align = '')

            $pdf->Text($pos_x + 30, $pos_y + 10, $entity['articlecode'], false, false, true, 0, 1);
            //$pdf->write1DBarcode( $entity->getArticleNo(), 'C128', 0, 8, 30, 15, 0.4, $style, 'T');
            $pdf->SetFont('helvetica', '', 10);

            $strings = $this->split_words($entity['description']);
            $line = 0;
            foreach ($strings as $s) {
                $pdf->Text($pos_x + 30, $pos_y + 15 + $line, $s, false, false, true, 0, 1);
                $line += 4;
            }
            $index++;


        }
        //    $html .= '</body></html>';


        //$response = new Response($html);
        //return $response;


        // $pdf->writeHTML($html, true, false, true, false, '');


        $date = date('U');
        $pdf->Output("print/" . $date . "_A6.pdf", 'F');

        return $this->render('BSDataBundle:Product:print.html.twig', array(
            'urlPDF' => "/print/" . $date . "_A6.pdf",
            'back' => $this->getRequest()->server->get('HTTP_REFERER')
        ));

    }


    private function buildLable($pdf, Product $entity, $width = 98, $height = 25)
    {

        $pdf->AddPage('L', array($width, $height));
        //Cell($w, $h=0, $txt='', $border=0, $ln=0, $align='', $fill=0, $link='', $stretch=0, $ignore_min_height=false, $calign='T', $valign='M')

        $pdf->SetFont('helvetica', 'B', 11);
        //$pdf->Write(1,$entity->getName(),'',false,'L',1);
        //$pdf->Cell(2, 6, $entity->getName(),1,1);
        $pdf->Text(0, 0, $entity->getName(), false, false, true, 0, 1);
        $pdf->SetFont('helvetica', 'B', 8);
        $pdf->Text(32, 4, $entity->getName2(), false, false, true, 0, 1);
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
            'fgcolor' => array(0, 0, 0),
            'bgcolor' => false, //array(255,255,255),
            'text' => false,
            'font' => 'helvetica',
            'fontsize' => 8,
            'stretchtext' => 0
        );
        //( 	code,	 	type,		x = '', 	y = '',	w = '',	h = '',xres = '',style = '',align = '')

        $pdf->write1DBarcode($entity->getArticleId(), 'EAN8', 1, 8, 30, 10, 0.5, $style, 'T');
        $pdf->Text(2, 17, $entity->getArticleNo(), false, false, true, 0, 1);
        $pdf->SetFont('helvetica', '', 7);
        $strings = $this->split_words($entity->getDescriptionShort());
        $line = 0;
        foreach ($strings as $s) {
            $pdf->Text(30, 8 + $line, $s, false, false, true, 0, 1);
            $line += 4;
        }


        //$pdf->Cell(15, 2,  $strings[0],0,1);
        // MultiCell($w, $h, $txt, $border=0, $align='J', $fill=0, $ln=1, $x='', $y='', $reseth=true, $stretch=0, $ishtml=false, $autopadding=true, $maxh=0)
        //$pdf->MultiCell(58, 30, ,0,'L',0,1,'','');

        return $pdf;

    }

    function split_words($string, $max = 58)
    {
        //$words = str_word_count($string, 1);
        $words = explode(' ', trim($string));


        $lines = array();
        $line = '';

        foreach ($words as $k => $word) {
            $length = strlen($line . ' ' . $word);
            if ($length <= $max) {
                $line .= ' ' . trim($word);
            } else if ($length > $max) {
                if (!empty($line)) $lines[] = $line;
                $line = trim($word);
            } else {
                $lines[] = trim($line) . ' ' . $word;
                $line = '';
            }
        }
        $lines[] = ($line = trim($line)) ? $line : $word;

        return $lines;
    }


}
