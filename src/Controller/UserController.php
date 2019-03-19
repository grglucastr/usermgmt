<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends AbstractController
{
    
    /**
     * @Route("/api/users")
     */
    
    public function index(){
        return new Response("List all users here!!!!!!!!");
    }
    
}

