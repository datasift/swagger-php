<?php

/**
 * @license Apache 2.0
 */

namespace Swagger\Annotations;

/**
 * @Annotation
 * License information for the exposed API.
 */
class Version extends AbstractAnnotation
{
    /**
     * Version
     *
     * @var string
     */
    public $_version;

    /**
     * The ref of the version supported.
     * @var string
     */
    public $ref;

    /**
     * A short description of the version. GFM syntax can be used for rich text representation.
     * @var string
     */
    public $description;

    /**
     * The version value which will appear after the base path.
     *  (host + basePath + path - example.com/api/v1.1)
     * @var string
     */
    public $path;

    /** @inheritdoc */
    public static $_required = ['ref'];

    /** @inheritdoc */
    public static $_types = [
        'ref' => 'string',
        'description' => 'string'
    ];

    /** @inheritdoc */
    public static $_parents = [
        'Swagger\Annotations\Info',
        'Swagger\Annotations\Response',
        'Swagger\Annotations\Operation',
        'Swagger\Annotations\Get',
        'Swagger\Annotations\Post',
        'Swagger\Annotations\Put',
        'Swagger\Annotations\Patch',
        'Swagger\Annotations\Delete',
        'Swagger\Annotations\Parameter',
        'Swagger\Annotations\Property',
        'Swagger\Annotations\Definition'
    ];
}