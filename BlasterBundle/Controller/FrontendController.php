<?php

namespace DJBlaster\BlasterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class FrontendController extends Controller
{
    public function homeAction()
    {
        return $this->render('DJBlasterBundle::frontend.html.twig');
    }
}
