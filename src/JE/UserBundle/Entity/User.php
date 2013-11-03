<?php

namespace JE\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * User
 *
 * @ORM\Table(name="je_user")
 * @ORM\Entity(repositoryClass="JE\UserBundle\Entity\UserRepository")
 */
class User extends BaseUser
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
     * @Assert\Length(min = "3", minMessage = "{{ limit }} caractères minimum")
     */
    protected $username;

    /**
     * @Assert\Length(min = "6", minMessage = "{{ limit }} caractères minimum")
     */
    protected $plainPassword;

    /**
     * @var string
     *
     * @ORM\Column(name="first_name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min = "2", minMessage = "{{ limit }} caractères minimum")
     */
    private $firstName;

    /**
     * @var string
     *
     * @ORM\Column(name="last_name", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(min = "2", minMessage = "{{ limit }} caractères minimum")
     */
    private $lastName;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=12)
     * @Assert\NotBlank()
     * @Assert\Length(min = "10", max = "12", minMessage = "Ceci n'est pas un numéro", maxMessage = "Ceci n'est pas un numéro")
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity="Group", inversedBy="users")
     * @Assert\NotNull(message="Choisissez un poste")
     */
    private $group;


    public function __construct()
    {
        parent::__construct();
    }

    public function __toString()
    {
        return $this->firstName.' '.$this->lastName;
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
     * @param mixed $group
     */
    public function setGroup($group)
    {
        $this->group = $group;
    }

    /**
     * @return mixed
     */
    public function getGroup()
    {
        return $this->group;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName($firstName)
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName($lastName)
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = preg_replace('#\D#', '', $phone);
    }

    /**
     * @return string
     */
    public function getPhone()
    {
        if(strlen($this->phone) === 10)
            return preg_replace('#^0([0-9])([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})$#', '+33 (0)$1 $2 $3 $4 $5', $this->phone);
        if(strlen($this->phone) === 11)
            return preg_replace('#^([0-9]{2})([0-9])([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})$#', '+$1 (0)$2 $3 $4 $5 $6', $this->phone);
        return preg_replace('#^([0-9]{2})0([0-9])([0-9]{2})([0-9]{2})([0-9]{2})([0-9]{2})$#', '+$1 (0)$2 $3 $4 $5 $6', $this->phone);
    }

    /**
     * @return array|void
     */
    public function getRoles()
    {
        return array_unique(array_merge(parent::getRoles(), $this->getGroup()->getRoles()));
    }
}
