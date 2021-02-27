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

namespace App\MessageHandler\Idea;

use App\Message\Idea\OpenIdeaCommand;
use App\Repository\IdeaRepository;

class OpenIdeaCommandHandler
{
    private IdeaRepository $ideaRepository;

    public function __construct(IdeaRepository $ideaRepository)
    {
        $this->ideaRepository   = $ideaRepository;
    }

    public function __invoke(OpenIdeaCommand $command): void
    {
        $idea = $command->getIdea();
        $idea->setClosed(false);

        $this->ideaRepository->add($idea);
    }
}
