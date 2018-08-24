<?php

declare(strict_types=1);

/*
 * This file is part of the `idea` project.
 *
 * (c) Aula de Software Libre de la UCO <aulasoftwarelibre@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Services\Telegram\Command;

use App\Entity\TelegramChat;
use App\Messenger\TelegramChat\RegisterUserChatCommand;
use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected $name = 'start';
    /**
     * {@inheritdoc}
     */
    protected $description = 'Inicia la interacción con el bot.';

    /**
     * {@inheritdoc}
     */
    public function handle($arguments): void
    {
        if (empty($arguments)) {
            $this->replyWithMessage([
                'text' => 'Este bot aún no interacciona con usuarios.',
            ]);

            return;
        }

        $message = $this->getUpdate()->getMessage();

        $valid = $this->telegram->getMessageBus()->dispatch(
            new RegisterUserChatCommand(
                $message,
                $arguments
            )
        );

        if (!$valid instanceof TelegramChat) {
            $this->replyWithMessage([
                'text' => 'El token no es válido.',
            ]);

            return;
        }

        $this->replyWithMessage([
            'text' => sprintf(
                "Enhorabuena te has registrado con el usuario %s.\nEnvía /stop para desconectar",
                $valid->getUser()->getUsername()
            ),
        ]);

        $this->triggerCommand('notify');
    }
}
