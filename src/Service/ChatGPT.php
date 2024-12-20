<?php

declare(strict_types=1);

namespace App\Service;

use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

final class ChatGPT
{
    private HttpClientInterface $client;

    public function __construct(private string $apiKey)
    {
        $this->client = HttpClient::createForBaseUri('https://api.openai.com/v1/');
    }

    public function generate(string $prompt): string
    {
        $response = $this->client->request('POST', 'chat/completions', [
            'headers' => [
                "Authorization" => "Bearer {$this->apiKey}",
                "Content-Type" => "application/json",
            ],
            'json' => [
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "Voici un prompt, réponds comme si tu étais homer simpsons en une dizaine de mots: $prompt",
                    ],
                ],
            ],
        ]);

        $data = $response->toArray();

        return $data['choices'][0]['message']['content'];
    }
}
