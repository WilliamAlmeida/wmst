<?php

use App\Models\User;

beforeEach(function () {
    config(['services.mcp.token' => 'secret-mcp-token']);
});

$payload = [
    'jsonrpc' => '2.0',
    'id' => 1,
    'method' => 'tools/list',
    'params' => [],
];

test('web mcp endpoint rejects requests without a token', function () use ($payload) {
    $this->postJson('/mcp/wmst-blog', $payload)->assertStatus(401);
});

test('web mcp endpoint rejects an invalid token', function () use ($payload) {
    $this->withHeaders(['Authorization' => 'Bearer wrong-token'])
        ->postJson('/mcp/wmst-blog', $payload)
        ->assertStatus(401);
});

test('web mcp endpoint rejects when no token is configured', function () use ($payload) {
    config(['services.mcp.token' => null]);

    $this->withHeaders(['Authorization' => 'Bearer secret-mcp-token'])
        ->postJson('/mcp/wmst-blog', $payload)
        ->assertStatus(401);
});

test('web mcp endpoint accepts the valid token and lists tools', function () use ($payload) {
    User::factory()->create();

    $response = $this->withHeaders([
        'Authorization' => 'Bearer secret-mcp-token',
        'Accept' => 'application/json, text/event-stream',
    ])->postJson('/mcp/wmst-blog', $payload);

    expect($response->status())->not->toBe(401);
    $response->assertStatus(200);
    expect($response->getContent())->toContain('create-blog-post');
});
