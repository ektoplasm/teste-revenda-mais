<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface SupplierRepositoryInterface
{
    /**
     * Retorna todos os fornecedores - com paginação
     *
     * @param int|null $per_page - Número de itens por página - Padrão: 15
     * @param string|null $sort - Campo para ordenação - Padrão: 'created_at'
     *                          - Opções válidas: 'created_at', 'name', 'email', 'cpf_cnpj', 'city', 'state', 'primary_contact'
     * @param string|null $sortOrder - Direção da ordenação (ASC ou DESC)
     *
     * @return LengthAwarePaginator
     */
    public function paginateSuppliers(?int $per_page = 15, ?string $sort = 'created_at', ?string $sortOrder = 'DESC'): LengthAwarePaginator;

    /**
     * Retorna todos os fornecedores
     *
     * @return Collection
     */
    public function getAllSuppliers(): Collection;

    /**
     * Retorna um fornecedor pelo id.
     *
     * @param int $supplierId - Id do fornecedor
     *
     * @return Model|null
     */
    public function getSupplierById(int $supplierId): Model|null;

    /**
     * Armazenada um novo fornecedor.
     *
     * @param array $newSupplier Dados do novo fornecedor
     *
     * @return Model
     */
    public function createSupplier(array $newSupplier): Model;

    /**
     * Atualiza os dados de um fornecedor.
     *
     * @param int $supplierId Id do fornecedor
     * @param array $newSupplier Dados do fornecedor
     *
     * @return bool
     */
    public function updateSupplier(int $supplierId, array $newSupplier): bool;

    /**
     * Remove um fornecedor.
     *
     * @param int $supplierId Id do fornecedor
     *
     * @return bool
     */
    public function removeSupplier(int $supplierId): bool;
}
