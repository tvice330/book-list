<?php

namespace Tests\Feature;

use Tests\TestCase;

class OpenApiSpecTest extends TestCase
{
    protected function setUp(): void
    {
        $originalMigratedState = static::$migrated;
        static::$migrated = true;

        parent::setUp();

        static::$migrated = $originalMigratedState;
    }

    public function test_openapi_spec_has_required_minimum_contract(): void
    {
        $docsDir = config('l5-swagger.documentations.default.paths.docs') ?? storage_path('api-docs');
        $jsonFile = config('l5-swagger.documentations.default.paths.docs_json') ?? 'api-docs.json';
        $path = rtrim($docsDir, DIRECTORY_SEPARATOR).DIRECTORY_SEPARATOR.$jsonFile;

        if (! file_exists($path)) {
            $this->artisan('l5-swagger:generate')->assertExitCode(0);
        }

        $this->assertFileExists($path);

        $json = file_get_contents($path);

        $this->assertNotFalse($json);

        $decoded = json_decode($json, true);

        $this->assertIsArray($decoded);
        $this->assertArrayHasKey('paths', $decoded);
        $this->assertArrayHasKey('/api/books', $decoded['paths']);
        $this->assertArrayHasKey('/api/books/{id}', $decoded['paths']);

        $hasOpenApiBookSchema = isset($decoded['components']['schemas']['Book']);
        $hasSwagger2BookSchema = isset($decoded['definitions']['Book']);

        $this->assertTrue(
            $hasOpenApiBookSchema || $hasSwagger2BookSchema,
            'Schema Book was not found in components.schemas or definitions.',
        );
    }
}
