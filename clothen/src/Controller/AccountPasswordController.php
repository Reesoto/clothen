<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{
    private $em;

    /**
     * @param $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }


    /**
     * @Route("/account/edit-password", name="account_password")
     */
    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {
        $notification = null;
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('old_password')->getData();
            if($encoder->isPasswordValid($user, $old_pwd)) {
                $new_pwd = $form->get('new_password')->getData();
                $new_pwd = $encoder->hashPassword($user, $new_pwd);

                $user->setPassword($new_pwd);
                $this->em->persist($user);
                $this->em->flush();
                $notification = [
                    'body'      => "Your password has been updated",
                    'result'    => true
                ];
            } else {
                $notification = [
                    'body'      => "Invalid current password",
                    'result'    => false
                ];
            }
        }

        return $this->render('account/password.html.twig', [
            "form"  => $form->createView(),
            "notification"  => $notification
        ]);
    }
}
