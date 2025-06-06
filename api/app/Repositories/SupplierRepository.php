<?php

namespace App\Repositories;

use App\Interfaces\SupplierRepositoryInterface;
use Illuminate\Support\Collection;
use App\Models\Supplier;
use Illuminate\Pagination\LengthAwarePaginator;

class SupplierRepository implements SupplierRepositoryInterface
{
    /**
     * @see SupplierRepositoryInterface::getAllSuppliers()
     */
    public function getAllSuppliers(): Collection
    {
        return Supplier::all();
    }

    /**
     * @see SupplierRepositoryInterface::paginateSuppliers()
     */
    public function paginateSuppliers(?int $per_page = 15, ?string $sort = 'created_at', ?string $sortOrder = 'DESC'): LengthAwarePaginator
    {
        $sortOrder = strtoupper($sortOrder) === 'ASC' ? 'ASC' : 'DESC';
        $per_page = is_numeric($per_page) && $per_page > 0 ? (int)$per_page : 15; // Padrão: 15 itens por página

        $validSortFields = ['created_at', 'name', 'email', 'cpf_cnpj', 'city', 'state', 'primary_contact'];
        if (!in_array($sort, $validSortFields))
            $sort = 'created_at'; // Default sort field

        return Supplier::orderBy($sort, $sortOrder)->paginate($per_page)->withQueryString();
    }

    /**
     * @see SupplierRepositoryInterface::getSupplierById()
     */
    public function getSupplierById(int $id, $fail = true): ?Supplier
    {
        return $fail ? Supplier::findOrFail($id) : Supplier::find($id);
    }

    /**
     * @see SupplierRepositoryInterface::createSupplier()
     */
    public function createSupplier(array $supplierData): Supplier
    {
        return Supplier::create($supplierData);
    }

    /**
     * @see SupplierRepositoryInterface::updateSupplier()
     */
    public function updateSupplier(int $id, array $supplierData): bool
    {
        $supplier = $this->getSupplierById($id);
        return $supplier ? $supplier->update($supplierData) : false;
    }

    /**
     * @see SupplierRepositoryInterface::removeSupplier()
     */
    public function removeSupplier(int $id): bool
    {
        return Supplier::destroy($id) > 0;
    }
}
