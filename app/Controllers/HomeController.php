<?php declare(strict_types=1);


namespace DiscWolf\Controllers;


use DiscWolf\DataTransferObjects\Game;

final class HomeController extends Controller
{
    /**
     * HOME LANDING PAGE
     *
     * This method renders the landing home page; the very first the users see.
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index(): void
    {
        $_SESSION['game'] = [];

        $template = $this->twig->load('/Home.twig');

        echo $template->render();
    }

    public function play(): void
    {
        $template = $this->twig->load('/Play.twig');

        echo $template->render();
    }

    public function rules(): void
    {
        $template = $this->twig->load('/Rules.twig');

        echo $template->render();
    }

    public function about(): void
    {
        $template = $this->twig->load('/About.twig');

        echo $template->render();
    }
}