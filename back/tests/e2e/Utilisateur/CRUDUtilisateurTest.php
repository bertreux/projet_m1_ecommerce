<?php

namespace App\Tests\e2e\Utilisateur;

use Symfony\Component\Panther\PantherTestCase;

// Il faut uniquement modifié remplir_formulaire(), edit_formulaire() et la propriété privé page

class CRUDUtilisateurTest extends PantherTestCase
{
    private $lastRowId;
    private $email;
    private $firstRowId;
    private $firstRowIdAfterCreate;
    private $client;
    private $page = 'utilisateur';

    public function testCRUD(): void
    {
        $this->client = static::createPantherClient();
        $this->create();
        $this->show();
        $this->edit();
        $this->delete();
    }

    private function remplir_formulaire($crawler) {
        $num = rand(1,9999999999);
        $this->email = "test{$num}@gmail.com";
        $crawler->filter('#utilisateur_email')->sendKeys($this->email);
        $crawler->filter('#utilisateur_nom')->sendKeys("test");
        $crawler->filter('#utilisateur_prenom')->sendKeys("test");
        $crawler->filter('#utilisateur_tel')->sendKeys("0000000000");
        $crawler->filter('#utilisateur_password')->sendKeys("test00");
        $crawler->filter('#utilisateur_isVerified')->click();
    }

    private function edit_formulaire($crawler) {
        $crawler->filter('#utilisateur_email')->sendKeys("1");
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
        $this->assertNotEquals($this->email, $qte, "La valeur avant et après la modification sont les memes");
    }

    private function delete(): void
    {
        $crawler = $this->client->request('GET', "/{$this->page}/");
        $firstTh = $crawler->filter('th')->first();
        $firstTh->click();

        $deleteButton = $crawler->filter('tr')->eq(1)->filter('td')->eq(0)->filter('input');
        $deleteButton->click();

        $this->client->executeScript('window.scrollTo(0, document.body.scrollHeight);');

        $this->takeScreenshot('tableau_clique_checkbox.png');

        $this->client->waitFor('#btn-delete-row:enabled');
        $deleteRowButton = $crawler->filter('#btn-delete-row');
        $deleteRowButton->click();

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
