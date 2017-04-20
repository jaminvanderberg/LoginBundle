<?php

namespace jaminvLoginBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;

class IndexController extends Controller {

    public function indexAction() {
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');
        
        $redirect = $this->container->getParameter('login_redirect');
        
        if ($authChecker->isGranted('ROLE_USER') ) {
            return new RedirectResponse($router->generate($redirect), 307 );
        }
        
        $csrfToken = $this->has('security.csrf.token_manager')
            ? $this->get('security.csrf.token_manager')->getToken('authenticate')->getValue()
            : null;

        return $this->render('welcome.html.twig', array('csrf_token' => $csrfToken));
    }
 
}
