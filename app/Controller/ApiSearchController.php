<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Vyse\Framework\Symfony\Responder\ViewResponder;

class ApiSearchController
{
    #[Route('/api/search', name: 'api_search', methods: ['GET'])]
    public function search(
        Request $request,
        ViewResponder $view,
    ): Response {
        $query = strtolower($request->query->get('q', ''));

        // If empty, pass an empty array to trigger the template's "No users found." state
        if (empty($query)) {
            return $view('components/_search_results', [
                'results' => [],
            ]);
        }

        // Mock Database
        $users = [
            ['name' => 'Alice Smith', 'email' => 'alice@example.com'],
            ['name' => 'Bob Jones', 'email' => 'bob@example.com'],
            ['name' => 'Charlie Brown', 'email' => 'charlie@example.com'],
            ['name' => 'David Miller', 'email' => 'david@example.com'],
            ['name' => 'Eve Davis', 'email' => 'eve@example.com'],
            ['name' => 'Frank Wilson', 'email' => 'frank@example.com'],
        ];

        // Filter results based on the query
        $results = array_filter($users, fn($user) => str_contains(strtolower($user['name']), $query)
                || str_contains(strtolower($user['email']), $query));

        return $view('components/_search_results', [
            'results' => $results,
        ]);
    }
}
