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
 * @ORM\Table(name="`user`")
 */
class User 
{
    /**
     * @var int 
     * 
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="SEQUENCE")
     * @ORM\SequenceGenerator(sequenceName="user_pkey")
     */
    protected $id;
    
    /**
     * @var string
     * 
     * @ORM\Column(type="string", name="country", nullable = true,)
     */
    protected $country;
    
    /**
     * @var string
     * 
     * @ORM\Column(type="string", name="locale", nullable = true,)
     */
    protected $locale;
    
    /**
     * @var string
     * 
     * @ORM\Column(type="string", name="phone", nullable = true,)
     */
    protected $phone;
    
    /**
     * @var int 
     * 
     * @ORM\Column(type="integer", name="mfo_id", nullable = true,)
     */
    protected $mfoId;
    
    /**
     * @var int 
     * 
     * @ORM\Column(type="integer", name="telegram_id")
     */
    protected $telegramId;
    
    /**
     * @var Collection 
     * 
     * @ORM\OneToMany(targetEntity="Worksheet", mappedBy="user")
     */
    protected $worksheets;
    
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
        $this->worksheets = new ArrayCollection();
    }
    
    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * @return string | null
     */
    public function getCountry(): ?string
    {
        return $this->country;
    }
    
    /**
     * @param string $country
     * 
     * @return self
     */
    public function setCountry(string $country): self
    {
        $this->country = $country;
        
        return $this;
    }
    
    /**
     * @return string | null
     */
    public function getLocale(): ?string
    {
        return $this->locale;
    }
    
    /**
     * @param string $locale
     * 
     * @return self
     */
    public function setLocale(string $locale): self
    {
        $this->locale = $locale;
        
        return $this;
    }
    
    /**
     * @return string | null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }
    
    /**
     * @param string $phone
     * 
     * @return self
     */
    public function setPhone(string $phone): self
    {
        $this->phone = $phone;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getMfoId(): int
    {
        return $this->mfoId;
    }
    
    /**
     * @param int $mfoId
     * 
     * @return self
     */
    public function setMfoId(int $mfoId): self
    {
        $this->mfoId = $mfoId;
        
        return $this;
    }
    
    /**
     * @return int
     */
    public function getTelegramId(): int
    {
        return $this->telegramId;
    }
    
    /**
     * @param int $telegramId
     * 
     * @return self
     */
    public function setTelegramId(int $telegramId): self
    {
        $this->telegramId = $telegramId;
        
        return $this;
    }
    
    /**
     * @param Worksheet $worksheet
     *
     * @return self
     */
    public function addWorksheet(Worksheet $worksheet): self
    {
        if (!$this->worksheets->contains($worksheet)) {
            $this->worksheets[] = $worksheet;
        }
        
        return $this;
    }

    /**
     * @param Worksheet $worksheet
     * 
     * @return self
     */
    public function removeWorksheet(Worksheet $worksheet): self
    {
        if ($this->worksheets->contains($worksheet)) {
            $this->worksheets->removeElement($worksheet);
        }
        
        return $this;
    }

    /**
     * @return Collection | Worksheet[]
     */
    public function getWorksheets(): Collection
    {
        return $this->worksheets;
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
