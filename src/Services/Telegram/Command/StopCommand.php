<?php

/*
 * This file is part of the ceo project.
 *
 * (c) Aula de Software Libre de la UCO <aulasoftwarelibre@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Services\Telegram\Command;

use App\Command\UnregisterUserChatCommand;
use Telegram\Bot\Commands\Command;

class StopCommand extends Command
{
    protected $name = 'stop';
    protected $description = 'Borra la conexión con el bot.';

    public function handle($arguments)
    {
        $this->replyWithMessage([
            'text' => 'Se ha desactivado el chat. Para volver a registrarte acude a la web de actividades.',
        ]);

        $message = $this->getUpdate()->getMessage();
        $chatId = (string) $message->getChat()->getId();

        $this->telegram->getTacticianBus()->handle(
            new UnregisterUserChatCommand(
                $chatId
            )
        );
    }
}
