<?php

namespace App\Services;

use App\Models\Supplier;
use App\Repositories\SupplierRepository;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class SupplierService
{
    /**
     * @var \App\Repositories\SupplierRepository
     */
    protected $supplierRepository;

    /**
     * SupplierService constructor.
     *
     * @param SupplierRepository $supplierRepository
     *
     */
    public function __construct(SupplierRepository $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;
    }

    /**
     * Recupera toda a lista de fornecedores.
     *
     * @return Collection
     */
    public function getAll(): Collection
    {
        return $this->supplierRepository->getAllSuppliers();
    }

    /**
     * Retorna a lista de fornecedores paginada.
     *
     * @param int|null $per_page - Número de itens por página - Padrão: 15
     * @param string|null $sort - Campo para ordenação
     * @param string|null $sortOrder - Direção da ordenação (ASC ou DESC)
     *
     * @return LengthAwarePaginator
     */
    public function paginate(?int $per_page = 15, ?string $sort = 'created_at', ?string $sortOrder = 'DESC'): LengthAwarePaginator
    {
        return $this->supplierRepository->paginateSuppliers($per_page, $sort, $sortOrder);
    }

    /**
     * Recupera um fornecedor pelo id.
     *
     * @param int $supplierId Id do fornecedor
     * @param bool $fail Se a operação deve falhar caso o fornecedor não exista no banco
     *
     * @return Supplier|null
     *
     */
    public function getSupplierById($supplierId, $fail): Supplier|null
    {
        return $this->supplierRepository->getSupplierById($supplierId, $fail);
    }

    /**
     * Cria um novo fornecedor.
     *
     * @param array $supplierData Dados do novo fornecedor
     *
     * @return Supplier|null
     *
     */
    public function createSupplier(array $supplierData): Supplier|null
    {
        return $this->supplierRepository->createSupplier($supplierData);
    }

    /**
     * Atualiza um novo fornecedor.
     *
     * @param int $supplierId Id do fornecedor
     * @param array $supplierData Dados atualizados do fornecedor
     *
     * @return bool
     *
     */
    public function updateSupplier($supplierId, array $supplierData): bool
    {
        return $this->supplierRepository->updateSupplier($supplierId, $supplierData);
    }

    /**
     * Remove um fornecedor pelo id.
     *
     * @param int $supplierId Id do fornecedor
     *
     * @return bool
     *
     */
    public function removeSupplier($supplierId): bool
    {
        return $this->supplierRepository->removeSupplier($supplierId);
    }

    /**
     * Busca dados de um fornecedor pelo CNPJ na BrasilAPI.
     *
     * @param string $cnpj CNPJ a ser pesquisado
     *
     * @return array|bool
     *
     */
    public function searchDataByCnpj($cnpj): array|bool
    {
        try {
            return json_decode(file_get_contents(sprintf('https://brasilapi.com.br/api/cnpj/v1/%s', $cnpj)), true);
        } catch (\Exception $e) {
            return false;
        }
    }
}
