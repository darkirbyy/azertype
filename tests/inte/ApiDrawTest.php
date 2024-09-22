<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

final class ApiDrawTest extends TestCase
{
    private Client $client;

    public function setUp(): void
    {
        $this->client = new Client(['base_uri' => $_ENV['API_URL'].$_ENV['API_URI']]);
    }

    public function testDrawGet()
    {
        $response = $this->client->request('GET', 'draw');

        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);
        
        $this->assertArrayHasKey('game_id', $data);
        $this->assertGreaterThanOrEqual(1, $data['game_id']);
        $this->assertArrayHasKey('words', $data);
        $this->assertEquals($_ENV['WORDS_PER_DRAW'], sizeof(explode(',', $data['words'])));
        $this->assertArrayHasKey('wait_time', $data);
        $this->assertGreaterThanOrEqual(0, $data['wait_time']);
    }

}
