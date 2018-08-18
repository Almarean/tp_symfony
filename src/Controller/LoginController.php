<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function index(AuthenticationUtils $authenticationUtils)
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();
        return $this->render('login/index.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error
        ));
    }
    
    /**
     * @Route("/user", name="user")
     */
    public function showUser(Security $security)
    {
        $user = $security->getUser();
        return $this->render('user.html.twig', array(
            'user' => $user
        ));
    }
    
    /**
     * @Route("/logout", name="logout")
     */
    public function logout()
    {
        return $this->render(
            'login/index.html.twig'
        );
    }


    /*
    private function createFormLogin()
    {
        return $this->createFormBuilder()
            ->add('mailAddress', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('submit', SubmitType::class)
            ->getForm();
    }
    
    private function fetchUSer(string $mail, string $password)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(User::class);
        $result = $repository->findOneBy(
                array(
                    'mail' => $mail,
                    'password' => $password
                )
        );
        return $result;
    }
     */
}