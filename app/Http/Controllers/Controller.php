<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Traits\ControllerTrait;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

/**
 *@OA\Info(
 * title="Pasquali Solution Api - Referência da API",
 * version="1.0.0",
 * description="Documentação de cada EndPoint para integração.",
 * @OA\Contact(
 *  email="diego.pereira@pasqualisolution.com.br"
 * ),
 *),
 */
class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ControllerTrait;
}
