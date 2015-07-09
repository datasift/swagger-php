<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

/**
 * @Annotation
 */
class Definition extends Schema
{

    /**
     * The key into Swagger->definitions array.
     * @var string
     */
    public $definition;

    /**
     * A list of the versions supported.
     * @var Version[]
     */
    public $_versions;

    /** @inheritdoc */
    public static $_types = [
        'definition' => 'string'
    ];

    /** @inheritdoc */
    public static $_parents = [
        'Swagger\Annotations\Swagger',
        'Swagger\Annotations\Schema',
        'Swagger\Annotations\Definition',
        'Swagger\Annotations\Property',
    ];

    /** @inheritdoc */
    public static $_nested = [
        'Swagger\Annotations\Items' => 'items',
        'Swagger\Annotations\Property' => ['properties', 'property'],
        'Swagger\Annotations\ExternalDocumentation' => 'externalDocs',
        'Swagger\Annotations\Xml' => 'xml',
        'Swagger\Annotations\Definition' => ['definitions', 'definition'],
        'Swagger\Annotations\Version' => ['_versions', '_version']
    ];
}
