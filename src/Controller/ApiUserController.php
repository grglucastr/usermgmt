<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Entity\Group;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\NoResultException;

class ApiUserController extends AbstractController
{
    
    /**
     * @Route("/api/users", name="api_users", methods={"GET"})
     */
    public function index(){
        $urepo = $this->getDoctrine()->getRepository(User::class);
        $users = $urepo->findAll();
        return $this->json($users);
    }
    
    /**
     * @Route("/api/users", name="api_add_users", methods={"POST"})
     */
    public function addUser(Request $request)
    {
        $toArray = true;
        $arrRequest = json_decode($request->getContent(), $toArray);
        
        $user = new User();
        $user->setUsername($arrRequest['username']);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
        
        return $this->json($user, Response::HTTP_CREATED);
    }
    
    /**
     * @Route("/api/users/{userIdOrUsername}", name="api_get_user_by_id", methods={"GET"})
     */
    public function getUserById($userIdOrUsername)
    {
        $userFound = $this->findTheUser($userIdOrUsername);
        if(is_object($userFound)){
            return $this->json($userFound);
        }
        return $this->json($userFound, Response::HTTP_NOT_FOUND);
    }
    
    
    /**
     * @Route("/api/users/{userIdOrUsername}", name="api_delete_user_by_id", methods={"DELETE"})
     */
    public function deleteUserById($userIdOrUsername)
    {
        $userFound = $this->findTheUser($userIdOrUsername);
        
        if(is_object($userFound)){
            $em = $this->getDoctrine()->getManager();
            $em->remove($userFound);
            $em->flush();
            
            return $this->json([
                "done" => true,
                "done_message" => "User removed."
            ]);
        }
        
        return $this->json($userFound, Response::HTTP_NOT_FOUND);        
    }
    
    
    /**
     * @Route("/api/users/{userIdOrUsername}/groups/{groupId}", name="api_add_user_to_group", methods={"POST"})
     */
    public function addUserToGroup($userIdOrUsername, $groupId){
        
        
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
    
    
    private function findTheUser($userIdOrUsername){
        try{
            
            $urepo = $this->getDoctrine()->getRepository(User::class);
            $user = $urepo->findByUserIdOrUserName($userIdOrUsername);
            return $user;
            
        }catch (NoResultException $e){
            return [
                "error" => true,
                "error_message" => "User not found.",
            ];
        }
    }
}
