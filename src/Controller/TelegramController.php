<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Worksheet;
use App\Service\EntityServices\FieldManager;
use App\Service\EntityServices\WorksheetManager;
use App\Service\EntityServices\UserManager;
use App\Service\OpenBotHandler;
use GuzzleHttp\Client;
use LogicException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

class TelegramController extends Controller
{
    /**
     * @Route("/telegram", name="telegram")
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     * @throws LogicException
     */
    public function telegram(
            FieldManager $fieldManager,
            OpenBotHandler $openBotHandler,
            Request $request,
            TranslatorInterface $translator,
            UserManager $userManager,
            WorksheetManager $worksheetManager
    ): JsonResponse {
        $result = json_decode($request->getContent(), true);

        $client = new Client(['base_uri' => $this->getParameter('swagger_protocol')
                                .$this->getParameter('swagger_hostname')]);

        if (!empty($result)) {
            $text = $result["message"]["text"]; //Текст сообщения
            $chatId = $result["message"]["chat"]["id"]; //Уникальный идентификатор пользователя
            $telegramId = $result["message"]["from"]["id"]; //Id пользователя в телеграме
    //        // TODO: Разобраться, как читать присылаемый боту контакт пользователя
    //        $contact = $result["message"]["contact"];
    //                ? file_put_contents($this->getParameter('kernel.project_dir').'/var/log/telegram.log', '--'.json_encode($result).PHP_EOL, FILE_APPEND)
    //                : file_put_contents($this->getParameter('kernel.project_dir').'/var/log/telegram.log', '--нет контактов'.PHP_EOL, FILE_APPEND);
        } else {
            $text = 'вар ыар ырычу'; //Текст сообщения
            $chatId = 335741432; //Уникальный идентификатор пользователя
            $telegramId = 335741432;
        }

        file_put_contents($this->getParameter('kernel.project_dir').'/var/log/telegram.log', 'вход --'.PHP_EOL, FILE_APPEND);
        file_put_contents($this->getParameter('kernel.project_dir').'/var/log/telegram.log', json_encode($result).PHP_EOL, FILE_APPEND);
        file_put_contents($this->getParameter('kernel.project_dir').'/var/log/telegram.log', 'далее функционал -->'.PHP_EOL, FILE_APPEND);

//        if ($result['message'] == 'sfsef') {
//        if (!empty($result['data'])) {
//            return new JsonResponse([
//                'method' => 'sendMessage',
//                'chat_id' => $chatId,
//                'text' => 'Получилось 8)',
//            ]);
//        }
        
        switch ($text) :
            case '/inline':
                file_put_contents($this->getParameter('kernel.project_dir').'/var/log/telegram.log', '/inline -- выводим встроенную клавиатуру'.PHP_EOL, FILE_APPEND);
                
                $response = new JsonResponse([
                    'method' => 'sendMessage',
                    'chat_id' => $chatId,
                    'text' => 'Привет) Я встроенная клава',
                    'reply_markup' => [
                        'inline_keyboard' => [
                            [
                                [
//                                    'text' => $translator->trans('бла', [], null, $locale),
                                    'text' => 'Колбэк',
                                    'callback_data' => 'test',
                                ],
                                [
//                                    'text' => $translator->trans('бла бла', [], null, $locale),
                                    'text' => 'бла бла ubuntu)',
                                    'url' => 'http://forum.ubuntu.ru/',
                                ],
                            ]
                        ],
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true,
                    ],
                ]);
                break;
            case '/start':
                if ($userManager->checkIsRegisterByTelegramId($telegramId)) {
                    $user = $userManager->getUserByTelegramId($telegramId);

                    $locale = $user->getLocale();

                    file_put_contents($this->getParameter('kernel.project_dir').'/var/log/telegram.log', 'старт -- если пользователь зареган'.PHP_EOL, FILE_APPEND);

                    if (empty($locale)) {
                        $response = new JsonResponse([
                            'method' => 'sendMessage',
                            'chat_id' => $chatId,
                            'text' => $translator->trans('choose locale', [], null, 'ru'),
                            'reply_markup' => [
                                'keyboard' => [
                                    [
                                        [
                                            'text' => 'ru',
                                            'callback_data' => 'ru',
                                        ],
                                        [
                                            'text' => 'kz',
                                            'callback_data' => 'kz',
                                        ],
                                    ]
                                ],
                                'resize_keyboard' => true,
                                'one_time_keyboard' => true,
                            ],
                        ]);
                        break;
                    }

                    if (empty($user->getCountry())) {
                        $response = new JsonResponse([
                            'method' => 'sendMessage',
                            'chat_id' => $chatId,
                            'text' => $translator->trans('choose country', [], null, $locale),
                            'reply_markup' => [
                                'keyboard' => [
                                    [
                                        [
                                            'text' => $translator->trans('russia', [], null, $locale),
                                        ],
                                        [
                                            'text' => $translator->trans('kazakhstan', [], null, $locale),
                                        ],
                                    ]
                                ],
                                'resize_keyboard' => true,
                                'one_time_keyboard' => true,
                            ],
                        ]);
                        break;
                    }

//  TODO: раскомментировать, когда будет написана обработка присылаемого контакта
//                    if (empty($user->getPhone())) {
//                        file_put_contents($this->getParameter('kernel.project_dir').'/var/log/telegram.log', 'старт -- нет телефона -- если пользователь зареган'.PHP_EOL, FILE_APPEND);
//
//                        $response = new JsonResponse([
//                            'method' => 'sendMessage',
//                            'chat_id' => $chatId,
//                            'text' => $translator->trans('please, enter your mobile number', [], null, $locale),
//                            'reply_markup' => [
//                                'keyboard' => [
//                                    [
//                                        [
//                                            'text' => $translator->trans('send number', [], null, $locale),
//                                            'request_contact' => true,
//                                        ],
//                                    ]
//                                ],
//                                'one_time_keyboard' => true,
//                                'resize_keyboard' => true,
//                            ],
//                        ]);
//                        break;
//                    }

                    $userWorksheets = $user->getWorksheets();

                    foreach ($userWorksheets as $userWorksheet) {
                        if (Worksheet::STATUS_OPEN == $userWorksheet->getStatus()) {
                            $response = new JsonResponse([
                                'method' => 'sendMessage',
                                'chat_id' => $chatId,
                                'text' => $translator->trans('please, continue filling in the application form', [], null, $locale),
                                'reply_markup' => [
                                    'keyboard' => [
                                        [
                                            [
                                                'text' => $translator->trans('worksheet', [], null, $locale),
                                            ],
                                            [
                                                // TODO: Обработать удаление анкеты при отмене
                                                'text' => $translator->trans('cancel', [], null, $locale),
                                            ],
                                        ]
                                    ],
                                    'one_time_keyboard' => true,
                                    'resize_keyboard' => true,
                                ],
                            ]);
                            break;
                        }
                    }

                    $response = new JsonResponse([
                        'method' => 'sendMessage',
                        'chat_id' => $chatId,
                        'text' => $translator->trans('menu', [], null, $locale),
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
                    break;
                } else {
                    file_put_contents($this->getParameter('kernel.project_dir').'/var/log/telegram.log', 'старт -- если пользователь не зареган'.PHP_EOL, FILE_APPEND);

                    $response = new JsonResponse([
                        'method' => 'sendMessage',
                        'chat_id' => $chatId,
                        'text' => $translator->trans('choose locale', [], null, 'ru'),
                        'reply_markup' => [
                            'keyboard' => [
                                [
                                    [
                                        'text' => 'ru',
                                        'callback_data' => 'ru',
                                    ],
                                    [
                                        'text' => 'kz',
                                        'callback_data' => 'kz',
                                    ],
                                ]
                            ],
                            'resize_keyboard' => true,
                            'one_time_keyboard' => true,
                        ],
                    ]);
                }
                break;
            case 'ru':
            case 'kz':
                if (!$userManager->checkIsRegisterByTelegramId($telegramId)) {
                    $userManager->create($telegramId);

                    file_put_contents($this->getParameter('kernel.project_dir').'/var/log/telegram.log', 'ru/kz -- создали нового пользователя'.PHP_EOL, FILE_APPEND);
                }

                $user = $userManager->setLocale($telegramId, $text);

                file_put_contents($this->getParameter('kernel.project_dir').'/var/log/telegram.log', 'ru/kz -- изменили язык'.PHP_EOL, FILE_APPEND);

                $locale = $user->getLocale();

                $response = new JsonResponse([
                    'method' => 'sendMessage',
                    'chat_id' => $chatId,
                    'text' => $translator->trans('choose country', [], null, $locale),
                    'reply_markup' => [
                        'keyboard' => [
                            [
                                [
                                    'text' => $translator->trans('russia', [], null, $locale),
                                ],
                                [
                                    'text' => $translator->trans('kazakhstan', [], null, $locale),
                                ],
                            ]
                        ],
                        'resize_keyboard' => true,
                        'one_time_keyboard' => true,
                    ],
                ]);
                break;
            case 'Россия':
            case 'Казахстан':
            case 'Ресей':
            case 'Қазақстан':
                if (!$userManager->checkIsRegisterByTelegramId($telegramId)) {
                    $userManager->create($telegramId);

                    file_put_contents($this->getParameter('kernel.project_dir').'/var/log/telegram.log', 'Россия/Казахстан -- создали нового пользователя'.PHP_EOL, FILE_APPEND);
                }

                if ($text == 'Россия' || $text == 'Ресей') {
                    $user = $userManager->setCountry($telegramId, 'ru');
                } elseif ($text == 'Казахстан' || $text == 'Қазақстан') {
                    $user = $userManager->setCountry($telegramId, 'kz');
                }

                file_put_contents($this->getParameter('kernel.project_dir').'/var/log/telegram.log', 'Россия/Казахстан -- изменили страну'.PHP_EOL, FILE_APPEND);

                $locale = $user->getLocale() ?? 'ru';

//  TODO: раскомментировать, когда будет написана обработка присылаемого контакта
//                if (empty($user->getPhone())) {
//                    file_put_contents($this->getParameter('kernel.project_dir').'/var/log/telegram.log', 'старт -- нет телефона -- если пользователь регится или в настройках'.PHP_EOL, FILE_APPEND);
//
//                    $response = new JsonResponse([
//                        'method' => 'sendMessage',
//                        'chat_id' => $chatId,
//                        'text' => $translator->trans('please, enter your mobile number', [], null, $locale),
//                        'reply_markup' => [
//                            'keyboard' => [
//                                [
//                                    [
//                                        'text' => $translator->trans('send number', [], null, $locale),
//                                        'request_contact' => true,
//                                    ],
//                                ]
//                            ],
//                            'one_time_keyboard' => true,
//                            'resize_keyboard' => true,
//                        ],
//                    ]);
//                    break;
//                }

                $response = new JsonResponse([
                    'method' => 'sendMessage',
                    'chat_id' => $chatId,
                    'text' => $translator->trans('menu', [], null, $locale),
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
                break;
            case 'Анкета':
            case 'Cауалнама':
                if ($userManager->checkIsRegisterByTelegramId($telegramId)) {
                    $user = $userManager->getUserByTelegramId($telegramId);
                    $locale = $user->getLocale();
                    $userCountry = $user->getCountry();

                    $worksheetFields = json_decode(
                        $client->get("/worksheetsample/$userCountry/questions")
                        ->getBody()
                        ->getContents()
                    );

                    $userWorksheets = $user->getWorksheets();

                    // Если есть открытая анкета, ищем следующий вопрос в ней
                    foreach ($userWorksheets as $userWorksheet) {
                        if (Worksheet::STATUS_OPEN == $userWorksheet->getStatus()) {
                            $currentField = $fieldManager->getCurrentField($userWorksheet);
                            $response = new JsonResponse([
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
                            break;
                        }
                    }

                    // Если нет открытой анкеты, создаём новую
                    $currentField = $fieldManager->getCurrentField($worksheetManager->create($telegramId, $worksheetFields));
                                    
                    $response = new JsonResponse([
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
                    break;
                } else {
                    $response = new JsonResponse([
                        'method' => 'sendMessage',
                        'chat_id' => $chatId,
                        'text' => $translator->trans('choose locale', [], null, 'ru'),
                    ]);
                }
                break;
            case 'Настройки':
            case 'Параметрлері':
                if ($userManager->checkIsRegisterByTelegramId($telegramId)) {
                    $userLocale = $userManager->getUserByTelegramId($telegramId)->getLocale();
                }

                $locale = $userLocale ?? 'ru';

                $response = new JsonResponse([
                        'method' => 'sendMessage',
                        'chat_id' => $chatId,
                        'text' => $translator->trans('choose locale', [], null, $locale),
                        'reply_markup' => [
                            'keyboard' => [
                                [
                                    [
                                        'text' => 'ru',
                                        'callback_data' => 'ru',
                                    ],
                                    [
                                        'text' => 'kz',
                                        'callback_data' => 'kz',
                                    ],
                                ]
                            ],
                            'resize_keyboard' => true,
                            'one_time_keyboard' => true,
                        ],
                    ]);
                break;
            // TODO: Разобраться, как читать присылаемый боту контакт пользователя
            case 'contact':
            case 'Отправить номер':
            case 'Нөмірді жіберу':
                $response = new JsonResponse([
                    'method' => 'sendMessage',
                    'chat_id' => $chatId,
                    'text' => 'номер:',
                ]);
                break;
            case 'Отменить':
            case 'Күшін жою':
                if ($userManager->checkIsRegisterByTelegramId($telegramId)) {
                    $userLocale = $userManager->getUserByTelegramId($telegramId)->getLocale();
                }

                if ($openWorksheet = $userManager->getOpenWorksheet($telegramId)) {
                    $worksheetManager->setStatusError($openWorksheet);
                    $worksheetManager->deleteFields($openWorksheet);
                }
                
                $locale = $userLocale ?? 'ru';
                
                $response = new JsonResponse([
                    'method' => 'sendMessage',
                    'chat_id' => $chatId,
                    'text' => $translator->trans('menu', [], null, $locale),
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
                break;
            case '/close':
                file_put_contents($this->getParameter('kernel.project_dir').'/var/log/telegram.log', 'клоз --'.PHP_EOL, FILE_APPEND);

                $response = new JsonResponse([
                    'method' => 'sendMessage',
                    'chat_id' => $chatId,
                    'text' => 'гудбай)',
                    'reply_markup' => [
                        'keyboard' => [],
                        'hide_keyboard' => true,
                    ],
                ]);
                break;
            case '/help':
                file_put_contents($this->getParameter('kernel.project_dir').'/var/log/telegram.log', 'хелп --'.PHP_EOL, FILE_APPEND);

                $response = new JsonResponse([
                    'method' => 'sendMessage',
                    'chat_id' => $chatId,
                    'text' => 'это не поможет',
                ]);
                break;
            default:
                file_put_contents($this->getParameter('kernel.project_dir').'/var/log/telegram.log', 'default'.PHP_EOL, FILE_APPEND);
                
                if ($userManager->checkIsRegisterByTelegramId($telegramId) 
                        && $openWorksheet = $userManager->getOpenWorksheet($telegramId)) {
                    $currentField = $fieldManager->getCurrentField($openWorksheet);

                    $user = $userManager->getUserByTelegramId($telegramId);
                    
                    $locale = $user->getLocale() ?? 'ru';
                    
                    if (empty($currentField)) {
                        $worksheetResult = $worksheetManager->sendToMfo($openWorksheet);

                        if (empty($worksheetResult['error'])) {
                            $response = new JsonResponse([
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
                            break;
                        } elseif ('validation_error' == $worksheetResult['error']) {
                            $response = new JsonResponse([
                                'method' => 'sendMessage',
                                'chat_id' => $chatId,
                                'text' => $translator->trans('the data is incorrect', [], null, $locale)
                                    ."\r\n".$currentField->getLabel()
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
                            break;
                        } elseif ('fatal_error' == $worksheetResult['error']) {
                            $response = new JsonResponse([
                                'method' => 'sendMessage',
                                'chat_id' => $chatId,
                                'text' => $translator->trans('error', [], null, $locale),
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
                            break;
                        }
                    }
                    
                    if (!$fieldManager->isValid($currentField, $text)) {
                        $response = new JsonResponse([
                            'method' => 'sendMessage',
                            'chat_id' => $chatId,
                            'text' => $translator->trans('the data is incorrect', [], null, $locale)
                                ."\r\n".$currentField->getLabel()
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
                        break;
                    }
                    
                    $fieldManager->setValue($currentField, $text);
                    
                    $newCurrentField = $fieldManager->getCurrentField($openWorksheet);
                    
                    if (empty($newCurrentField)) {
                        $response = new JsonResponse([
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
                        break;
                    } else {
                        $response = new JsonResponse([
                            'method' => 'sendMessage',
                            'chat_id' => $chatId,
                            'text' => $newCurrentField->getLabel()
                                ."\r\n".$newCurrentField->getDescription()
                                ."\r\n({$translator->trans('for example', [], null, $locale)}, {$newCurrentField->getSample()})",
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
                        break;
                    }
                } else {
                    $response = new JsonResponse([
                        'method' => 'sendMessage',
                        'chat_id' => $chatId,
                        'text' => $text,
                    ]);
                    break;
                }
        endswitch;

        return $response;
    }
}