<?php
/**
 * Created by PhpStorm.
 * User: agr
 * Date: 14.02.2017
 * Time: 11:22
 */

namespace UW\Modules\Notify\Providers;


use UW\Facades\MainFacade;
use UW\Logger;
use UW\Modules\Notify\Controller;
use UW\Modules\Notify\Message;

/**
 * Провайдер модуля уведомлений для отправки сообщений по электронной почте
 *
 * Class EmailProvider
 * @package UW\Modules\Notify\Providers
 */
class EmailProvider extends Provider
{
    /**
     * Код провайдера
     *
     * @var string
     */
    protected $code = 'Email';
    protected $name = 'E-mail уведомления';

    /**
     * Список сообщений для отправки
     *
     * @var Message[]
     */
    protected $messages;

    /**
     * Преобразовать относительные УРЛы в абсолютные (с доменным именем)
     *
     * @param $string
     * @return mixed
     */
    private function convertRelativeURLs($string)
    {
        $mainFacade = new MainFacade();

        $domain = $mainFacade->getDomain();
        $protocol = $mainFacade->getProtocol();
        $pattern = '/href\s*=\s*"[^http](.+?)"/i';
        $replacement = sprintf('href="%s://%s/${1}"', $protocol, $domain);

        if($result = preg_replace($pattern, $replacement, $string)){
            return $result;
        }

        return $string;
    }


    /**
     * Запустить сбор и отправку уведомлений
     *
     * Используется для работы Битрикс-агента
     */
    public static function run()
    {
        // \UW\Modules\Notify\NotifyAgents::runProvider("Email");
        $provider = new EmailProvider();

        //трассировка получения сообщений для провайдера
        Logger::startTrace();
        Logger::trace('Начало работы провайдра Notice');

        $provider
            ->setTimeProcessMessaging(new \DateTime())
            ->setMessages(
                (new Controller())->getMessages(
                    $provider->getCode(),
                    $provider->getLastQueryDate()
                )
            )
            ->send();

        Logger::trace('Окончание работы провайдра Notice');
        Logger::endTrace();

        return '\\'.__METHOD__.'();';
    }


    /**
     * Установить сообщения для отправки
     * @param Message[] $messages
     *
     * @return $this
     */
    public function setMessages($messages)
    {
        $this->messages = $messages;

        return $this;
    }



    /**
     * Отправить сообщения
     */
    public function send()
    {
        foreach ($this->messages as $message){

            //если в сообщении нет ни одного получателя, то пропускаем его
            if(empty($message->getConsumers())){
                continue;
            }

            $consumers = implode(', ', $message->getConsumers());
            $mailFields = [
                'TITLE' => $message->getTemplate()->getTitle(),
                'BODY' => $this->convertRelativeURLs($message->getTemplate()->getBody()),
                'CONSUMERS' => $consumers
            ];

            $evt = new \CEvent();
            $evt->SendImmediate('NOTIFY_EMAIL_PROVIDER_SEND', 's1', $mailFields, 'N');
        }

        $this->setLastQueryDate($this->timeProcessMessaging);
    }
}