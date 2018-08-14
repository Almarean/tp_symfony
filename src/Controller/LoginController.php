<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function index(Request $request)
    {
        $formLogin = $this->createFormLogin();
        $formLogin->handleRequest($request);
        if ($formLogin->isSubmitted() && $formLogin->isValid()) {
            $mail = $formLogin['mailAddress']->getData();
            $password = $formLogin['password']->getData();
            $user = $this->fetchUSer($mail, $password);
            return $this->render('user.html.twig',
                array('user' => $user)
            );
        }
        return $this->render('login/index.html.twig',
            array('formLogin' => $formLogin->createView())
        );
    }
    
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
}