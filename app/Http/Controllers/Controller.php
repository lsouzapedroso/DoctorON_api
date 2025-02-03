<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;

/**
 * @OA\Info(
 *     title="API de Consultas Médicas",
 *     version="1.0.0",
 *     description="Documentação da API para agendamento de consultas médicas.",
 *
 *     @OA\Contact(
 *         email="lsouzapedroso@gmail.com"
 *     )
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Insira o token JWT no formato: Bearer {token}"
 * )
 */
class Controller
{
    use AuthorizesRequests, ValidatesRequests;
}
