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
        $repo = $this->getDoctrine()->getRepository(User::class);
        $users = $repo->findAll();
        
        return $this->render('users/users.html.twig', [
            "users" => $users
        ]);
    }
    
    /**
     * @Route("/users/add", name="form_user_add", methods={"GET"});
     */
    public function userAdd()
    {
        return $this->render('users/uform.html.twig',[
            'user' => new User()
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
        $repo = $this->getDoctrine()->getRepository(User::class);
        $user = $repo->findOneBy(['id' => $userId]);
        
        return $this->render('users/uform.html.twig',[
            "user" => $user    
        ]);
    }
    
    /**
     * @Route("/users/{userId}/delete", name="form_delete_user", methods={"POST"})
     * */
    public function deleteUser($userId)
    {
        $repo = $this->getDoctrine()->getRepository(User::class);
        $user = $repo->findOneBy(['id' => $userId]);
        
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        
        $arrResponse = ['done' => true];
        
        return $this->json($arrResponse);
    }
    
    /**
     * @Route("/users/{userId}/groups", name="form_show_user_to_group", methods={"GET"})
     * */
    public function userGroups($userId)
    {
        $uRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $uRepo->find($userId);
        
        $gRepo = $this->getDoctrine()->getRepository(Group::class);
        $groups = $gRepo->findGroupsWhereUserIsNotIn($userId);
       
        $groupsAvailable = count($groups) > 0;
               
        
        return $this->render('users/usergroups.html.twig', [
            "user" => $user,
            "groups" => $groups,
            "groupsAvailable" => $groupsAvailable
        ]);
    }
        
    
    /**
     * @Route("/users/{userId}/groups", name="form_save_user_to_group", methods={"POST"})
     * */
    public function userAddToGroup(Request $request, $userId)
    {
        $gRepo = $this->getDoctrine()->getRepository(Group::class);
        $group = $gRepo->find($request->get("group"));
        
        $uRepo = $this->getDoctrine()->getRepository(User::class);
        $user = $uRepo->find($userId);
        
        $user->addUgroup($group);
        
        $em = $this->getDoctrine()->getManager();
        $em->merge($user);
        $em->flush();
        
        $url = "/users/".$userId."/groups";
        return $this->redirect($url);
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
        
        return $this->render('groups/group_details.html.twig', [
            "group" => $group
        ]);
    }
    
    
    
  
}