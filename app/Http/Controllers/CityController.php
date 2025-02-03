<?php

namespace App\Http\Controllers;

use App\Models\City;
use App\Models\Medic;
use Illuminate\Http\Request;

class CityController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/cidades",
     *     summary="Listar todas as cidades",
     *     description="Retorna uma lista de todas as cidades cadastradas.",
     *     tags={"Cidades"},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de cidades retornada com sucesso",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(
     *
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="São Paulo")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao listar cidades",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Erro ao listar cidades."),
     *             @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
     *         )
     *     )
     * )
     */
    public function index()
    {
        try {
            $cities = City::all();

            return response()->json(
                $cities, 200);
        } catch (\Exception $e) {
            \Log::error('Erro ao listar cidades:', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Erro ao listar cidades.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * @OA\Get(
     *     path="/api/cidades/{id_cidade}/medicos",
     *     summary="Listar médicos de uma cidade específica",
     *     description="Retorna uma lista de médicos cadastrados em uma cidade específica. Permite busca por nome.",
     *     tags={"Médicos"},
     *
     *     @OA\Parameter(
     *         name="id_cidade",
     *         in="path",
     *         description="ID da cidade",
     *         required=true,
     *
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *
     *     @OA\Parameter(
     *         name="nome",
     *         in="query",
     *         description="Parte do nome do médico para busca (ignora 'Dr.' e 'Dra.')",
     *         required=false,
     *
     *         @OA\Schema(type="string", example="João")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Lista de médicos retornada com sucesso",
     *
     *         @OA\JsonContent(
     *             type="array",
     *
     *             @OA\Items(
     *
     *                 @OA\Property(property="id", type="integer", example=1),
     *                 @OA\Property(property="name", type="string", example="Dr. João Silva"),
     *                 @OA\Property(property="specialization", type="string", example="Cardiologista"),
     *                 @OA\Property(property="city_id", type="integer", example=1),
     *                 @OA\Property(property="created_at", type="string", format="date-time", example="2024-02-03T12:00:00Z"),
     *                 @OA\Property(property="updated_at", type="string", format="date-time", example="2024-02-03T12:00:00Z")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Cidade não encontrada",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Cidade não encontrada.")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao listar os médicos",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="message", type="string", example="Erro ao listar os médicos."),
     *             @OA\Property(property="error", type="string", example="Mensagem detalhada do erro")
     *         )
     *     )
     * )
     */
    public function getMedicosByCidade(Request $request, $id_cidade)
    {
        try {
            $city = City::find($id_cidade);

            if (! $city) {
                return response()->json(['message' => 'Cidade não encontrada.'], 404);
            }

            $query = Medic::where('city_id', $id_cidade);

            if ($request->has('nome') && ! empty($request->input('nome'))) {
                $nome = $request->input('nome');
                $nameWithoutPrefix = preg_replace('/^Dr\. |^Dra\. /i', '', $nome);
                $query->whereRaw("REPLACE(REPLACE(name, 'Dr. ', ''), 'Dra. ', '') LIKE ?", ["%$nameWithoutPrefix%"]);
            }
            $query->orderByRaw("REPLACE(REPLACE(name, 'Dr. ', ''), 'Dra. ', '') ASC");

            $medicos = $query->get();

            if ($medicos->isEmpty()) {
                return response()->json([
                    'cidade' => $city->name,
                    'message' => 'Nenhum médico encontrado nesta cidade.',
                ], 200);
            }

            return response()->json([
                'cidade' => $city->name,
                'medicos' => $medicos,
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Erro ao listar os médicos:', ['error' => $e->getMessage()]);

            return response()->json([
                'message' => 'Erro ao listar os médicos.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
