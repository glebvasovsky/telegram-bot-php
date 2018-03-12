<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * 
 * @ORM\Table(name="worksheet")
 */
class Worksheet 
{
    public const STATUS_OPEN = 'open';
    
    public const STATUS_SENDED = 'sended';
    
    public const STATUS_ERROR = 'error';
    
    /**
     * @var int 
     * 
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="worksheet_pkey")
     */
    protected $id;
    
    /**
     * @var string
     * 
     * @ORM\Column(type="string", name="status", nullable = true)
     */
    protected $status;
    
    /**
     * @var User
     * 
     * @ORM\ManyToOne(targetEntity="User", inversedBy="worksheets")
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $user;
    
    /**
     * @var Collection 
     * 
     * @ORM\OneToMany(targetEntity="Field", mappedBy="worksheet")
     */
    protected $fields;

    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="create")
     * 
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;
    
    /**
     * @var DateTime
     *
     * @Gedmo\Timestampable(on="update")
     * 
     * @ORM\Column(name="updated_at", type="datetime")
     */
    protected $updatedAt;
    
    public function __construct()
    {
        $this->fields = new ArrayCollection();
    }
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }
    
    /**
     * @param string $status
     * 
     * @return self
     */
    public function setStatus(string $status): self
    {
        $this->status = $status;
        
        return $this;
    }
    
    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }
    
    /**
     * @param DateTime $createdAt
     * 
     * @return self
     */
    public function setCreatedAt(DateTime $createdAt): self
    {
        $this->createdAt = $createdAt;
        
        return $this;
    }
    
    /**
     * @return DateTime
     */
    public function getUpdatedAt(): DateTime
    {
        return $this->updatedAt;
    }
    
    /**
     * @param DateTime $updatedAt
     * 
     * @return self
     */
    public function setUpdatedAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        
        return $this;
    }
    
    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
    
    /**
     * @param User $user
     * 
     * @return self
     */
    public function setUser(User $user): self
    {
        $this->user = $user;
        
        return $this;
    }
    
    /**
     * @param Field $field
     *
     * @return self
     */
    public function addField(Field $field): self
    {
        if (!$this->fields->contains($field)) {
            $this->fields[] = $field;
        }
        
        return $this;
    }

    /**
     * @param Field $field
     * 
     * @return self
     */
    public function removeField(Field $field): self
    {
        if ($this->fields->contains($field)) {
            $this->fields->removeElement($field);
        }
        
        return $this;
    }

    /**
     * @return Collection | Field[]
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }
}
