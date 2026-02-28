<?php

namespace Tests\Feature;

use Tests\TestCase;

class OpenApiSpecTest extends TestCase
{
    public function test_openapi_spec_has_required_minimum_contract(): void
    {
        $path = resource_path('swagger/openapi.json');

        $this->assertFileExists($path);

        $json = file_get_contents($path);

        $this->assertNotFalse($json);

        $decoded = json_decode($json, true);

        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('paths', $decoded);
        $this->assertArrayHasKey('/api/books', $decoded['paths']);
        $this->assertArrayHasKey('/api/books/{id}', $decoded['paths']);
        $this->assertArrayHasKey('components', $decoded);
        $this->assertArrayHasKey('schemas', $decoded['components']);
        $this->assertArrayHasKey('Book', $decoded['components']['schemas']);
    }
}
