<?php

namespace DJBlaster\BlasterBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Filesystem\Filesystem;
class FrontendController extends Controller
{
    public function homeAction()
    {
        return $this->render('DJBlasterBundle::frontend.html.twig');
    }
    
    public function clearCacheAction()
    {
        $fs = new Filesystem();
        $fs->remove($this->container->getParameter('kernel.cache_dir'));
        header( 'Location: http://djblaster.knceradio.com/' );
    }
}
