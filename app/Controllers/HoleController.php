<?php
declare(strict_types=1);

namespace DiscWolf\Controllers;


class HoleController extends Controller
{

    public function index(): void
    {
        $args = func_get_arg(1);
        $params = ['data' => $args];

        $template = $this->twig->load('/Hole.twig');

        echo $template->render($params);
    }
}