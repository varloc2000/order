<?php

namespace Insider\CurrencyBundle\Controller;

use Insider\CurrencyBundle\Form\UserCurrencyType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserCurrencyController extends Controller
{
    public function changeUserCurrencyAction(Request $request)
    {
        $user = $this->getUser();

        $form = $this->createForm(
            new UserCurrencyType(),
            $user,
            array(
                'action' => $this->generateUrl('insider_currency_user_currency_change'),
            )
        );

        if ($request->isMethod('POST')) {
            $form->handleRequest($request);

            $user = $form->getData();

            /** @var \FOS\UserBundle\Model\UserManagerInterface $userManager */
            $userManager = $this->get('fos_user.user_manager');
            $userManager->updateUser($user);

            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('InsiderCurrencyBundle:User:user_currency_form.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}
