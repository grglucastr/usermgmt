<?php 

namespace App\Controller;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Group;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;


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
     * @Route("/users/add", name="form_user_add", methods={"GET"});
     */
    public function userAdd()
    {
        return $this->render('users/uform.html.twig',[
            
        ]);
    }
    
    /**
     * @Route("/users/add", name="form_user_save", methods={"POST"});
     */
    public function userSave(Request $request)
    {
        $user = new User();
        $user->setUsername($request->get('user_name'));
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        
        return $this->redirect("/users");
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
        $repo = $this->getDoctrine()->getRepository(Group::class);
        $groups = $repo->findAll();
        
        return $this->render('groups/groups.html.twig', [
            "groups" => $groups
        ]);
    }
    
    /**
     * @Route("/groups/add", methods={"GET"})
     */
    
    public function groupsForm()
    {
        
        return $this->render('groups/gform.html.twig',[
            
        ]);
    }
    
    /**
     * @Route("/groups/add", methods={"POST"})
     */
    public function groupsSave(Request $request){
        
        $group = new Group();
        $group->setGroupName($request->get('group_name'));
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($group);
        $em->flush();
        
        return $this->redirect("/groups");
    }
    
    
    
  
}