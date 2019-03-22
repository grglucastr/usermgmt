<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Group;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\NoResultException;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;

class ApiGroupController extends AbstractController
{
    /**
     * @Route("/api/groups", name="api_list_groups", methods={"GET"})
     */
    public function index()
    {
        $repo = $this->getDoctrine()->getRepository(Group::class);
        $groups = $repo->findAll();
        return $this->json($groups);
    }
    
    /**
     * @Route("/api/groups/{groupId}", name="api_get_group_by_id", methods={"GET"})
     */
    public function getGroupById($groupId)
    {
        $groupFound = $this->findTheGroup($this->getDoctrine(), $groupId);
        if(is_object($groupFound)){
            return $this->json($groupFound, Response::HTTP_OK);
        }
        return $this->json($groupFound, Response::HTTP_NOT_FOUND);
    }
    
    /**
     * @Route("/api/groups", name="api_add_groups", methods={"POST"})
     */
    public function addGroup( Request $request )
    {
        $arr = json_decode($request->getContent(), true);
        
        $group = new Group();
        $group->setGroupName($arr['group_name']);
        
        $em = $this->getDoctrine()->getManager();
        $em->persist($group);
        $em->flush();
        
        return $this->json($group);
    }
        
        
    /**
     * @Route("/api/groups/{groupId}", name="api_delete_group", methods={"DELETE"})
     */
    public function deleteGroup($groupId)
    {
        $groupFound = $this->findTheGroup($this->getDoctrine(), $groupId);
        if(is_object($groupFound)){
            
            if( count($groupFound->getUsers()->toArray()) > 0){
                return $this->json([
                    "error" => true,
                    "error_message" => "Can not delete group with users."
                ], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            
            $em = $this->getDoctrine()->getManager();
            $em->remove($groupFound);
            $em->flush();
            
            return $this->json([
                "done" => true,
                "done_message" => "Group removed"
            ], Response::HTTP_OK);
            
        }
        return $this->json($groupFound, Response::HTTP_NOT_FOUND);
    }
    
    /**
     * @Route("/api/groups/{groupId}/users", name="api_list_group_users", methods={"GET"})
     */
    public function listGroupUsers($groupId)
    {
        $groupFound = $this->findTheGroup($this->getDoctrine(), $groupId);
        if(is_object($groupFound)){
            return $this->json($groupFound->getUsers()->toArray(), Response::HTTP_OK);
        }
        return $this->json($groupFound, Response::HTTP_NOT_FOUND);
    }
    
    public function findTheGroup(ManagerRegistry $doctrine, $groupId){
        $gRepo = $doctrine->getRepository(Group::class);
        $group = $gRepo->findOneBy(['id' => $groupId]);
        
        if(empty($group )){
            return [
                "error" => true,
                "error_message" => "Group not found.",
            ];
        }
        
        return $group;
    }
}
