<?php

namespace App\Http\Controllers;

use App\Models\PurchaseRequest;

class PrApprovalController extends ControllerApprovalTemplate
{
    protected $model = PurchaseRequest::class;
    protected $numberField = 'pr_number';
}
