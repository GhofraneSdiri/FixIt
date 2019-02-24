<?php

namespace ServicesBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ReclamationControllerTest extends WebTestCase
{
    public function testAjout()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/reclamation/ajout');
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/reclamation/list');
    }

}
