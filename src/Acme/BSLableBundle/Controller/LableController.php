<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Florian Engler
 * Mail: florian.engler@gmx.de
 * Date: 24.01.13
 * Time: 23:02
 */

namespace Acme\BSLableBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class LableController extends Controller
{

    public function indexAction()
    {
        $fb = $this->createFormBuilder();
        $fb->add('deutsch','text');
        $fb->add('latein','text');
        $form = $fb->getForm();

        return $this->render('BSLableBundle:Default:base.html.twig', array('form' => $form->createView()));
    }


}
