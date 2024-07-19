<?php

namespace App\Tests\fonctionnel;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AllUrlRunningTest extends WebTestCase
{
    public function testAllUrls(): void
    {
        $urls_to_not_test = [
            '/utilisateur/fetch/supprimer'
        ];
        if(getenv('PANTHER_URL') == 'localhost') {
            $client = static::createClient();
        } else {
            $client = static::createClient([], [
                'base_uri' => getenv('PANTHER_URL'),
            ]);
        }


        $router = $this->getContainer()->get('router');
        $routes = $router->getRouteCollection();

        foreach ($routes as $routeName => $route) {
            $path = $route->getPath();
            if (!in_array($path, $urls_to_not_test)) {
                $path = preg_replace('/\{[^\}]+\}/', '1', $path);
                $client->request('GET', $path);
                $response = $client->getResponse();

                if ($response->isRedirection()) {
                    $crawler = $client->followRedirect();
                    $response = $client->getResponse();
                }

                $this->assertTrue(
                    $response->isSuccessful(),
                    sprintf("La route %s n'a pas retourné une réponse 200 OK. Réponse actuelle: %s", $path, $response->getStatusCode())
                );
            }
        }
    }

}
