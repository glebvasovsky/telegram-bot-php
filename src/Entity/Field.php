<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * @ORM\Entity
 * 
 * @ORM\Table(name="field")
 */
class Field 
{
    /**
     * @var int 
     * 
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="field_pkey")
     */
    protected $id;
    
    /**
     * @var string
     * 
     * @ORM\Column(type="string", name="name")
     */
    protected $name;
    
    /**
     * @var int 
     * 
     * @ORM\Column(type="integer", name="min")
     */
    protected $min;
    
    /**
     * @var int 
     * 
     * @ORM\Column(type="integer", name="max")
     */
    protected $max;
    
    /**
     * @var string
     * 
     * @ORM\Column(type="string", name="regex")
     */
    protected $regex;
    
    /**
     * @var string
     * 
     * @ORM\Column(type="string", name="label")
     */
    protected $label;

    /**
     * @var string
     * 
     * @ORM\Column(type="string", name="description")
     */
    protected $description;
    
    /**
     * @var string
     * 
     * @ORM\Column(type="string", name="sample")
     */
    protected $sample;
    
    /**
     * @var string
     * 
     * @ORM\Column(type="string", name="value", nullable = true)
     */
    protected $value;
    
    /**
     * @var Worksheet
     * 
     * @ORM\ManyToOne(targetEntity="Worksheet", inversedBy="fields")
     * @ORM\JoinColumn(name="worksheet_id", referencedColumnName="id", onDelete="CASCADE")
     */
    protected $worksheet;
    
    /**
     * @var bool 
     * 
     * @ORM\Column(type="boolean", name="is_current")
     */
    protected $isCurrent;
    
    /**
     * @var bool 
     * 
     * @ORM\Column(type="boolean", name="is_hidden")
     */
    protected $isHidden;

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
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @param string $name
     * 
     * @return self
     */
    public function setName(string $name): self
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * @return string 
     */
    public function getRegex(): string
    {
        return $this->regex;
    }
    
    /**
     * @param string $regex
     * 
     * @return self
     */
    public function setRegex(string $regex): self
    {
        $this->regex = $regex;
        
        return $this;
    }
    
    /**
     * @return string 
     */
    public function getLabel(): string
    {
        return $this->label;
    }
    
    /**
     * @param string $label
     * 
     * @return self
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;
        
        return $this;
    }
    
    /**
     * @return string 
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    
    /**
     * @param string $description
     * 
     * @return self
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;
        
        return $this;
    }
    
    /**
     * @return string 
     */
    public function getSample(): string
    {
        return $this->sample;
    }
    
    /**
     * @param string $sample
     * 
     * @return self
     */
    public function setSample(string $sample): self
    {
        $this->sample = $sample;
        
        return $this;
    }
    
    /**
     * @return string 
     */
    public function getValue(): string
    {
        return $this->value;
    }
    
    /**
     * @param string $value
     * 
     * @return self
     */
    public function setValue(string $value): self
    {
        $this->value = $value;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getMin(): int
    {
        return $this->min;
    }
    
    /**
     * @param int $min
     * 
     * @return self
     */
    public function setMin(int $min): self
    {
        $this->min = $min;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getMax(): int
    {
        return $this->max;
    }
    
    /**
     * @param int $max
     * 
     * @return self
     */
    public function setMax(int $max): self
    {
        $this->max = $max;
        
        return $this;
    }
    
    /**
     * @return bool
     */
    public function getIsCurrent(): bool
    {
        return $this->isCurrent;
    }
    
    /**
     * @param bool $isCurrent
     * 
     * @return self
     */
    public function setIsCurrent(bool $isCurrent): self
    {
        $this->isCurrent = $isCurrent;
        
        return $this;
    }
    
    /**
     * @return bool
     */
    public function getIsHidden(): bool
    {
        return $this->isHidden;
    }
    
    /**
     * @param bool $isHidden
     * 
     * @return self
     */
    public function setIsHidden(bool $isHidden): self
    {
        $this->isHidden = $isHidden;
        
        return $this;
    }
    
    /**
     * @return Worksheet
     */
    public function getWorksheet(): Worksheet
    {
        return $this->worksheet;
    }
    
    /**
     * @param Worksheet $worksheet
     * 
     * @return self
     */
    public function setWorksheet(Worksheet $worksheet): self
    {
        $this->worksheet = $worksheet;
        
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
}
