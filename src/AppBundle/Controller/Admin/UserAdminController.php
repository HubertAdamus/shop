<?php

namespace AppBundle\Controller\Admin;

use AppBundle\Entity\User;
use AppBundle\Form\UserFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;


class UserAdminController extends Controller
{
    /**
     * @Route("/user", name="admin_user_list")
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findAll();

        return $this->render('admin/user/list.html.twig', array(
            'users' => $users
        ));
    }

    /**
     * @Route("/user/new", name="admin_user_new")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $user = new User();

        $form = $this->createForm(UserFormType::class, $user, array(
            'validation_groups' => array('create')
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $users = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($users);
            $em->flush();

            $this->addFlash('success', 'User created!');

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user/new.html.twig', [
            'userForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/user/{id}/edit", name="admin_user_edit")
     * @param Request $request
     * @param User $users
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction(Request $request, User $users)
    {
        $form = $this->createForm(UserFormType::class, $users, array(
            'validation_groups' => array('edit')
        ));

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $users = $form->getData();

            $em = $this->getDoctrine()->getManager();
            $em->persist($users);
            $em->flush();

            $this->addFlash('success', 'User updated!');

            return $this->redirectToRoute('admin_user_list');
        }

        return $this->render('admin/user/edit.html.twig', [
            'userForm' => $form->createView()
        ]);
    }

}