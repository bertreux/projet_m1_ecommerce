<?php

namespace App\Tests\e2e\Mail;

use Symfony\Component\Panther\PantherTestCase;

// Il faut uniquement modifié remplir_formulaire(), edit_formulaire() et la propriété privé page

class CRUDMailTest extends PantherTestCase
{
    private $firstRowId;
    private $client;
    private $page = 'mail';

    public function testCRUD(): void
    {
        $this->client = static::createPantherClient();
        $crawler = $this->client->request('GET', "/{$this->page}/");
        $firstTh = $crawler->filter('th')->first();
        $firstTh->click();

        $this->firstRowId = $crawler->filter('tr')->eq(1)->filter('td')->eq(1)->getText();
        $this->assertNotEmpty($this->firstRowId, "On ,'obitent pas l'id de la premiere valeur si il existe une valeur");


        $crawler = $this->client->request('GET', "/{$this->page}/{$this->firstRowId}");
        $showId = $crawler->filter('tr')->eq(0)->filter('td')->eq(0)->getText();
        $this->takeScreenshot('show.png');
        $this->assertEquals($this->firstRowId, $showId, "L'id de la page show de l'élément n'est pas le meme que celui du tableau");
    }

    private function takeScreenshot(string $filename): void
    {
        $screenshot = $this->client->takeScreenshot();
        $dir = ucfirst($this->page);
        file_put_contents("tests/e2e/{$dir}/{$filename}", $screenshot);
    }
}
