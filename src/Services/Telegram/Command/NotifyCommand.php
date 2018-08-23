<?php

/*
 * This file is part of the `idea` project.
 *
 * (c) Aula de Software Libre de la UCO <aulasoftwarelibre@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Services\Telegram\Command;

use App\Messenger\TelegramChat\GetTelegramChatNotificationsQuery;
use Telegram\Bot\Commands\Command;

class NotifyCommand extends Command
{
    protected $name = 'notify';
    protected $description = 'Informa de las notificaciones activas';

    public function handle($arguments)
    {
        $message = $this->getUpdate()->getMessage();

        try {
            $this->telegram->getMessageBus()->dispatch(
                new GetTelegramChatNotificationsQuery(
                    (string) $message->getChat()->getId()
                )
            );
        } catch (\Exception $e) {
            $this->replyWithMessage([
                'text' => $e->getMessage(),
            ]);
        }
    }
}
