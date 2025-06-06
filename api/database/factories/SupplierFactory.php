<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class SupplierFactory extends Factory
{

    public function __construct()
    {
        parent::__construct();
        $this->faker = $this->withFakerPTBR();
    }
    /**
     * Model com as definições do fornecedor.
     *
     * @var class-string<\Illuminate\Database\Eloquent\Model>
     */
    protected $model = Supplier::class;

    protected function withFakerPTBR()
    {
        $faker = \Faker\Factory::create('pt_BR');
        $faker->addProvider(new \Faker\Provider\pt_BR\Person($faker));
        return $faker;
    }

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'cpf_cnpj' => $this->faker->unique()->cpf(false),
            'address' => $this->faker->streetAddress(),
            'number' => $this->faker->buildingNumber(),
            'city' => $this->faker->city(),
            'state' => $this->faker->stateAbbr(),
            'address_info' => $this->faker->postcode(),
            'primary_contact' => $this->faker->name(),
            'primary_contact_email' => $this->faker->unique()->safeEmail()
        ];
    }
}
