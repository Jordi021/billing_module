<?php declare(strict_types=1);

namespace App\GraphQL\Scalars;

use Carbon\Carbon;
use GraphQL\Error\Error;
use GraphQL\Language\AST\Node;
use GraphQL\Type\Definition\ScalarType;
use GraphQL\Language\AST\StringValueNode;

/** Read more about scalars here: https://webonyx.github.io/graphql-php/type-definitions/scalars. */
final class CustomDateTime extends ScalarType
{
    public string $name = 'CustomDateTime';
    public ?string $description = 'Formato de fecha personalizado m-d-Y h:i:s';

    /** Serializes an internal value to include in a response. */
    public function serialize(mixed $value): mixed
    {
        try {
            // Convertir a objeto Carbon si es string
            if (is_string($value)) {
                $value = Carbon::parse($value);
            }

            // Asegurar que el valor es una instancia de Carbon
            if (!$value instanceof Carbon) {
                throw new Error('Valor de fecha inválido');
            }

            // Retornar en el formato deseado
            return $value->format('m-d-Y h:i:s');
        } catch (\Exception $e) {
            throw new Error('Error al formatear la fecha: ' . $e->getMessage());
        }
    }

    /** Parses an externally provided value (query variable) to use as an input. */
    public function parseValue(mixed $value): mixed
    {
        try {
            return Carbon::createFromFormat('m-d-Y h:i:s', $value);
        } catch (\Exception $e) {
            throw new Error('Formato de fecha inválido. Use m-d-Y h:i:s');
        }
    }

    /**
     * Parses an externally provided literal value (hardcoded in GraphQL query) to use as an input.
     *
     * Should throw an exception with a client friendly message on invalid value nodes, @see \GraphQL\Error\ClientAware.
     *
     * @param  \GraphQL\Language\AST\ValueNode&\GraphQL\Language\AST\Node  $valueNode
     * @param  array<string, mixed>|null  $variables
     */
    public function parseLiteral(Node $valueNode, ?array $variables = null): mixed
    {
        if (!$valueNode instanceof StringValueNode) {
            throw new Error('La fecha debe ser un string');
        }

        try {
            return Carbon::createFromFormat('m-d-Y h:i:s', $valueNode->value);
        } catch (\Exception $e) {
            throw new Error('Formato de fecha inválido. Use m-d-Y h:i:s');
        }
    }
}
