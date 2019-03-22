<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
class User implements \JsonSerializable
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Group", inversedBy="users")
     */
    private $ugroups;

    /**
     * @ORM\Column(type="string", unique=true, length=80)
     */
    private $username;

    public function __construct()
    {
        $this->ugroups = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Group[]
     */
    public function getUgroups(): Collection
    {   
        return $this->ugroups;
    }

    public function addUgroup(Group $ugroup): self
    {
        if (!$this->ugroups->contains($ugroup)) {
            $this->ugroups[] = $ugroup;
        }

        return $this;
    }

    public function removeUgroup(Group $ugroup): self
    {
        if ($this->ugroups->contains($ugroup)) {
            $this->ugroups->removeElement($ugroup);
        }

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }
    public function jsonSerialize()
    {
        return [
          "id" => $this->id,
          "username" => $this->username 
        ];
        
    }

}
