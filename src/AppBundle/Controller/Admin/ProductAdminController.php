<?php

namespace AppBundle\Controller\Admin;


use AppBundle\Form\ProductFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class ProductAdminController extends Controller
{
    /**
     * @Route("/product", name="admin_product_list")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        $query = $this->getDoctrine()
            ->getRepository('AppBundle:Product')
            ->createAllSortedQueryBuilder();

        $paginator = $this->get('knp_paginator');
        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1)
        );

        return $this->render('admin/product/list.html.twig', array(
            'pagination' => $pagination
        ));
    }

    /**
     * @Route("/new-product", name="admin_product_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $form = $this->createForm(ProductFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $product = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($product);
            $em->flush();

            $this->addFlash('success', 'Product created!');

            $message = \Swift_Message::newInstance()
                ->setSubject('Hello Email')
                ->setFrom('shop@example.com')
                ->setTo('fake@example.com')
                ->setBody("New product was created", 'text/html')
            ;
            $this->get('mailer')->send($message);
            $this->addFlash('success', 'Email sent!');

            return $this->redirectToRoute('admin_product_list');
        }

        return $this->render('admin/product/new.html.twig', [
            'productForm' => $form->createView()
        ]);
    }
}