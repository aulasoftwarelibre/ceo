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

namespace App\Messenger\Vote;

use App\Entity\Vote;
use App\Exception\NoMoreSeatsLeftException;
use App\MessageBus\CommandHandlerInterface;
use App\Repository\VoteRepository;

class AddVoteHandler implements CommandHandlerInterface
{
    /**
     * @var VoteRepository
     */
    private $repository;

    public function __construct(VoteRepository $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(AddVoteCommand $command): void
    {
        $idea = $command->getIdea();
        $user = $command->getUser();

        /** @var Vote $vote */
        $vote = $this->repository->findOneBy([
            'user' => $user,
            'idea' => $idea,
        ]);

        if ($vote instanceof Vote) {
            return;
        }

        $count = $idea->getVotes()->count();
        $numSeats = $idea->getNumSeats();

        if ($numSeats > 0 && $count >= $numSeats) {
            throw new NoMoreSeatsLeftException();
        }

        $vote = new Vote();
        $vote->setIdea($idea);
        $vote->setUser($user);

        $this->repository->add($vote);
    }
}
