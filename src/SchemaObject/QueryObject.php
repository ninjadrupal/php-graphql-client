<?php

namespace GraphQL\SchemaObject;

use GraphQL\Exception\EmptySelectionSetException;
use GraphQL\QueryBuilder\AbstractQueryBuilder;

/**
 * An abstract class that acts as the base for all schema query objects generated by the SchemaScanner
 *
 * Class QueryObject
 *
 * @package GraphQL\SchemaObject
 */
abstract class QueryObject extends AbstractQueryBuilder
{
    /**
     * This constant stores the name of the object name in the API definition
     *
     * @var string
     */
    private const OBJECT_NAME = '';

    /**
     * SchemaObject constructor.
     *
     * @param string $nameAlias
     */
    public function __construct(string $nameAlias = '')
    {
        $queryObject = !empty($nameAlias) ? $nameAlias : static::OBJECT_NAME;
        parent::__construct($queryObject);
    }

    /**
     * Constructs the object's arguments list from its attributes
     */
	protected function constructArgumentsList(): array
    {
        $argumentsList = [];
        foreach ($this as $name => $value) {
            // TODO: Use annotations to avoid having to check on specific keys
            if (empty($value) || in_array($name, ['nameAlias', 'selectionSet', 'argumentsList', 'query'])) continue;

            // Handle input objects before adding them to the arguments list
            if ($value instanceof InputObject) {
                $value = $value->toRawObject();
            }

            $argumentsList[$name] = $value;
        }

        return $argumentsList;
    }

    /**
     * @return string
     * @throws EmptySelectionSetException
     */
    public function getQueryString(): string
    {
        return (string) $this->getQuery();
    }
}