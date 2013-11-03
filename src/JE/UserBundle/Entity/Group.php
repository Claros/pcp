<?php

namespace JE\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use FOS\UserBundle\Model\Group as BaseGroup;
use \Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Group
 *
 * @ORM\Table(name="je_group")
 * @ORM\Entity(repositoryClass="JE\UserBundle\Entity\GroupRepository")
 */
class Group extends BaseGroup
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @Assert\NotBlank()
     */
    protected $name;

    /**
     * @var User $updatedBy
     *
     * @Gedmo\Blameable(on="update")
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumn(name="updated_by")
     */
    private $updatedBy;

    /**
     * @var \DateTime $updatedAt
     *
     * @Gedmo\Timestampable(on="update")
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="group")
     */
    private $users;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->roles = array('ROLE_USERS_VIEW', 'ROLE_GROUP_VIEW');
        $this->users = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
    
    /**
     * Add users
     *
     * @param User $users
     * @return Group
     */
    public function addUser(User $users)
    {
        $this->users[] = $users;
    
        return $this;
    }

    /**
     * Remove users
     *
     * @param User $users
     */
    public function removeUser(User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Set updatedBy
     *
     * @param User $updatedBy
     * @return Group
     */
    public function setUpdatedBy(User $updatedBy = null)
    {
        $this->updatedBy = $updatedBy;
    
        return $this;
    }

    /**
     * Get updatedBy
     *
     * @return User
     */
    public function getUpdatedBy()
    {
        return $this->updatedBy;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Group
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    
        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}