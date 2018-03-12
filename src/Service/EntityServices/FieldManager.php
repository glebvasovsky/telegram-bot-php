<?php

namespace App\Service\EntityServices;

use App\Entity\User;
use App\Entity\Worksheet;
use App\Entity\Field;
use App\Service\EntityServices\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FieldManager
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
     * @param ContainerInterface     $container
     * @param EntityManagerInterface $em
     * @param UserManager            $userManager
     */
    public function __construct(
            ContainerInterface $container, 
            EntityManagerInterface $em,
            UserManager $userManager
    ) {
        $this->container = $container;
        $this->em = $em;
        $this->userManager = $userManager;
    }
    
    /**
     * @param int   $telegramId
     * @param array $fields
     * 
     * @return Worksheet
     */
//    public function create(int $telegramId, array $fields): Worksheet
//    {
//        $user = $this->userManager->getUserByTelegramId($telegramId);
//        
//        $worksheet = new Worksheet();
//        $worksheet->setUser($user);
//        
//        foreach ($fields as $field) {
//            $worksheetField = new Field();
//            
//            $worksheetField->setName($field->name);
//            $worksheetField->setMin($field->min);
//            $worksheetField->setMax($field->max);
//            $worksheetField->setRegex($field->regexp);
//            $worksheetField->setLabel($field->label);
//            $worksheetField->setDescription($field->description);
//            $worksheetField->setSample($field->sample);
//            $worksheetField->setIsCurrent(false);
//            $worksheetField->setWorksheet($worksheet);
//            
//            $this->em->persist($worksheetField);
//        }
//        
//        $worksheet->setStatus(Worksheet::STATUS_OPEN);
//        
//        $this->em->persist($worksheet);
//        $this->em->flush();
//        
//        $this->setCurrentField($worksheet);
//         
//        return $worksheet;
//    }
    
   /**
     * @param Worksheet $worksheet
     * 
     * @return Field
     * 
     * @throws EntityNotFoundException
     */
    public function setCurrentField(Worksheet $worksheet): Field
    {
        $fields = $this->em->createQueryBuilder()
                ->select('f')
                ->from(Field::class, 'f')
                ->where("f.worksheet = {$worksheet->getId()} AND f.value is null")
                ->orderBy('f.id')
                ->getQuery()
                ->getResult();
                
        if (empty($fields)) {
            throw new EntityNotFoundException("Полей для анкеты с id = {$worksheet->getId()} не найдено");
        }
                
        $currentField = $fields[0];
        $currentField->setIsCurrent(true);
        
        $this->em->persist($currentField);
        $this->em->flush();
        
        return $currentField;
    }


    /**
     * @param Worksheet $worksheet
     * 
     * @return Field | null
     */
    public function getCurrentField(Worksheet $worksheet): ?Field
    {
        $worksheetFields = $this->em->getRepository(Field::class)
                ->findBy([
                    'worksheet' => $worksheet,
                    'isHidden' => false,
                ]);
        
        if (empty($worksheetFields)) {
            throw new EntityNotFoundException("Полей для анкеты с id = {$worksheet->getId()} не найдено");
        }
        
        $currentField = null;
        
        foreach ($worksheetFields as $field) {
            if (true == $field->getIsCurrent()) {
                
                $currentField = $field;
            }
        }
        
        return $currentField;
    }
    
    /**
     * @param Field $field
     * @param string $value
     * 
     * @return Field
     */
    public function setValue(Field $field, string $value): Field
    {
        // Присваиваем значение полю
        $field->setValue($value);
        $field->setIsCurrent(false);
        
        $this->em->persist($field);
        $this->em->flush();
        
        // И переключаем индикатор на следующее поле
        $this->setCurrentField($field->getWorksheet());
        
        return $field;
    }
    
    /**
     * @param Field $field
     * @param string $value
     * 
     * @return bool
     */
    public function isValid(Field $field, string $value): bool
    {
        if (preg_match($field->getRegex(), $value)) {
            return true;
        }
        
        return false;
    }
}
