<?php

namespace App\Http\Controllers;

use App\Services\SupplierService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use App\Http\Requests\SupplierRequest;

/**
 * Controller responsável por gerenciar as operações relacionadas aos fornecedores.
 *
 * @package App\Http\Controllers
 * @version 1.0.0
 * @since 1.0.0
 * @license Private
 * @author Paulo <contato@juniorherval.com.br>
 *
 */
class SupplierController extends Controller
{
    /**
     * @var SupplierService
     */
    protected $supplierService;

    /**
     * Cosntrutor
     * .
     * Recebe como parâmetro o serviço por injeção de dependência
     *
     * @param SupplierService $supplierService
     *
     */
    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    /**
     * Retorna a lista de fornecedores.
     *
     * @param Request $request
     *
     * @return JsonResponse
     *
     */
    public function index(Request $request): JsonResponse
    {
        // Verifica se a página já está em cache, e caso não esteja, armazena no cache
        // A duração do cache é definida na variável CACHE_DURATION_DEFAULT,
        // o tempo de expiração padrão é 5 minutos
        $suppliers = Cache::remember($this->getUniqueCacheKey('suppliers-', $request->input()), env('CACHE_DURATION_DEFAULT', 300), function () use ($request) {
            return $this->supplierService->paginate($request->input('per_page'), $request->input('sort'), $request->input('sortOrder'));
        });

        return response()->json([
            'data' => compact('suppliers'),
        ], Response::HTTP_OK);
    }

    /**
     * Armazena um novo fornecedor no sistema.
     *
     * @param SupplierRequest $request
     *
     * @return JsonResponse
     *
     */
    public function store(SupplierRequest $request): JsonResponse
    {
        return response()->json([
            'data' => $this->supplierService->createSupplier($request->safe()->toArray()),
        ], Response::HTTP_CREATED);
    }

    /**
     * Atualiza os dados de um fornecedor do sistema.
     */
    public function update(SupplierRequest $request, $id): JsonResponse
    {
        $supplier = $this->supplierService->getSupplierById($id, false);

        // Verifica se o fornecedor existe antes de tentar atualizá-lo
        if (empty($supplier))
            return response()->json(['message' => 'The supplier does not exists.'], Response::HTTP_NOT_FOUND);

        $this->supplierService->updateSupplier($id, $request->safe()->toArray());

        return response()->json([
            'data' => $supplier->fresh()
        ], Response::HTTP_OK);
    }

    /**
     * Remove um fornecedor do sistema.
     *
     * @param int $supplier ID Do Fornecedor
     *
     * @return JsonResponse
     *
     */
    public function destroy(int $supplier): JsonResponse
    {
        if (empty($this->supplierService->getSupplierById($supplier, false)))
            return response()->json([
                'message' => 'The supplier does not exists.'
            ], Response::HTTP_NOT_FOUND);

        if ($this->supplierService->removeSupplier($supplier))
            return response()->json([
                'message' => 'Supplier removed successfully.'
            ], Response::HTTP_GONE);

        return response()->json([
            'message' => 'Error removing supplier.'
        ], Response::HTTP_BAD_REQUEST);
    }


    /**
     * Busca dados de um fornecedor pelo CNPJ.
     *
     * @param string $cnpj CNPJ a ser pesquisado
     *
     * @return JsonResponse
     */
    public function searchDataByCnpj($cnpj): JsonResponse
    {
        $supplier = $this->supplierService->searchDataByCnpj($cnpj);

        if (empty($supplier))
            return response()->json([
                'message' => 'No data found.'
            ], Response::HTTP_NOT_FOUND);

        return response()->json([
            'data' => $supplier
        ], Response::HTTP_OK);
    }
    /**
     * Gera uma chave única para o cache com base nos parâmetros fornecidos.
     *
     * @param string $prefix Prefixo para a chave de cache
     * @param array $params Parâmetros para gerar a chave de cache
     * @return string Chave única de cache
     */
    private function getUniqueCacheKey(string $prefix = '', array $params): string
    {
        $params = array_filter($params, function ($value) {
            return !empty($value);
        });

        ksort($params);

        return $prefix . implode('&', array_map(function ($key, $value) {
            return $key . '=' . $value;
        }, array_keys($params), $params));
    }
}
