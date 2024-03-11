<?php
namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/default');

        $this->assertResponseIsSuccessful();
        $this->assertJson($client->getResponse()->getContent());
        $this->assertJsonStringEqualsJsonString(
            '{"message":"Welcome to your new controller!","path":"src/Controller/DefaultController.php"}',
            $client->getResponse()->getContent()
        );
    }

    public function testBase()
    {
        $client = static::createClient();
        $client->request('GET', '/base');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Base Page'); // Replace 'h1' with your heading selector
    }

    public function testPost()
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Post Page'); // Replace 'h1' with your heading selector
    }

    public function testCatalogue()
    {
        $client = static::createClient();
        $client->request('GET', '/catalogue');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Catalogue Page'); // Replace 'h1' with your heading selector
    }

    public function testPostDetail()
    {
        $client = static::createClient();
        $client->request('GET', '/post/1'); // Replace '1' with the ID of a post in your database

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Post Detail Page'); // Replace 'h1' with your heading selector
    }
}
?>
