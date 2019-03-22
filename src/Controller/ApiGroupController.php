<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Group;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\NoResultException;
use Doctrine\Common\Persistence\ManagerRegistry;

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
        $repo = $this->getDoctrine()->getRepository(Group::class);
        $group = $repo->find($groupId);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($group);
        $em->flush();
        
        return $this->json(["done" => true]);
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
