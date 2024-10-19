<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponses;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ApiController extends Controller
{
    use AuthorizesRequests;
    use ApiResponses;

    protected $policyClass;
    public function include(string $relationship): bool
    {
        $param = request()->get('include');

        if (!isset($param)){
            return false;
        }
        $includedValues = explode(',', strtolower($param));
        return in_array(strtolower($relationship), $includedValues);
    }
    public function isAble($ability, $targetModel): Response
    {
        return $this->authorize($ability, [$targetModel, $this->policyClass]);
    }
}
