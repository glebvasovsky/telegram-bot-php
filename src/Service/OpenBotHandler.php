<?php

namespace App\Service;

use App\Entity\User;
use App\Entity\Worksheet;
use App\Entity\Field;
use App\Service\EntityServices\UserManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OpenBotHandler 
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
     * @return array
     */
    public function sayHello(): array
    {
        if ($userManager->checkIsRegisterByTelegramId($telegramId)) {
            $user = $userManager->getUserByTelegramId($telegramId);
            
            $userWorksheets = $user->getWorksheets();

            // Если есть открытая анкета, ищем следующий вопрос в ней
            foreach ($userWorksheets as $userWorksheet) {
                if (Worksheet::STATUS_OPEN == $userWorksheet->getStatus()) {
                    $currentField = $worksheetManager->getCurrentField($userWorksheet);
                    
                    $locale = $user->getLocale() ?? 'ru';
                    
                    if (empty($currentField)) {
                        return new JsonResponse([
                            'method' => 'sendMessage',
                            'chat_id' => $chatId,
                            'text' => $translator->trans('thank you', [], null, $locale).'!',
                            'reply_markup' => [
                                'keyboard' => [
                                    [
                                        [
                                            'text' => $translator->trans('worksheet', [], null, $locale),
                                        ],
                                        [
                                            'text' => $translator->trans('my borrowings', [], null, $locale),
                                        ],
                                        [
                                            'text' => $translator->trans('mfo list', [], null, $locale),
                                        ],
                                        [
                                            'text' => $translator->trans('settings', [], null, $locale),
                                        ],
                                    ]
                                ],
                                'one_time_keyboard' => true,
                                'resize_keyboard' => true,
                            ],
                        ]);
                    }
                    
                    return new JsonResponse([
                        'method' => 'sendMessage',
                        'chat_id' => $chatId,
                        'text' => $currentField->getLabel()
                            ."\r\n".$currentField->getDescription()
                            ."\r\n({$translator->trans('for example', [], null, $locale)}, {$currentField->getSample()})",
                        'reply_markup' => [
                            'keyboard' => [
                                [
                                    [
                                        'text' => $translator->trans('cancel', [], null, $locale),
                                    ],
                                ]
                            ],
                            'one_time_keyboard' => true,
                            'resize_keyboard' => true,
                        ],
                    ]);
                }
            }
        }
        
        return [];
    }
    
    public function askQuestion()
    {
        
    }
    
    
}
