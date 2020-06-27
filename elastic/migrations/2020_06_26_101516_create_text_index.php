<?php
declare(strict_types=1);

use ElasticAdapter\Indices\Mapping;
use ElasticAdapter\Indices\Settings;
use ElasticMigrations\Facades\Index;
use ElasticMigrations\MigrationInterface;

final class CreateTextIndex implements MigrationInterface
{
    /**
     * Run the migration.
     */
    public function up(): void
    {
        Index::createIfNotExists('nlp_texts');
        Index::putMapping('nlp_texts', function (Mapping $mapping) {
            $mapping->text('content', ['analyzer' => 'english']);
            $mapping->text('category', [
                'analyzer' => 'english',
                'fields' => [
                    'raw' => [
                        'type' => 'keyword',
                    ]
                ]
            ]);
            $mapping->float('price');
        });
    }

    /**
     * Reverse the migration.
     */
    public function down(): void
    {
        Index::dropIfExists('nlp_texts');
    }
}
