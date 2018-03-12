<?php

namespace App\Service\EntityServices;

use App\Entity\User;
use App\Entity\Worksheet;
use App\Entity\Field;
use App\Service\EntityServices\UserManager;
use App\Service\EntityServices\FieldManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class WorksheetManager
{
    /**
     * @var EntityManagerInterface
     */
    protected $em;
    
    /**
     * @var ContainerInterface
     */
    protected $container;
    
    /**
     * @var UserManager
     */
    protected $userManager;
    
    /**
     * @var UserManager
     */
    protected $fieldManager;

    /**
     * @param ContainerInterface     $container
     * @param EntityManagerInterface $em
     * @param UserManager            $userManager
     */
    public function __construct(
            ContainerInterface $container, 
            EntityManagerInterface $em,
            UserManager $userManager,
            FieldManager $fieldManager
    ) {
        $this->container = $container;
        $this->em = $em;
        $this->userManager = $userManager;
        $this->fieldManager = $fieldManager;
    }
    
    /**
     * @param int   $telegramId
     * @param array $fields
     * 
     * @return Worksheet
     */
    public function create(int $telegramId, array $fields): Worksheet
    {
        $user = $this->userManager->getUserByTelegramId($telegramId);
        
        $worksheet = new Worksheet();
        $worksheet->setUser($user);
        
        foreach ($fields as $field) {
            $worksheetField = new Field();
            
            $worksheetField->setName($field->name);
            $worksheetField->setMin($field->min);
            $worksheetField->setMax($field->max);
            $worksheetField->setRegex($field->regexp);
            $worksheetField->setLabel($field->label);
            $worksheetField->setDescription($field->description);
            $worksheetField->setSample($field->sample);
            $worksheetField->setIsCurrent(false);
            $worksheetField->setIsHidden(false);
            $worksheetField->setWorksheet($worksheet);
            
            $this->em->persist($worksheetField);
        }
        
        $worksheet->setStatus(Worksheet::STATUS_OPEN);
        
        $this->em->persist($worksheet);
        $this->em->flush();
        
        $this->fieldManager->setCurrentField($worksheet);
         
        return $worksheet;
    }
    
    public function sendToMfo(Worksheet $worksheet): array
    {
        $result = [
            'error' => '',
        ];
        
        // TODO: Отправка анкеты в MFO API. Возвращает OK, ошибку валидации или 500 ошибку
        if (false) {
            $this->setStatusError($worksheet);
            $this->deleteFields($worksheet);
            
            $result = [
                'error' => 'fatal_error',
            ];
        } else {
            $this->setStatusSended($worksheet);
        }
        
        return $result;
    }
    
    /**
     * @param Worksheet $worksheet
     * 
     * @return Worksheet
     */
    public function setStatusSended(Worksheet $worksheet): Worksheet
    {
        $worksheet->setStatus(Worksheet::STATUS_SENDED);
        
        $this->em->persist($worksheet);
        $this->em->flush();
        
        return $worksheet;
    }
    
    /**
     * @param Worksheet $worksheet
     * 
     * @return Worksheet
     */
    public function setStatusError(Worksheet $worksheet): Worksheet
    {
        $worksheet->setStatus(Worksheet::STATUS_ERROR);
        
        $this->em->persist($worksheet);
        $this->em->flush();
        
        return $worksheet;
    }
    
    /**
     * @param Worksheet $worksheet
     * 
     * @return bool
     */
    public function deleteFields(Worksheet $worksheet): bool
    {
        $worksheetFields = $this->em->getRepository(Field::class)
                ->findBy([
                    'worksheet' => $worksheet,
                    'isHidden' => false,
                ]);
        
        if (empty($worksheetFields)) {
            throw new EntityNotFoundException("Полей для анкеты с id = {$worksheet->getId()} не найдено");
        }
        
        
        foreach ($worksheetFields as $field) {
            $field->setIsHidden(true);
            $field->setIsCurrent(false);
           
            $this->em->persist($field);
        }
        
        $this->em->flush();
        
        return true;
    }
}
