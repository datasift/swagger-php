<?php

namespace Swagger;

use Swagger\Annotations\Info;
use Swagger\Annotations\Swagger;

class Versioning
{
    /**
     * Process a swagger output for versioning tags,
     *      returns an array of versioned swagger objects or a single swagger object
     *
     * @param Swagger $swagger
     *
     * @return array|Swagger
     */
    public static function process(Swagger $swagger)
    {
        $versions = false;
        $output = [];

        if (isset($swagger->info) && $swagger->info instanceof Info) {
            $versions = $swagger->info->_versions;
        }

        if (false === $versions) {
            // TODO: clean up to cope with missing config settings, remove all version tags if they appear.
            return $swagger;
        }

        foreach ($versions as $version)
        {
            // Clone object to working variable
            $temp = unserialize(serialize($swagger));

            // Base path prefix
            if (isset($version->path) && $version->path != '') {
                $temp->basePath .= $version->path . "/";
            }

            // Version Number
            $temp->info->version = $version->ref;
            $ref = $version->ref;

            // TODO : check version number is valid

            $paths = null;
            foreach ($temp->paths as $path) {
                $keepPath = false;

                foreach ($path as $method => $operation) {
                    if ($operation !== null && in_array($method, ['get', 'put', 'post', 'delete', 'options', 'head', 'patch'])) {

                        if (isset($operation->_versions) && !empty($operation->_versions)) {
                            foreach ($operation->_versions as $version) {

                                if (self::keepInVersion($version->ref, $ref)) {
                                    $operation->_versions = null;
                                    $path->{$method} = $operation;
                                    $keepPath = true;
                                } else {
                                    $path->{$method} = null;
                                }
                            }
                        } else {
                            $keepPath = true;
                        }

                        // If we have removed the method then no point continuing
                        if (!is_null($path->{$method})) {
                            $path->{$method}->responses = self::processElement($operation->responses, $ref);
                            $path->{$method}->parameters = self::processElement($operation->parameters, $ref);
                        }
                    }
                }

                if ($keepPath) $paths[] = $path;
            }
            // Only include the paths to keep
            $temp->paths = $paths;


            // Definitions/Models
            $definitions = null;
            if (isset($temp->definitions) && !empty($temp->definitions))
            {
                foreach ($temp->definitions as $definition)
                {
                    $keepDest = false;
                    if (isset($definition->_versions) && ! empty($definition->_versions)) {
                        foreach ($definition->_versions as $version) {
                            if (self::keepInVersion($version->ref, $ref)) {
                                $definition->_versions = null;
                                $keepDest = true;
                            }
                        }
                    } else {
                        $keepDest = true;
                    }

                    if ($keepDest) {
                        // Definitions/Models Properties
                        $definition->properties = self::processElement($definition->properties, $ref);
                        $definitions[] = $definition;
                    }
                }
            }
            // Only include the definitions to keep
            $temp->definitions = $definitions;


            $temp->info->_versions = null;  // Remove version config
            $output[$ref] = $temp; // Save output to version tag in array of outputs
        }

        return $output;
    }

    /**
     * Process a set of elements and return only api version valid items.
     *
     * @param $element
     * @param $ref
     *
     * @return array|null
     */
    private static function processElement($element, $ref)
    {
        $elements = null;

        if (isset($element) && ! empty($element)) {
            foreach ($element as $e) {
                if (isset($e->_versions) && ! empty($e->_versions)) {
                    foreach ($e->_versions as $version) {
                        if (self::keepInVersion($version->ref, $ref)) {
                            $e->_versions = null;
                            $elements[] = $e;
                        }
                    }
                } else {
                    $elements[] = $e;
                }
            }
        }

        return $elements;
    }

    /**
     * keepInVersion
     *
     * @param $version
     * @param $ref
     *
     * @return bool
     */
    private static function keepInVersion($version, $ref)
    {
        // TODO: Add better support for semantic versioning, currently only support numeric versioning

        $version_number = $version;
        if (preg_match('/^(\<\=|\>\=|\<|\>|\!|\=){0,1}( )*[0-9]+(.[0-9]+)*$/', trim($version))) {
            $operator = false;
            if (preg_match('/^(\<\=|\>\=|\<|\>|\!|\=){1}.*$/', $version, $matches)) {
                $operator = array_pop($matches); // Last match is the operator
                $version_number = trim(str_replace($operator, "", $version_number));
            }

            switch ($operator) {
                case '>':
                    if ($ref > $version_number) return true;
                    break;
                case '<':
                    if ($ref < $version_number) return true;
                    break;
                case '<=':
                    if ($ref <= $version_number) return true;
                    break;
                case '>=':
                    if ($ref >= $version_number) return true;
                    break;
                case '!':
                    if ($ref != $version_number) return true;
                    break;
                default:
                    if ($ref == $version_number) return true;
            }
        } else {
            // TODO: display warning message, invalid version number
            return false; // Invalid version number, remove from the version.
        }
        return false;
    }
}