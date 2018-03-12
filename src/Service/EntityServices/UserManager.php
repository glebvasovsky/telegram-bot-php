<?php

namespace App\Service\EntityServices;

use App\Entity\User;
use App\Entity\Worksheet;
use App\Entity\Field;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserManager
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
     * @param ContainerInterface     $container
     * @param EntityManagerInterface $em
     */
    public function __construct(ContainerInterface $container, EntityManagerInterface $em)
    {
        $this->container = $container;
        $this->em = $em;
    }
    
    /**
     * @param int $telegramId
     * 
     * @return User | null
     */
    public function checkIsRegisterByTelegramId(int $telegramId): ?User
    {
        return $this->em->getRepository(User::class)
            ->findOneBy([
                'telegramId' => $telegramId,
            ]);
    }
    
    /**
     * @param int $telegramId
     * 
     * @return User
     */
    public function create(int $telegramId): User
    {
        $user = new User();
        $user->setTelegramId($telegramId);
        
        $this->em->persist($user);
        $this->em->flush();
        
        file_put_contents($this->container->getParameter('kernel.project_dir').'/var/log/telegram.log', "create".PHP_EOL, FILE_APPEND);
        
        return $user;
    }
    
    /**
     * @param int $telegramId
     * 
     * @return User
     * 
     * @throws EntityNotFoundException
     */
    public function getUserByTelegramId(int $telegramId): User
    {
        file_put_contents($this->container->getParameter('kernel.project_dir').'/var/log/telegram.log', "getUserByTelegramId".PHP_EOL, FILE_APPEND);
        
        $user = $this->em->getRepository(User::class)
            ->findOneBy([
                'telegramId' => $telegramId,
            ]);
        
        if (empty($user)) {
            throw new EntityNotFoundException("Пользователем с telegramId = $telegramId не зарегистрирован");
        }
        
        return $user;
    }
    
    /**
     * @param int    $telegramId
     * @param string $locale
     * 
     * @return User
     */
    public function setLocale(int $telegramId, string $locale): User
    {
        $user = $this->getUserByTelegramId($telegramId);
        $user->setLocale($locale);

        $this->em->persist($user);
        $this->em->flush();

        file_put_contents($this->container->getParameter('kernel.project_dir').'/var/log/telegram.log', 'set locale'.PHP_EOL, FILE_APPEND);

        return $user;
    }
    
    /**
     * @param int    $telegramId
     * @param string $country
     * 
     * @return User
     */
    public function setCountry(int $telegramId, string $country): User
    {
        $user = $this->getUserByTelegramId($telegramId);
        $user->setCountry($country);

        $this->em->persist($user);
        $this->em->flush();

        file_put_contents($this->container->getParameter('kernel.project_dir').'/var/log/telegram.log', 'set country'.PHP_EOL, FILE_APPEND);

        return $user;
    }
    
    /**
     * @return Worksheet | null
     */
    public function getOpenWorksheet(int $telegramId): ?Worksheet
    {
        $user = $this->getUserByTelegramId($telegramId);
            
        $userWorksheets = $user->getWorksheets();

        $openWorksheet = null;
        
        // Если есть открытая анкета, ищем следующий вопрос в ней
        foreach ($userWorksheets as $userWorksheet) {
            if (Worksheet::STATUS_OPEN == $userWorksheet->getStatus()) {
                $openWorksheet = $userWorksheet;
            }
        }
        
        return $openWorksheet;
    }
}
