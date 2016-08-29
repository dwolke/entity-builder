<?php
/**
 * ZF3 book Entity Builder Console Application
 *
 * @author     Ralf Eggert <ralf@travello.de>
 * @link       https://github.com/zf3buch/entity-builder
 * @license    http://opensource.org/licenses/MIT The MIT License (MIT)
 */

return [
    [
        'name'              => 'check-db',
        'route'             => 'check-db',
        'description'       => 'Check the access to the database adapter',
        'short_description' => 'Check access to database adapter',
        'handler'           => 'EntityBuilder\Command\CheckDb',
    ],
    [
        'name'              => 'show-tables',
        'route'             => 'show-tables',
        'description'       => 'Show all database tables available from database adapter',
        'short_description' => 'Show available database tables',
        'handler'           => 'EntityBuilder\Command\ShowTables',
    ],
    [
        'name'                 => 'create-entity',
        'route'                => 'create-entity <table>',
        'description'          => 'Create entity class for given database table',
        'short_description'    => 'Create entity class',
        'options_descriptions' => [
            '<table>' => 'name of table to create entity class for',
        ],
        'handler'              => 'EntityBuilder\Command\CreateEntity',
    ],
];
