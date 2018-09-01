<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends Controller
{
    /**
     * @Route("/register", name="user_registration")
     */
    public function register(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // 1) build the form
        $user = new User();
        $form = $this->createForm(UserType::class, $user);

        // 2) handle the submit (will only happen on POST)
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            if ($this->findOneByMail($user->getMail()) === null) {
                // 3) encode the password
                $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
                $user->setPassword($password);

                // 4) save the User!
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                return $this->render(
                    'registration/register.html.twig',
                    array(
                        'form' => $form->createView(),
                        'text_alert' => 'You have been registered !',
                        'class_alert' => 'alert-success'
                    )
                );
            } else {
                return $this->render(
                    'registration/register.html.twig',
                    array(
                        'form' => $form->createView(),
                        'text_alert' => 'You have been already registered !',
                        'class_alert' => 'alert-danger'
                    )
                );
            }            
        }
        return $this->render(
            'registration/register.html.twig',
            array('form' => $form->createView())
        );
    }
    
    private function findOneByMail(string $mail)
    {
        $repository = $this->getDoctrine()->getManager()->getRepository(User::class);
        $result = $repository->findOneBy(array(
            'mail' => $mail
        ));
        return $result;
    }
}