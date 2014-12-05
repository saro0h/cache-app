<?php

namespace Saro0h\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     * @Template()
     * @Cache(smaxage=60)
     */
    public function indexAction()
    {
        return array();
    }

    /**
     * @Route("/content")
     * @Template()
     * @Cache(smaxage=20)
     */
    public function getContentAction()
    {
        return array('date' => new \Datetime());
    }
}
