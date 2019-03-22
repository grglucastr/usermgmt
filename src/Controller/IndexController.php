<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Group;
use App\Entity\User;

class IndexController extends AbstractController
{
    
    /**
     * @Route("/")
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Group::class);
        $groups = $repo->findAll();
        
        $repo = $this->getDoctrine()->getRepository(User::class);
        $users = $repo->findAll();
        
        
        return $this->render('index.html.twig', [
            'users' => $users,
            'groups' => $groups
        ]);
    }

}