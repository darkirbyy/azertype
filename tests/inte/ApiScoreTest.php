<?php declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

final class ApiScoreTest extends TestCase
{
    private Client $client;

    public function setUp(): void
    {
        $this->client = new Client(['base_uri' => $_ENV['API_URL'].$_ENV['API_URI']]);
    }

    public function testScoreGet()
    {
        $response = $this->client->request('GET', 'score');

        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);
        
        $this->assertArrayHasKey('game_id', $data);
        $this->assertGreaterThanOrEqual(1, $data['game_id']);
        $this->assertArrayHasKey('best_time', $data);
        $this->assertGreaterThanOrEqual(0, $data['best_time']);
        $this->assertArrayHasKey('nb_players', $data);
        $this->assertGreaterThanOrEqual(0, $data['nb_players']);
    }

}
