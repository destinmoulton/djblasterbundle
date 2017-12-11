<?php

namespace DJBlaster\BlasterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use DJBlaster\BlasterBundle\Entity\DJReadPSA;

class AdminController extends Controller {
    public function indexAction() {
        $psa_reads = $this->getDoctrine()
            ->getRepository('DJBlasterBundle:DJReadPSA')
            ->getRecentReads(20);

        return $this->render('DJBlasterBundle:Admin:admin_index.html.twig', array(
            'psa_reads'=>$psa_reads
        ));
    }

}
