<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\NoResultException;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Doctrine\Common\Persistence\ManagerRegistry;

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
        
        try {
            
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            return $this->json($user, Response::HTTP_CREATED);
            
        } catch (UniqueConstraintViolationException $e) {
            
            return $this->json([
                "error" => true,
                "error_message" => "Username already in use.",
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    
    /**
     * @Route("/api/users/{userIdOrUsername}", name="api_get_user_by_id", methods={"GET"})
     */
    public function getUserById($userIdOrUsername)
    {
        $userFound = $this->findTheUser($this->getDoctrine(), $userIdOrUsername);
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
        $userFound = $this->findTheUser($this->getDoctrine(), $userIdOrUsername);
        
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
     * @Route("/api/users/{userIdOrUsername}/groups/", name="api_list_user_groups", methods={"GET"})
     */
    public function getUserGroups($userIdOrUsername){
        $userFound = $this->findTheUser($this->getDoctrine(), $userIdOrUsername);
        if(is_object($userFound)){
            return $this->json($userFound->getUgroups()->toArray());
        }
        return $this->json($userFound, Response::HTTP_NOT_FOUND);
    }
    
    /**
     * @Route("/api/users/{userIdOrUsername}/groups/{groupId}", name="api_add_user_to_group", methods={"POST"})
     */
    public function addUserToGroup($userIdOrUsername, $groupId){
        
        $userFound = $this->findTheUser($this->getDoctrine(), $userIdOrUsername);
        if (is_object($userFound)) {
            
            $apiGroup = new ApiGroupController();
            $groupFound = $apiGroup->findTheGroup($this->getDoctrine(), $groupId);
                        
            if(is_object($groupFound)){
                
                $userFound->addUgroup($groupFound);
                $em = $this->getDoctrine()->getManager();
                $em->merge($userFound);
                $em->flush();
                return $this->json([
                    "done" => true,
                    "done_message" => "User added to group!"
                ], Response::HTTP_OK);
            }
            
            return $this->json($groupFound, Response::HTTP_NOT_FOUND);
        }
        return $this->json($userFound, Response::HTTP_NOT_FOUND);
    }
    
    /**
     * @Route("/api/users/{$userIdOrUsername}/groups/{groupId}/remove", name="api_remove_user_from_group", methods={"DELETE"})
     */
    public function removeUserFromGroup($userIdOrUsername, $groupId)
    {
        $userFound = $this->findTheUser($this->getDoctrine(), $userIdOrUsername);
        if (is_object($userFound)) {
            
            $apiGroup = new ApiGroupController();
            $groupFound = $apiGroup->findTheGroup($this->getDoctrine(), $groupId);
            
            if(is_object($groupFound)){
                
                $userFound->removeUgroup($groupFound);
                $em = $this->getDoctrine()->getManager();
                $em->merge($userFound);
                $em->flush();
                return $this->json([
                    "done" => true,
                    "done_message" => "User removed from group"
                ], Response::HTTP_OK);
            }
            
            return $this->json($groupFound, Response::HTTP_NOT_FOUND);
        }
        return $this->json($userFound, Response::HTTP_NOT_FOUND);
    }
   
    public function findTheUser(ManagerRegistry $doctrine,  $userIdOrUsername){
        try{
            
            $urepo = $doctrine->getRepository(User::class);
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
