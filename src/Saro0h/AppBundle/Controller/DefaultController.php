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
     * @cache(smaxage=60)
     */
    public function indexAction()
    {
        return array('date' => new \Datetime());
    }
}
