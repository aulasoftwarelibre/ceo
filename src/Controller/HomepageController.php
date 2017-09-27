<?php
/**
 * This file is part of the ceo.
 *
 * (c) Sergio Gómez <sergio@uco.es>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class HomepageController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function __invoke(UrlGeneratorInterface $generator)
    {
        return $this->render("frontend/homepage.html.twig", [
        ]);
    }
}
