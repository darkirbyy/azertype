<?php declare(strict_types=1);

use PHPUnit\Framework\Attributes\Depends;
use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

final class ApiScoreTest extends TestCase
{
    private Client $client;

    public function setUp(): void
    {
        $this->client = new Client(['base_uri' => $_ENV['API_URL'].$_ENV['API_URI'],
                                    'timeout'  => $_ENV['API_TIMEOUT_MS'] / 1000,
                                    'http_errors' => false]);
    }

    public function testScoreGet()
    {
        $response = $this->client->get('score');

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

        return $data['game_id'];
    }

    #[Depends('testScoreGet')]
    public function testScorePostGameExpired($game_id)
    {
        $response = $this->client->post('score',
        ['json' => [
            'game_id' => $game_id - 1,
            'time' => 0,
        ]]);

        $statusCode = $response->getStatusCode();
        $this->assertEquals(400, $statusCode);

        $body = $response->getBody()->getContents();
        $data = json_decode($body, true);
        
        $this->assertArrayHasKey('error', $data);
    }

    #[Depends('testScoreGet')]
    public function testScorePostGameValid($game_id)
    {
        $response = $this->client->post('score',
        ['json' => [
            'game_id' => $game_id,
            'time' => 0,
        ]]);

        $statusCode = $response->getStatusCode();
        $this->assertEquals(200, $statusCode);
    }


}
