<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

/**
 * @Annotation
 */
class Property extends Schema
{

    /**
     * The key into Schema->properties array.
     * @var string
     */
    public $property;

    /**
     * A list of the versions supported.
     * @var Version[]
     */
    public $_versions;

    /** @inheritdoc */
    public static $_parents = [
        'Swagger\Annotations\Definition',
        'Swagger\Annotations\Schema',
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
