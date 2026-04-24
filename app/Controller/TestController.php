<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Foo;
use App\Repository\FooRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Vyse\Framework\Symfony\Responder\ViewResponder;

class TestController
{
    #[Route('/test', name: 'app_test')]
    public function index(
        FooRepository $foos,
        ViewResponder $view,
    ): Response {
        $newFoo = new Foo('Visit at ' . date('Y-m-d H:i:s'));
        $foos->save($newFoo);
        $totalCount = $foos->countAll();

        return $view('test', [
            'controller_name' => 'TestController',
            'total_foos' => $totalCount,
            'latest_id' => $newFoo->getId(),
        ]);
    }
}
