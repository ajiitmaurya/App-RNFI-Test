<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class RNFIApiService
{
    protected $apiBase;
    protected $token;

    public function __construct()
    {
        $this->apiBase = env('API', 'http://127.0.0.1:8001/api');
        $data = session('data');
        $this->token = $data['token'] ?? null;
    }

    public function encryptData(array $data)
    {
        $response = $this->httpClient()->post("{$this->apiBase}/encrypt", $data)->json();
        return $response['encrypted'] ?? null;
    }

    public function decryptData(string $encrypted)
    {
        $response = $this->httpClient()->post("{$this->apiBase}/decrypt", $encrypted)->json();
        return $response['decrypted'] ?? null;
    }

    protected function httpClient()
    {
        return Http::withToken($this->token);
    }

    public function login($credentials)
    {
        return $this->httpClient()->post("{$this->apiBase}/login", $credentials)->json();
    }

    public function register($credentials)
    {
        return $this->httpClient()->post("{$this->apiBase}/register", $credentials)->json();
    }

    public function logout()
    {
        return $this->httpClient()->post("{$this->apiBase}/logout")->json();
    }

    public function getAll()
    {
        $data = $this->httpClient()->get("{$this->apiBase}/articles")->json();
        return $this->decryptData($data);
    }

    public function get($id)
    {
        $data = $this->httpClient()->get("{$this->apiBase}/article/{$id}")->json();
        return $this->decryptData($data);
    }

    public function create($title, $content)
    {
        $data = $this->httpClient()->post(
            "{$this->apiBase}/articles",
            $this->encryptData([
                'title' => $title,
                'content' => $content,
            ])
        )->json();
        return $this->decryptData($data);
    }

    public function update($id, $title = null, $content = null)
    {
        $data = $this->httpClient()->put(
            "{$this->apiBase}/article/{$id}",
            $this->encryptData([
                'title' => $title,
                'content' => $content,
            ])
        )->json();
        return $this->decryptData($data);
    }

    public function delete($id)
    {
        return $this->httpClient()->delete("{$this->apiBase}/article/{$id}")->json();
    }
}
