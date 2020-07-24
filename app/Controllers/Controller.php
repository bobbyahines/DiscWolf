<?php
declare(strict_types=1);

namespace DiscWolf\Controllers;


use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Controller
{
    protected Environment $twig;

    /**
     * Controller constructor.
     *
     * Establishes the loading environment and template handlers for the controllers.
     */
    public function __construct()
    {
        $loader = new FilesystemLoader(dirname(__DIR__) . '/Views');
        $this->twig = new Environment($loader);
    }

    /**
     * Just a test function.
     * @todo Remove when vestigial.
     */
    public function test(): void
    {
        echo 'Test Controller function has been successfully accessed.';
    }
}