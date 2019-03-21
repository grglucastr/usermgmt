<?php
namespace App\Controller;

use App\Entity\Group;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class UserController extends AbstractController
{
    
    /**
     * @Route("/api/users", name="api_users")
     */
    
    public function index(){
        return new Response("List all users here!!!!!!!!");
    }
    
    /**
     * @Route("/api/users/{userId}/groups/{groupId}", name="api_remove_user_from_group", methods={"DELETE"})
     */
    public function removeUserFromGroup($userId, $groupId)
    {
        $uRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $uRepo->find($userId);
        
        $gRepo = $this->getDoctrine()->getRepository(Group::class);
        $group = $gRepo->find($groupId);
        
        $user->removeUgroup($group);
        
        $em = $this->getDoctrine()->getManager();
        $em->merge($user);
        $em->flush();
        
        return $this->json(["done" => true]);
    }
    
}

