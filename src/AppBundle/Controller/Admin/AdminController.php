<?php

namespace AppBundle\Controller\Admin;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


class AdminController extends Controller
{
    /**
     * @Route("/", name="admin")
     */
    public function indexAction()
    {
        return $this->render('admin/admin/welcome.html.twig');
    }
}