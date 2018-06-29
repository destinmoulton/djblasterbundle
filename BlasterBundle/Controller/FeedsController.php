<?php

namespace DJBlaster\BlasterBundle\Controller;

use \DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\JsonResponse;

class FeedsController extends Controller {
    public function djSigninsJSONAction(Request $request) {
        
        $params = json_decode($request->getContent());

        $count = 10;
        $ignore = ["Test show"];
        if($params !== null){
            $ignore = $params->ignore;
            $count = $params->count;
        }
        
        $signins = $this->getDoctrine()->getRepository('DJBlasterBundle:DJSignIn')
                                            ->getByEndTimeDesc($count, $ignore);
                                         
        $serializedEntity = $this->container->get('jms_serializer')->serialize($signins,'json');
        $response = new JsonResponse($serializedEntity);

        $response->headers->set('Access-Control-Allow-Origin', '*');
        return $response;
    }
}