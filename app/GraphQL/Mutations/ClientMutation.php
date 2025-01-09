<?php declare(strict_types=1);

namespace App\GraphQL\Mutations;
use App\Models\Client;
use Illuminate\Support\Facades\Validator;

final readonly class ClientMutation {
    /**
     * Create a new client.
     *
     * This method creates a new client in the database.
     * It validates the input data using the defined rules
     * and automatically assigns the "active" status to the client.
     *
     * @param array<string, mixed> $args GraphQL mutation input.
     * @return array<string, mixed> The created client's data as an associative array.
     * @throws \Illuminate\Validation\ValidationException If validation fails.
     */
    public function create($_, array $args): array {
        $data = $args["input"];
        $this->validate($data, $this->rules());
        $data = array_merge($data, ["status" => true]);
        $client = Client::create($data);

        return $client->toArray();
    }

    /**
     * Update an existing client.
     *
     * This method updates the data of an existing client in the database.
     * It validates the input data and ensures that the client exists before updating.
     *
     * @param array<string, mixed> $args GraphQL mutation input.
     * @return array<string, mixed> The updated client's data as an associative array.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the client is not found.
     * @throws \Illuminate\Validation\ValidationException If validation fails.
     */
    public function update($_, array $args): array {
        $client = Client::findOrFail($args["id"]);
        $this->validate($args["input"], $this->rules(["update" => true]));
        $client->update($args["input"]);

        return $client->toArray();
    }

    /**
     * Delete (deactivate) a client.
     *
     * This method deactivates an existing client by setting its status to false.
     * It does not physically remove the record from the database.
     *
     * @param array<string, mixed> $args GraphQL mutation input (includes the client ID).
     * @return array<string, mixed> The deactivated client's data as an associative array.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the client is not found.
     * @throws \Illuminate\Validation\ValidationException If validation fails.
     */
    public function delete($_, array $args): array {
        $this->validate($args, [
            "id" => ["required", "string", "exists:clients,id"],
        ]);

        $client = Client::findOrFail($args["id"]);
        $client->update(["status" => false]);

        return $client->toArray();
    }

    /**
     * Restore a deactivated client.
     *
     * This method changes the status of a previously deactivated client to true.
     *
     * @param array<string, mixed> $args GraphQL mutation input (includes the client ID).
     * @return array<string, mixed> The restored client's data as an associative array.
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException If the client is not found or is not inactive.
     */

    public function restore($_, array $args): array {
        $client = Client::where("id", $args["id"])
            ->where("status", false)
            ->firstOrFail();
        $client->update(["status" => true]);

        return $client->toArray();
    }

    /**
     * Definir las reglas de validación.
     *
     * Retorna las reglas de validación para crear o actualizar un cliente.
     * Si se utiliza en el contexto de actualización, las reglas incluyen
     * condiciones específicas para el ID y permiten valores opcionales.
     *
     * @param array<string, mixed> $context Contexto adicional para personalizar las reglas (e.g., ["update" => true]).
     * @return array<string, mixed> Reglas de validación.
     */
    protected function rules(array $context = []): array {
        $rules = [
            "id" => ["required", "string", "size:10", "unique:clients,id"],
            "name" => ["required", "string", "max:50"],
            "last_name" => ["required", "string", "max:50"],
            "birth_date" => ["required", "date"],
            "client_type" => ["required", "string", "in:Cash,Credit"],
            "address" => ["required", "string"],
            "phone" => ["required", "string"],
            "email" => ["required", "email", "unique:clients,email"],
        ];

        if ($context["update"] ?? false) {
            $rules["id"] = ["required", "string", "exists:clients,id"];
            foreach ($rules as &$rule) {
                $rule = array_merge(["sometimes"], $rule);
            }
        }

        return $rules;
    }

    /**
     * Validar los datos de entrada.
     *
     * Este método valida los datos de entrada según las reglas especificadas
     * y lanza una excepción si alguna regla no se cumple.
     *
     * @param array<string, mixed> $args Datos de entrada a validar.
     * @param array<string, mixed> $rules Reglas de validación a aplicar.
     * @throws \Illuminate\Validation\ValidationException Si la validación falla.
     */
    protected function validate(array $args, array $rules): void {
        $messages = [
            "client_type.in" =>
                "El tipo de cliente seleccionado no es válido. Los valores permitidos son: Cash, Credito.",
        ];
        $validator = Validator::make($args, $rules, $messages);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }
    }
}
