<?php

namespace App\Tests\e2e\Produit;

use Facebook\WebDriver\WebDriverKeys;
use Symfony\Component\Panther\PantherTestCase;

// Il faut uniquement modifié remplir_formulaire(), edit_formulaire() et la propriété privé page

class CRUDProduitTest extends PantherTestCase
{
    private $lastRowId;
    private $firstRowId;
    private $firstRowIdAfterCreate;
    private $client;
    private $page = 'produit';

    public function testCRUD(): void
    {
        if(getenv('PANTHER_URL') == 'localhost') {
            $this->client = static::createPantherClient();
        } else {
            $this->client = static::createPantherClient(
                ['external_base_uri' => getenv('PANTHER_URL')]
            );
        }
        $this->create();
        $this->show();
        $this->edit();
        $this->delete();
    }

    private function remplir_formulaire($crawler) {
        $crawler->filter('#produit_nom')->sendKeys('test');
        $crawler->filter('#produit_prix')->sendKeys('1');
        $crawler->filter('#produit_stock')->sendKeys('10');
        $crawler->filter('#produit_description')->sendKeys('test');
        $this->selectOption('produit_arriver_month', '2');
        $this->selectOption('produit_arriver_day', '2');
        $this->selectOption('produit_arriver_year', '2023');
        $crawler->filter('#produit_prioriter')->sendKeys('1');
        $this->selectOption('produit_categorie', '1');
        $this->selectOption('produit_commande', '1');
    }

    private function edit_formulaire($crawler) {
        $crawler->filter('#produit_nom')->sendKeys('test');
    }

    private function create(): void
    {
        $crawler = $this->client->request('GET', "/{$this->page}/");
        $firstTh = $crawler->filter('th')->first();
        $firstTh->click();

        $this->firstRowId = $crawler->filter('tr')->eq(1)->filter('td')->eq(1)->getText();
        $this->assertNotEmpty($this->firstRowId, "On ,'obitent pas l'id de la premiere valeur si il existe une valeur");

        $this->takeScreenshot('first_array.png');

        $crawler = $this->client->request('GET', "/{$this->page}/new");
        $this->takeScreenshot('form_vide.png');

        $this->remplir_formulaire($crawler);

        $this->takeScreenshot('form_plein.png');

        $crawler->filter('button.btn-success')->click();

        $crawler = $this->client->request('GET', "/{$this->page}/");
        $firstTh = $crawler->filter('th')->first();
        $firstTh->click();

        $this->takeScreenshot('tableau_apres_ajout.png');

        $this->lastRowId = $crawler->filter('tr')->eq(1)->filter('td')->eq(1)->getText();
        $this->assertNotEquals($this->firstRowId, $this->lastRowId, "La valeur de l'id de la premiere valeur du tableau sont les mêmes avant et après la création : {$this->firstRowId}");
    }

    private function show(): void
    {
        $crawler = $this->client->request('GET', "/{$this->page}/{$this->lastRowId}");
        $showId = $crawler->filter('tr')->eq(0)->filter('td')->eq(0)->getText();
        $this->takeScreenshot('show.png');
        $this->assertEquals($this->lastRowId, $showId, "L'id de la page show de l'élément n'est pas le meme que celui du tableau");
    }

    private function edit(): void
    {
        $crawler = $this->client->request('GET', "/{$this->page}/{$this->lastRowId}/edit");
        $this->takeScreenshot('before_edit.png');

        $this->edit_formulaire($crawler);

        $crawler->filter('button.btn-success')->click();

        $this->takeScreenshot('after_edit.png');

        $crawler = $this->client->request('GET', "/{$this->page}/");
        $firstTh = $crawler->filter('th')->first();
        $firstTh->click();

        $qte = $crawler->filter('tr')->eq(1)->filter('td')->eq(2)->getText();
        $this->takeScreenshot('tableau_with_edit.png');
        $this->assertNotEquals('test', $qte, "La valeur avant et après la modification sont les memes");
    }

    private function delete(): void
    {
        $crawler = $this->client->request('GET', "/{$this->page}/");
        $firstTh = $crawler->filter('th')->first();
        $firstTh->click();

        $deleteButton = $crawler->filter('tr')->eq(1)->filter('td')->eq(0)->filter('input');
        $deleteButton->click();

        $this->client->executeScript('window.scrollTo(0, document.body.scrollHeight);');
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_DOWN);

        $this->takeScreenshot('tableau_clique_checkbox.png');

        $this->client->waitFor('#btn-delete-row:enabled');
        $deleteRowButton = $crawler->filter('#btn-delete-row');
        $deleteRowButton->click();

        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);
        $this->client->getKeyboard()->pressKey(WebDriverKeys::PAGE_UP);

        $crawler = $this->client->request('GET', "/{$this->page}/");
        $firstTh = $crawler->filter('th')->first();
        $firstTh->click();

        $this->takeScreenshot('tableau_apres_sup.png');

        $this->firstRowIdAfterCreate = $crawler->filter('tr')->eq(1)->filter('td')->eq(1)->getText();
        $this->assertEquals($this->firstRowId, $this->firstRowIdAfterCreate, "Après les tests de suppressions on ne retoruve pas l'id avant la création, on doit avoir : {$this->firstRowId} et on a : {$this->firstRowIdAfterCreate}");
    }

    private function selectOption(string $id_select, string $value): void
    {
        $this->client->executeScript("
            var select = document.getElementById('{$id_select}');
            select.value = '{$value}';
            select.dispatchEvent(new Event('change'));
        ");
    }

    private function takeScreenshot(string $filename): void
    {
        $screenshot = $this->client->takeScreenshot();
        $dir = ucfirst($this->page);
        file_put_contents("tests/e2e/{$dir}/{$filename}", $screenshot);
    }
}
