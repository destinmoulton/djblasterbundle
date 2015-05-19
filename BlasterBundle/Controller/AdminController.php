<?php

namespace DJBlaster\BlasterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends Controller {
    public function indexAction() {
        return $this->render('DJBlasterBundle:Admin:admin_index.html.twig');
    }

}
