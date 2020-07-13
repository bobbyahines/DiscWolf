<?php
declare(strict_types=1);

namespace DiscWolf\Controllers;


use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class Controller
{
    protected Environment $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader(dirname(__DIR__) . '/Views');
        $this->twig = new Environment($loader);
    }

    public function test(): void
    {
        echo 'Test Controller function has been successfully accessed.';
    }
}