<?php

namespace DJBlaster\BlasterBundle\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Session\Session;

use DJBlaster\BlasterBundle\Entity\DJSignIn;
use DJBlaster\BlasterBundle\Form\Type\DJSignInType;

class FrontendController extends Controller
{
    public function homeAction()
    {
        $session = $this->getRequest()->getSession();


        if(!$session->has('djsignin_information')){
            return $this->redirect($this->generateUrl('dj_blaster_djsignin'));
        }
        
        $data = array(
            'djsignin_information'=>$session->get('djsignin_information')
        );
        return $this->render('DJBlasterBundle::dj_main.html.twig', $data);
    }

    public function djsigninAction(Request $request)
    {
        $session = $this->getRequest()->getSession();
        $action = $this->generateUrl('dj_blaster_djsignin');
        $options = array('action' => $action);
        $form = $this->createForm(new DJSignInType, new DJSignIn, $options);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {

            if ($form->isValid()) {
                $em = $this->get('doctrine')->getManager();
                $data = $form->getData();

                // Automatically add people to the MailChimp mailing list
                // The configuration options are set in config.yml
                $mailChimp = $this->get('hype_mailchimp');
                $list = $mailChimp->getList()
                                      ->addMerge_vars(array(
                                          'FNAME' => $data->getDjFirstName(),
                                          'LNAME' => $data->getDjLastName()
                                  ))
                                  ->subscribe($data->getDjEmail());
                
                $em->persist($data);
                $em->flush();

                $session_data = array(
                    'dj_first_name' => $data->getDjFirstName(),
                    'dj_last_name' => $data->getDjLastName(),
                    'dj_email' => $data->getDjEmail(),
                    'show_end_time' =>$data->getShowEndTime()->format('H:i:s')
                );
                $session->set('djsignin_information', $session_data);

                return $this->redirect($this->generateUrl('dj_blaster_home'));

            }
        } else {
            // Clear the session information
            $session->remove('djsignin_information');
        }

        return $this->render('DJBlasterBundle::dj_signin.html.twig', array(
            'form' => $form->createView()
        ));
    }
    
    public function clearCacheAction()
    {
        $fs = new Filesystem();
        $fs->remove($this->container->getParameter('kernel.cache_dir'));
        header( 'Location: http://djblaster.knceradio.com/' );
    }
}
