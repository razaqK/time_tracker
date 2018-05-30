<?php
/**
 * Created by PhpStorm.
 * User: kasalirazaq
 * Date: 5/26/18
 * Time: 9:12 AM
 */

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Template("default/index.html.twig")
     * @Route("/{reactRouting}", name="index", requirements={"reactRouting"="^(?!api).+"}, defaults={"reactRouting": null})
     */
    public function index()
    {
        return [];
    }
}