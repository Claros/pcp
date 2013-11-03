<?php

namespace JE\FinancesBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * PaymentSlip
 *
 * @ORM\Table(name="je_payment_slip")
 * @ORM\Entity(repositoryClass="JE\FinancesBundle\Entity\PaymentSlipRepository")
 * @UniqueEntity("bv")
 * @Gedmo\Uploadable(allowOverwrite = true, filenameGenerator = "SHA1")
 */
class PaymentSlip
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="ref", type="string", length=40)
     * @Assert\NotBlank()
     * @Assert\Regex("/^\d+$/")
     */
    private $ref;

    /**
     * @var string
     *
     * @ORM\Column(name="bv", type="string", length=40, unique=true)
     * @Assert\NotBlank()
     * @Assert\Regex("/^BV\d+$/")
     */
    private $bv;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     * @Assert\NotBlank()
     */
    private $createdAt;

    /**
     * @var string
     *
     * @ORM\Column(name="client", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $client;

    /**
     * @var string
     *
     * @ORM\Column(name="student", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $student;

    /**
     * @var integer
     *
     * @ORM\Column(name="amount", type="integer")
     * @Assert\NotBlank()
     */
    private $amount;

    /**
     * @var integer
     *
     * @ORM\Column(name="number_of_days", type="integer")
     * @Assert\NotBlank()
     */
    private $numberOfDays;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255, nullable=true)
     * @Gedmo\UploadableFilePath
     */
    private $path;

    /**
     * @Assert\File(
     *     maxSize="8M",
     *     mimeTypes = {
     *      "application/pdf",
     *      "application/zip", "application/x-compressed", "application/x-zip-compressed", "multipart/x-zip",
     *      "application/excel", "application/vnd.ms-excel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet", "application/x-excel", "application/x-msexcel",
     *      "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document"},
     *     mimeTypesMessage = "Fichier ZIP, PDF, Word ou Excel",
     *     maxSizeMessage = "Fichier trop gros (8Mo max)"
     * )
     */
    private $file;

    public function __construct()
    {
        $this->bv = 'BV';
        $this->createdAt = new \DateTime;
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
     * Set ref
     *
     * @param string $ref
     * @return PaymentSlip
     */
    public function setRef($ref)
    {
        $this->ref = $ref;
    
        return $this;
    }

    /**
     * Get ref
     *
     * @return string 
     */
    public function getRef()
    {
        return $this->ref;
    }

    /**
     * Set bv
     *
     * @param string $bv
     * @return PaymentSlip
     */
    public function setBv($bv)
    {
        $this->bv = $bv;
    
        return $this;
    }

    /**
     * Get bv
     *
     * @return string 
     */
    public function getBv()
    {
        return $this->bv;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return PaymentSlip
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    
        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set client
     *
     * @param string $client
     * @return PaymentSlip
     */
    public function setClient($client)
    {
        $this->client = $client;
    
        return $this;
    }

    /**
     * Get client
     *
     * @return string 
     */
    public function getClient()
    {
        return $this->client;
    }

    /**
     * Set student
     *
     * @param string $student
     * @return PaymentSlip
     */
    public function setStudent($student)
    {
        $this->student = $student;
    
        return $this;
    }

    /**
     * Get student
     *
     * @return string 
     */
    public function getStudent()
    {
        return $this->student;
    }

    /**
     * Set amount
     *
     * @param integer $amount
     * @return PaymentSlip
     */
    public function setAmount($amount)
    {
        $this->amount = $this->strToNormalized($amount);
    
        return $this;
    }

    /**
     * Get amount
     *
     * @return integer 
     */
    public function getAmount()
    {
        return $this->normalizedToFloat($this->amount);
    }

    public function getUrssaf()
    {
        return $this->numberOfDays * 4 * 9.43 * 0.156 + $this->getAmount() * 0.024;
    }

    public function getTotalAmount()
    {
        return $this->getAmount() - $this->getUrssaf();
    }

    /**
     * Set numberOfDays
     *
     * @param integer $numberOfDays
     * @return PaymentSlip
     */
    public function setNumberOfDays($numberOfDays)
    {
        $this->numberOfDays = $numberOfDays;
    
        return $this;
    }

    /**
     * Get numberOfDays
     *
     * @return integer 
     */
    public function getNumberOfDays()
    {
        return $this->numberOfDays;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return PaymentSlip
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Get web path
     *
     * @return string
     */
    public function getWebPath()
    {
        return preg_replace('#^.+\.\./www/(.+)$#', '$1', $this->getPath());
    }

    /**
     * @param mixed $file
     */
    public function setFile($file)
    {
        $this->file = $file;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    public function hasFile()
    {
        return $this->path !== null;
    }

    private function strToNormalized($str)
    {
        $str = str_replace(',', '.', $str);
        $str = str_replace(' ', '', $str);
        return intval(100 * floatval($str));
    }

    private function normalizedToFloat($int)
    {
        if($int === null) return null;
        return floatval($int / 100);
    }
}
