<?php

/*
 * This file is part of the ceo project.
 *
 * (c) Aula de Software Libre de la UCO <aulasoftwarelibre@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use App\Entity\Participation;
use App\Entity\User;
use App\Form\Type\ProfileType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/profile")
 */
class ProfileController extends Controller
{
    /**
     * @var UserRepository
     */
    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/edit", name="profile_edit")
     * @Method({"GET", "POST"})
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function editAction(Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(ProfileType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager = $this->get('doctrine.orm.default_entity_manager');

            $manager->persist($user);
            $manager->flush();

            $this->addFlash('positive', 'Su perfil ha sido actualizado');

            return $this->redirectToRoute('homepage');
        }

        return $this->render('/frontend/profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="profile_show")
     * @Method("GET")
     * @Security("is_granted('IS_AUTHENTICATED_FULLY')")
     */
    public function showAction(Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();
        /** @var User $profile */
        $profile = $this->repository->getProfile($user->getId());

        $academicYears = [];
        $activities = 0;
        $hours = 0;

        $profile->getParticipations()->map(function (Participation $participation) use (&$academicYears, &$activities, &$hours) {
            $academicYear = $participation->getActivity()->getAcademicYear();
            $academicYears[$academicYear][] = $participation;

            ++$activities;
            $hours += $participation->getDuration();
        });

        return $this->render('/frontend/profile/show.html.twig', [
            'profile' => $profile,
            'academic_years' => $academicYears,
            'activities' => $activities,
            'hours' => $hours,
        ]);
    }

    public function showCard()
    {
        /** @var User $user */
        $user = $this->getUser();
        $profile = $this->repository->getProfile($user->getId());

        return $this->render('/frontend/profile/_card.html.twig', [
            'profile' => $profile,
        ]);
    }
}
