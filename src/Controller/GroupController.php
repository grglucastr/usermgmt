<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Group;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;


class GroupController extends AbstractController
{
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
     * @Route("/groups/add", name="form_group", methods={"GET"})
     */
    public function groupsForm()
    {
        return $this->render('groups/gform.html.twig',[
            "group" => new Group()
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
    
    /**
     * @Route("/groups/{groupId}", name="form_group_details", methods={"GET"})
     */
    public function groupDetails($groupId)
    {
        $repo = $this->getDoctrine()->getRepository(Group::class);
        $group = $repo->find($groupId);
        
        $uRepo = $this->getDoctrine()->getRepository(User::class);
        $users = $uRepo->findUsersWhereNotInGroup($groupId);
        
        $usersAvailable = count($users) > 0;
        
        return $this->render('groups/group_details.html.twig', [
            "group" => $group,
            "users" => $users,
            "usersAvailable" => $usersAvailable
        ]);
        
    }
    
    /**
     * @Route("/groups/{groupId}/users", name="form_group_user_bind", methods={"POST"})
     * */
    public function groupAddUser(Request $request, $groupId)
    {
        $uRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $uRepo->find($request->get("user"));
        
        $gRepo = $this->getDoctrine()->getRepository(Group::class);
        $group = $gRepo->find($groupId);
        
        $group->addUser($user);
        
        $em = $this->getDoctrine()->getManager();
        $em->merge($group);
        $em->flush();
        
        $url = "/groups/".$groupId;
        return $this->redirect($url);
    }
    
}
