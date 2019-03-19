<?php 

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;


class IndexController extends AbstractController
{
    
    /**
     * @Route("/")
     */
    public function index()
    {
        return $this->render('index.html.twig', [
            
        ]);
    }
    
    /**
     * @Route("/users")
     */
    public function users()
    {
        return $this->render('users/users.html.twig', [
            
        ]);
    }
    
    /**
     * @Route("/users/add");
     */
    public function userAdd()
    {
        return $this->render('users/uform.html.twig',[
            
        ]);
    }
    
    /**
     * @Route("/users/{userId}/edit");
     */
    public function userEdit($userId)
    {
        return new Response("Load user info and edit");
    }
    
    /**
     * @Route("/groups")
     */
    public function groups()
    {
        return $this->render('groups/groups.html.twig', [
            
        ]);
    }
    
    /**
     * @Route("/groups/add")
     */
    
    public function groupsAdd() 
    {
        return $this->render('groups/gform.html.twig',[
            
        ]);
    }
  
}