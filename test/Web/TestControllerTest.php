<?php

declare(strict_types=1);

namespace Symfony\Vyse\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TestControllerTest extends WebTestCase
{
    public function testMyRouteLoadsCorrectly(): void
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/test');

        self::assertResponseIsSuccessful();

        self::assertSelectorTextContains('h1', 'Hello TestController! ✅');
    }
}
