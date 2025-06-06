<?php

namespace Tests\Feature;

use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class SupplierControllerTest extends TestCase
{
    //use RefreshDatabase;
    use \Illuminate\Foundation\Testing\DatabaseTransactions;

    private $suppliers;

    public function setUp(): void
    {
        parent::setUp();
        $this->withoutExceptionHandling();

        #Cria fornecedores fictícios para os testes
        $this->suppliers = $this->createSuppliers(15);
    }

    /**
     * Verifica se pelo menos um fornecedor está cadastrado.
     */
    public function testAssertThatAtLeastOneSupplierIsRegistered(): void
    {
        $response = $this->getJson('api/v1/suppliers')->assertStatus(Response::HTTP_OK)->json();

        $this->assertNotEmpty($response['data']);
    }

    /**
     * Verifica se a paginação de fornecedores funciona corretamente.
     */
    public function testAssertTheSupplierPaginationIsWorking(): void
    {
        $response = $this->getJson('api/v1/suppliers?page=2&per_page=5')->assertStatus(Response::HTTP_OK)->json();

        $this->assertNotEmpty($response['data']);
    }

    /**
     * Verifica se um erro apropriado é retornado quando o fornecedor não existe.
     */
    public function testReceiveANotFoundErrorIfASupplierDoesNotExist()
    {
        $supplier = $this->makeSupplier();

        $this->putJson('api/v1/suppliers/9999999999', [
            'name' => $supplier->name,
            'email' => $supplier->email,
            'cpf_cnpj' => $supplier->cpf_cnpj,
            'address' => $supplier->address,
            'number' => $supplier->number,
            'city' => $supplier->city,
            'state' => $supplier->state,
            'address_info'  => $supplier->address_info,
            'primary_contact'  => $supplier->primary_contact,
            'primary_contact_email' => $supplier->primary_contact_email
        ])->assertStatus(Response::HTTP_NOT_FOUND);
    }

    /**
     * Verifica se fornecedores podem ser cadastrados com CNPJ.
     */
    public function testAssertThatASupplierCanBeRegisteredWithCnpj(): void
    {
        $supplier = $this->makeSupplier();

        $response = $this->postJson('api/v1/suppliers', [
            'name' => $supplier->name,
            'email' => $supplier->email,
            'cpf_cnpj' => '83712729000118',
            'address' => $supplier->address,
            'number' => $supplier->number,
            'city' => $supplier->city,
            'state' => $supplier->state,
            'address_info'  => $supplier->address_info,
            'primary_contact'  => $supplier->primary_contact,
            'primary_contact_email' => $supplier->primary_contact_email
        ])->assertStatus(Response::HTTP_CREATED)->json('data');

        $this->assertEquals($supplier->name, $response['name']);
    }

    /**
     * Verifica se fornecedores podem ser cadastrados com CPF.
     */
    public function testAssertThatASupplierCanBeRegisteredWithCpf(): void
    {
        $supplier = $this->makeSupplier();

        $response = $this->postJson('api/v1/suppliers', [
            'name' => $supplier->name,
            'email' => $supplier->email,
            'cpf_cnpj' => '15751487060',
            'address' => $supplier->address,
            'number' => $supplier->number,
            'city' => $supplier->city,
            'state' => $supplier->state,
            'address_info'  => $supplier->address_info,
            'primary_contact'  => $supplier->primary_contact,
            'primary_contact_email' => $supplier->primary_contact_email
        ])->assertStatus(Response::HTTP_CREATED)->json('data');

        $this->assertEquals($supplier->name, $response['name']);
    }

    /**
     * Verifica se não é possível cadastrar sem CPF ou CNPJ.
     */
    public function testAssertASupplierCannotBeRegisteredWithoutCpfOrCnpj(): void
    {
        $supplier = $this->makeSupplier();

        try {
            $this->postJson('api/v1/suppliers', [
                'name' => $supplier->name,
                'email' => $supplier->email,
                'cpf_cnpj' => null,
                'address' => $supplier->address,
                'number' => $supplier->number,
                'city' => $supplier->city,
                'state' => $supplier->state,
                'address_info'  => $supplier->address_info,
                'primary_contact'  => $supplier->primary_contact,
                'primary_contact_email' => $supplier->primary_contact_email
            ]);
        } catch (ValidationException $e) {
            $this->assertEquals($e->status, Response::HTTP_UNPROCESSABLE_ENTITY);
            $this->assertEquals('The CPF/CNPJ field is required.', $e->getMessage());

            return;
        } catch (Exception $e) {
            $this->fail('Expected ValidationException was not thrown.');
        }
    }

    /**
     * Verifica que um fornecedor não pode ser cadastrado com CPF ou CNPJ já existente.
     */
    public function testAssertThatASupplierCannotBeRegisteredWithAnExistentCpfOrCnpj(): void
    {
        $supplier = $this->makeSupplier();

        try {
            $this->postJson('api/v1/suppliers', [
                'name' => $supplier->name,
                'email' => $supplier->email,
                'cpf_cnpj' => $this->suppliers[0]->cpf_cnpj, // Usando o CPF/CNPJ do primeiro fornecedor já cadastrado
                'address' => $supplier->address,
                'number' => $supplier->number,
                'city' => $supplier->city,
                'state' => $supplier->state,
                'address_info'  => $supplier->address_info,
                'primary_contact'  => $supplier->primary_contact,
                'primary_contact_email' => $supplier->primary_contact_email
            ]);
        } catch (ValidationException $e) {
            $this->assertEquals($e->status, Response::HTTP_UNPROCESSABLE_ENTITY);
            $this->assertEquals('This CPF/CNPJ already exists.', $e->getMessage());

            return;
        } catch (Exception $e) {
            $this->fail('Expected ValidationException was not thrown.');
        }
    }

    /**
     * Verifica que um fornecedor não pode ser cadastrado com um estado inválido.
     */
    public function testAssertThatASupplierCannotBeRegisteredWithAWrongStateData(): void
    {
        $supplier = $this->makeSupplier();

        try {
            $this->postJson('api/v1/suppliers', [
                'name' => $supplier->name,
                'email' => $supplier->email,
                'cpf_cnpj' => $supplier->cpf_cnpj,
                'address' => $supplier->address,
                'number' => $supplier->number,
                'city' => $supplier->city,
                'state' => 'XX', // Estado inválido
                'address_info'  => $supplier->address_info,
                'primary_contact'  => $supplier->primary_contact,
                'primary_contact_email' => $supplier->primary_contact_email
            ]);
        } catch (ValidationException $e) {
            $this->assertEquals($e->status, Response::HTTP_UNPROCESSABLE_ENTITY);
            $this->assertEquals('The state field must be a valid Brazilian state.', $e->getMessage());

            return;
        } catch (Exception $e) {
            $this->fail('Expected ValidationException was not thrown.');
        }
    }

    /**
     * Verifica que um fornecedor não pode ser cadastrado com dados vazios.
     */
    public function testAssertThatASupplierCannotBeRegisteredWithEmptyData(): void
    {
        try {
            $this->postJson('api/v1/suppliers', []);
        } catch (ValidationException $e) {
            $this->assertEquals($e->status, Response::HTTP_UNPROCESSABLE_ENTITY);
            $errors = $e->errors();
            $this->assertEquals('The CPF/CNPJ field is required.', $errors['cpf_cnpj'][0]);
            $this->assertEquals('The name field is required.', $errors['name'][0]);
            $this->assertEquals('The email field is required.', $errors['email'][0]);
            $this->assertEquals('The address field is required.', $errors['address'][0]);
            $this->assertEquals('The number field is required.', $errors['number'][0]);
            $this->assertEquals('The city field is required.', $errors['city'][0]);
            $this->assertEquals('The state field is required.', $errors['state'][0]);
            $this->assertEquals('The primary contact field is required.', $errors['primary_contact'][0]);
            $this->assertEquals('The primary contact email field is required.', $errors['primary_contact_email'][0]);

            return;
        } catch (Exception $e) {
            $this->fail('Expected ValidationException was not thrown.');
        }
    }

    /**
     * Verifica que um fornecedor pode ser atualizado
     */
    public function testAssertThatASupplierCanBeUpdated(): void
    {
        $supplier = $this->makeSupplier();

        $response = $this->putJson('api/v1/suppliers/' . $this->suppliers[0]->id, [
            'name' => $supplier->name,
            'email' => $supplier->email,
            'cpf_cnpj' => $supplier->cpf_cnpj,
            'address' => $supplier->address,
            'number' => $supplier->number,
            'city' => $supplier->city,
            'state' => $supplier->state,
            'address_info'  => $supplier->address_info,
            'primary_contact'  => $supplier->primary_contact,
            'primary_contact_email' => $supplier->primary_contact_email
        ])->assertStatus(Response::HTTP_OK)->json('data');

        $this->assertEquals($supplier->name, $response['name']);
    }

    /**
     * Verifica se um fornecedor pode ser removido
     */
    public function testAssertThatASupplierCanBeRemoved(): void
    {
        $this->deleteJson('api/v1/suppliers/' . $this->suppliers[0]->id)->assertStatus(Response::HTTP_GONE);
    }

    /**
     * Busca informações de um CNPJ
     */
    public function testAssertACompanyCanBeFoundByCnpj(): void
    {
        $response = $this->getJson('api/v1/suppliers/search/03995142000124')->assertStatus(Response::HTTP_OK)->json('data');

        $this->assertEquals('REVENDA MAIS', $response['nome_fantasia']);
    }
}
