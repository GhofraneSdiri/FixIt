<?php

namespace ServicesBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategorieControllerTest extends WebTestCase
{
    public function testAjout()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/categorie/ajout');
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/categorie/list');
    }

    public function testUpadate()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/categorie/update');
    }

    public function testDelete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/categorie/delete');
    }

}
