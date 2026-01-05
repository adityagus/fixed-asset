<?php

namespace App\Http\Controllers;

use App\Models\Assets;
use App\Models\LogAssetNumber;
use App\Models\LogRequestNumber;
use App\Models\Notes;
use App\Models\PurchaseOrderApproval;
use App\Models\PurchaseRequestApproval;
use App\Models\RegistrationAsset;
use App\Models\RegistrationAssetApproval;
use App\Models\RegistrationAssetItem;
use App\Models\Susut;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Validators\ValidationException;
use Tymon\JWTAuth\Facades\JWTAuth;

class SubmissionController extends Controller
{

    public function index(Request $request, string $type): JsonResponse
    {
        try {
            // Ambil user dari JWT payload
            $payload = JWTAuth::parseToken()->getPayload();
            $user = (object) $payload->get('user');
            $username = $user->username;
            

            switch ($type) {
                case 'purchase-request':
                    $model = PurchaseRequest::with([
                        'approvals' => function ($query) {
                            $query->select('*')
                                ->whereNotNull('approval_status')
                                ->orderBy('layer', 'desc');
                        },
                        'purchaseRequestItems.itemMaster.category'
                    ])
                    ->where('created_by', $username)
                    ->orderBy('created_at', 'desc')->get();
                    foreach ($model as $pr) {
                        $pr->date = Carbon::parse($pr->pr_date)->format('d M Y  H:i');
                    }
                    break;
                case 'purchase-order':
                    $model = PurchaseOrder::with([
                        'approvals' => function ($query) {
                            $query->select('*')
                                ->whereNotNull('approval_status')
                                ->orderBy('layer', 'desc');
                        },
                        'vendor',
                        'purchaseOrderItems.itemMaster.category',
                    ])
                    ->where('created_by', $username)
                    ->orderBy('created_at', 'desc')->get();
                    foreach ($model as $po) {
                        $po->date = Carbon::parse($po->po_date)->format('d M Y  H:i');
                    }
                    break;
                case 'registration-asset':
                    $model = RegistrationAsset::with([
                        'approvals' => function ($query) {
                            $query->select('*')
                                ->whereNotNull('approval_status')
                                ->orderBy('layer', 'desc');
                        },
                        'RegistrationAssetItems.itemMaster.category',
                        'purchaseOrder:purchase_request_number,po_number',
                        'purchaseOrder.purchaseRequest:id,pr_number,created_by,cabang'
                    ])
                    ->where('created_by', $username)
                    ->orderBy('created_at', 'desc')->get();

                    foreach ($model as $ra) {
                        $ra->date = Carbon::parse($ra->ra_date)->format('d M Y  H:i');
                    }

                    break;
                default:
                    abort(404);
            }
            
            return response()->json([
                'success' => true,
                'data' => $model,
                // 'user' => $user // opsional, jika ingin kirim user ke frontend
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete(Request $request, $type, $requestNumber): JsonResponse
    {

        switch ($type) {
            case 'purchase-request':
                $title = 'Purchase Request';
                $submissionId = PurchaseRequest::where('pr_number', $requestNumber)->where('status', 'Draft')->firstOrFail()->id;
                $model = PurchaseRequest::findOrFail($submissionId);
                break;
            case 'purchase-order':
                $title = 'Purchase Order';
                $submissionId = PurchaseOrder::where('po_number', $requestNumber)->where('status', 'Draft')->firstOrFail()->id;
                $model = PurchaseOrder::findOrFail($submissionId);
                break;
            case 'registration-asset':
                $title = 'Registration Asset';
                $submissionId = RegistrationAsset::where('ra_number', $requestNumber)->where('status', 'Draft')->firstOrFail()->id;
                $model = RegistrationAsset::findOrFail($submissionId);
                break;
            default:
                abort(404);
        }
        $model->update([
            'status' => 'Canceled'
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Deleted ' . $title . ' with Request Number ' . $requestNumber
        ]);
    }
    
    
    public function edit ($type, $requestNumber){
        
        switch ($type) {
            case 'purchase-request':
                $title = 'Purchase Request';
                $submissionId = PurchaseRequest::where('pr_number', $requestNumber)->where('status', 'Waiting Approval')->firstOrFail()->id;
                $model = PurchaseRequest::findOrFail($submissionId);
                break;
            case 'purchase-order':
                $title = 'Purchase Order';
                $submissionId = PurchaseOrder::where('po_number', $requestNumber)->where('status', 'Waiting Approval')->firstOrFail()->id;
                $model = PurchaseOrder::findOrFail($submissionId);
                break;
            case 'registration-asset':
                $title = 'Registration Asset';
                $submissionId = RegistrationAsset::where('ra_number', $requestNumber)->where('status', 'Waiting Approval')->firstOrFail()->id;
                $model = RegistrationAsset::findOrFail($submissionId);
                break;
            default:
                abort(404);
        }
        $model->update([
            'status' => 'Draft'
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Edited ' . $title . ' with Request Number ' . $requestNumber
        ]);
    }

    public function approvalsList(Request $request, string $type): JsonResponse
    {
        try {
            $payload = JWTAuth::parseToken()->getPayload();
            $user = (object) $payload->get('user');
            // $username = "Agung"; // Ganti dengan $user->username jika ada
            $username = $user->username; // Ganti dengan $user->username jika ada
            

            switch ($type) {
                case 'purchase-request':
                    $model = PurchaseRequest::with('approvals')
                        ->whereIn('status', ['Waiting Approval', 'Revised', 'Rejected', 'Full Approved'])
                        ->orderByRaw('GREATEST(UNIX_TIMESTAMP(created_at), UNIX_TIMESTAMP(updated_at)) DESC')
                        ->get();
                    break;
                case 'purchase-order':
                    $model = PurchaseOrder::with('approvals', 'purchaseRequest')
                        ->whereIn('status', ['Waiting Approval', 'Revised', 'Rejected', 'Full Approved'])
                        ->orderByRaw('GREATEST(UNIX_TIMESTAMP(created_at), UNIX_TIMESTAMP(updated_at)) DESC')
                        ->get();
                        
                    break;
                case 'registration-asset':
                    $model = RegistrationAsset::with(
                        'approvals',
                        'purchaseOrder:purchase_request_number,po_number',
                        'purchaseOrder.purchaseRequest:id,pr_number,created_by,cabang'
                    )
                        ->whereIn('status', ['Waiting Approval', 'Revised', 'Rejected', 'Full Approved'])
                        ->orderByRaw('GREATEST(UNIX_TIMESTAMP(created_at), UNIX_TIMESTAMP(updated_at)) DESC')
                        ->get();
                    break;
                default:
                    abort(404);
            }

            $result = [
                'waiting approval' => [],
                'approved' => [],
                'revised' => [],
                'rejected' => [],
                'other' => [],
            ];

            foreach ($model as $item) {
                $userIsApprover = false;
                $waitingApproval = false;
                $revised = false;
                $rejected = false;
                $allApproved = true;

                
                $item->request_time = Carbon::parse($item->updated_at ?? $item->created_at)->format('d-m-Y H:i');
                foreach ($item->approvals as $approval) {
                    $statusLower = strtolower($approval->approval_status);

                    if ($approval->approver_by === $username) {
                        $userIsApprover = true;
                        if (in_array($statusLower, ['in progress', 'waiting approval'])) {
                            $waitingApproval = true;
                        }
                        if ($statusLower === 'revised') {
                            $revised = true;
                        }
                        if ($statusLower === 'rejected') {
                            $rejected = true;
                        }
                    }

                    // Cek jika ada approval yang belum approved/full approved
                    if (!in_array($statusLower, ['approved', 'full approved'])) {
                        $allApproved = false;
                    }
                }

                if (!$userIsApprover) {
                    continue;
                }

                if ($waitingApproval) {
                    $result['waiting approval'][] = $item;
                    continue;
                }
                if ($revised) {
                    $result['revised'][] = $item;
                    continue;
                }
                if ($rejected) {
                    $result['rejected'][] = $item;
                    continue;
                }
                if ($allApproved) {
                    $result['approved'][] = $item;
                    continue;
                }

                $result['other'][] = $item;
            }

            foreach ($result as $stat => $arr) {
                if (empty($arr))
                    unset($result[$stat]);
            }

            return response()->json([
                'success' => true,
                'data' => $result,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public static function CreateLayerApproval(Request $request)
    {
        try {
            // Validasi input
            $data = $request->validate([
                'type' => 'required|string|in:purchase-request,purchase-order,registration-asset',
                'cabang' => 'nullable|string',
                'idgrup' => 'nullable|string',
                'jabatan' => 'nullable|string',
                'request_number' => 'required|string',
                'need_it_approval' => 'required|string|in:true,false',
            ]);
            
            // dd($data);

            // Pilih model approval sesuai type
            $typeMap = [
                'purchase-request' => PurchaseRequestApproval::class,
                'purchase-order' => PurchaseOrderApproval::class,
                'registration-asset' => RegistrationAssetApproval::class,
            ];
            $approvalModel = $typeMap[$data['type']];

            // Hapus approval lama berdasarkan request_number
            $approvalModel::where('request_number', $data['request_number'])->delete();

            $layers = [];
            $now = Carbon::now()->format('Y-m-d H:i:s');

            // Ambil approver dari master approval
            // Jika tipe bukan 'purchase-request' maka ambil yang type IS NULL
            // buat jika termasuk 2 array dengan idgrup JBT-001 atau JBT-002
            if ($data['type'] !== 'purchase-request') {
                $approvers = \DB::table(table: 'mst_approval')
                    ->select('layer', 'email', 'jabatan', 'type', 'username')
                    ->where('type', 'GA Head')
                    ->orderBy('layer', 'asc')
                    ->get();
            } else {
                // Untuk purchase-request, gunakan need_it_approval untuk memilih IT atau GA (plus NULL)
                $cmApprovers = [];
                $group = ['JBT-021', 'JBT-005'];
                if (!empty($data['cabang']) && ($data['idgrup'] === $group[0] || $data['idgrup'] === $group[1])) {
                    $cmApprovers = self::approvalCM($data['cabang'])->toArray();
                }

                if ($data['need_it_approval'] === 'true') {
                    $mainApprovers = \DB::table('mst_approval')
                        ->select('layer', 'email', 'jabatan', 'type', 'username')
                        ->whereIn('type', ['IT'])
                        ->orderByRaw("CASE WHEN type = 'IT' THEN 0 ELSE 1 END, layer ASC")
                        ->get()
                        ->toArray();
                } else {
                    $mainApprovers = \DB::table('mst_approval')
                        ->select('layer', 'email', 'jabatan', 'type', 'username')
                        ->whereIn('type', ['GA Head'])
                        ->orderByRaw("CASE WHEN type = 'GA' THEN 0 ELSE 1 END, layer ASC")
                        ->get()
                        ->toArray();
                }
                

                // Gabungkan CM dan mainApprovers
                $approvers = array_merge($cmApprovers, $mainApprovers);
                if($approvers === null){
                    return response()->json([
                        'success' => false,
                        'message' => 'No approvers found for the given criteria.'
                    ], 404);
                }
                
            }

            
            $index = 1;
            foreach ($approvers as $approvalLayer) {
                $layers[] = [
                    'type' => $data['type'] ?? '',
                    'request_number' => $data['request_number'],
                    'approval_status' => $index == 1 ? 'In Progress' : null,
                    'layer' => $index++,
                    'note' => '',
                    'approver_by' => $approvalLayer->username,
                    'email' => $approvalLayer->email,
                    'jabatan' => $approvalLayer->jabatan,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }
            // Insert layer baru ke tabel approval
            if (!empty($layers)) {
                $approvalModel::insert($layers);
            }

            return response()->json([
                'success' => true,
                'message' => 'Layer approval created successfully.',
                'data' => $layers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }


    public static function SetApprovalStatus(Request $request)
    {
        try {
            $data = $request->validate([
                'layer' => 'required|integer',
                'status' => 'required|string|in:Approved,Revised,Rejected',
                'type' => 'required|string|in:purchase-request,purchase-order,registration-asset',
                'formNumber' => 'required|string',
            ]);
            // Pilih model approval sesuai tipe
            $approvalModelMap = [
                'purchase-request' => PurchaseRequestApproval::class,
                'purchase-order' => PurchaseOrderApproval::class,
                'registration-asset' => RegistrationAssetApproval::class,
            ];
            $approvalModel = $approvalModelMap[$data['type']];
            $approval = $approvalModel::where('type', $data['type'])
                ->where('request_number', $data['formNumber'])
                ->where('layer', $data['layer'])
                ->firstOrFail();

            $approval->update([
                'approval_status' => $data['status'],
                'approval_date' => now(),
            ]);

            // Jika status Approved, aktifkan layer berikutnya (jika ada)
            $nextApproval = null;
            $nextApproval = $approvalModel::where('type', $data['type'])
                ->where('request_number', $data['formNumber'])
                ->where('layer', $data['layer'] + 1)
                ->first();
            if ($data['status'] === 'Approved') {
                if ($nextApproval && $nextApproval->approval_status === null) {
                    $nextApproval->update([
                        'approval_status' => 'in progress',
                        'approval_date' => null,
                    ]);
                }

                if($nextApproval === null && $data['type'] === 'purchase-order') {
                    // buat registration asset
                    $purchaseOrder = PurchaseOrder::where('po_number', $data['formNumber'])->first();

                    // semua layer sudah approved
                    // buat registration asset dengan status draft
                    $registrationAsset = RegistrationAsset::create([
                        'ra_number' => LogRequestNumber::createRequest('RA')->request_number,
                        'purchase_order_number' => $purchaseOrder->po_number,
                        'created_by' => $purchaseOrder->created_by,
                        'ra_date' => null,
                        'invoice_date' => null,
                        'status' => 'draft',
                    ]);
                    
                    

                }
                
                if ($nextApproval === null && $data['type'] === 'registration-asset') {
                    // register asset 
                    $registerAsset = RegistrationAsset::where('ra_number', $data['formNumber'])->first();

                    // semua layer sudah approved
                    // set asset registration jadi full approved
                    $registrationAssetItem = RegistrationAssetItem::with('itemMaster.type')->where('registration_asset_number', $data['formNumber'])->get();

                    // Ambil semua item terkait sekaligus asset-nya
                    $registrationAssetItem = RegistrationAssetItem::with('itemMaster.type')->where('registration_asset_number', $data['formNumber'])->get();
                    $assetIds = $registrationAssetItem->pluck('id', 'total_price')->toArray();
                    $assets = Assets::whereIn('regist_item_id', $assetIds)->get()->keyBy('regist_item_id');

                    $updates = [];
                    $assetUpdates = [];
                    $susutUpdates = [];

                    foreach ($registrationAssetItem as $item) {
                        $assetNumber = LogAssetNumber::createAsset($item->itemMaster->type->kode)->asset_number;
                        $item->asset_number = $assetNumber;
                        $updates[] = [
                            'id' => $item->id,
                            'asset_number' => $assetNumber,
                        ];

                        if (isset($assets[$item->id])) {
                            $assets[$item->id]->asset_number = $assetNumber;
                            $assetUpdates[] = [
                                'id' => $item->id,
                                'asset_number' => $assetNumber,
                            ];
                        }

                        $susutUpdates[] = [
                            'asset_id' => $assets[$item->id]->id ?? null,
                            'cbg_id' => $assets[$item->id]->location ?? null,
                            'nom_susut' => $item->total_price / $item->itemMaster->category->umur,
                            'sisa_umur' => $item->itemMaster->category->umur,
                            'sts_tmt' => null,
                            'sts_jual' => null,
                            'tgl_reg' => $registerAsset->ra_date,
                            'total_umur' => $item->itemMaster->category->umur,
                        ];

                    }

                    // Bulk update RegistrationAssetItem
                    foreach ($updates as $update) {
                        RegistrationAssetItem::where('id', $update['id'])->update(['asset_number' => $update['asset_number']]);
                    }

                    // Bulk update Assets
                    foreach ($assetUpdates as $update) {
                        Assets::where('regist_item_id', $update['id'])->update(
                            [
                                'asset_number' => $update['asset_number']
                            ]);
                    }

                    foreach ($susutUpdates as $update) {
                        Susut::create([
                            'asset_id' => $update['asset_id'],
                            'cbg_id' => $update['cbg_id'],
                            'nom_susut' => $update['nom_susut'],
                            'sisa_umur' => $update['sisa_umur'],
                            'sts_tmt' => $update['sts_tmt'] ?? 0,
                            'sts_jual' => $update['sts_jual'] ?? 0,
                            'tgl_reg' => $update['tgl_reg'],
                            'total_umur' => $update['total_umur'],
                        ]);
                    }

                }

            }

            // Jika revised/rejected ATAU tidak ada layer berikutnya setelah approved, update status di tabel utama
            if (
                $data['status'] === 'Revised' ||
                $data['status'] === 'Rejected' ||
                ($data['status'] === 'Approved' && !$nextApproval)
            ) {
                $typeMap = [
                    'purchase-request' => PurchaseRequest::class,
                    'purchase-order' => PurchaseOrder::class,
                    'registration-asset' => RegistrationAsset::class,
                ];
                $numberFieldMap = [
                    'purchase-request' => 'pr_number',
                    'purchase-order' => 'po_number',
                    'registration-asset' => 'ra_number',
                ];
                $statusValueMap = [
                    'Approved' => 'full approved',
                    'Revised' => 'revised',
                    'Rejected' => 'rejected',
                ];
                $modelClass = $typeMap[$data['type']];
                $numberField = $numberFieldMap[$data['type']];
                $statusValue = $statusValueMap[$data['status']];
                $mainModel = $modelClass::where($numberField, $data['formNumber'])->first();
                if ($mainModel) {
                    $mainModel->status = $statusValue;
                    $mainModel->save();
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Approval status updated successfully.',
                'data' => $approval
            ]);
        } catch (ValidationException $ve) {
            return response()->json([
                'success' => false,
                'message' => $ve->getMessage()
            ], 500);
        }
    }

    public function listApproved(Request $request): JsonResponse
    {
        try {
            $formType = $request->route('formType');
            switch ($formType) {
                case 'purchase-order':
                    // Ambil semua PR yang statusnya Full Approved
                    $purchaseRequests = PurchaseRequest::with('purchaseRequestItems.itemMaster.category', 'purchaseRequestItems.itemMaster.brand', 'purchaseRequestItems.itemMaster.type', 'approvals')
                        ->where('status', 'Full Approved')
                        ->whereHas('purchaseRequestItems', function ($query) {
                            $query->where('is_locked', false);
                        })
                        ->orderBy('created_at', 'desc')
                        ->distinct()
                        ->get();

                    // Ambil semua nomor PR yang sudah ada di PO dengan status bukan draft
                    $usedPrNumbers = PurchaseOrder::where('status', '!=', 'draft')
                        ->whereNotNull('purchase_request_number')
                        ->pluck('purchase_request_number')
                        ->toArray();

                    // Filter PR yang belum pernah dipakai di PO non-draft
                    $filteredRequests = $purchaseRequests->filter(function ($pr) use ($usedPrNumbers) {
                        return !in_array($pr->pr_number, $usedPrNumbers);
                    })->values();

                    return response()->json([
                        'success' => true,
                        'data' => $filteredRequests
                    ]);

                    break;
                case 'registration-asset':
                    // Ambil semua PR yang statusnya Full Approved
                    $purchaseOrder = PurchaseOrder::with('purchaseOrderItems.itemMaster.category', 'purchaseOrderItems.itemMaster.brand', 'purchaseOrderItems.itemMaster.type', 'approvals', 'purchaseRequest')
                        ->where('status', 'Full Approved')
                        ->orderBy('created_at', 'desc')
                        ->get();

                    // Ambil semua nomor PR yang sudah ada di PO dengan status bukan draft
                    $usedPrNumbers = RegistrationAsset::where('status', '!=', 'draft')
                        ->whereNotNull('purchase_order_number')
                        ->pluck('purchase_order_number')
                        ->toArray();

                    // Filter PR yang belum pernah dipakai di PO non-draft
                    $filteredRequests = $purchaseOrder->filter(function ($po) use ($usedPrNumbers) {
                        return !in_array($po->po_number, $usedPrNumbers);
                    })->values();

                    return response()->json([
                        'success' => true,
                        'data' => $filteredRequests
                    ]);

                    break;

                default:
                    return response()->json([
                        'success' => false,
                        'message' => 'form type not purchase order or registration asset.'
                    ], 200);
                    break;
            }

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
        // jwt auth user
        // $user = $request->get('jwt_user');
    }


    // Notes
    public function sendNote(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'form_type' => 'required|string|in:purchase-request,purchase-order,registration-asset',
                'form_number' => 'required|string',
                'text' => 'required|string',
                'sender' => 'required|string',
                'time' => 'required|string',
            ]);
            $now = Carbon::now()->format('Y-m-d H:i:s');
            

            // Simpan note ke tabel notes (buat model Notes jika belum ada)
            switch ($data['form_type']) {
                case 'purchase-request':
                    $notes = PurchaseRequestApproval::where('request_number', $data['form_number'])->where('approver_by', $data['sender'])->firstOrFail();
                    
                    break;
                case 'purchase-order':
                    $notes = PurchaseOrderApproval::where('request_number', $data['form_number'])->where('approver_by', $data['sender'])->firstOrFail();
                    break;
                case 'registration-asset':
                    $notes = RegistrationAssetApproval::where('request_number', $data['form_number'])->where('approver_by', $data['sender'])->firstOrFail();
                    break;
                    
                default:
                    abort(404);
                    break;
            }
            
            $note = $notes;
            $note->note = $data['text'];
            $note->save();

            return response()->json([
                'success' => true,
                'message' => 'Note sent successfully.',
                'data' => $note,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getNotes(Request $request)
    {
        try {
            $form_type = $request->query('formType');
            $form_number = $request->query('formNumber');
            
            $notes = Notes::where('form_type', $form_type)
                ->where('form_number', $form_number)
                ->orderBy('time', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $notes,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function getCountApproval($username){
        try{
            $countPR = PurchaseRequestApproval::where('approver_by', $username)
                ->whereIn('approval_status', ['In Progress', 'Waiting Approval'])
                ->count();
            $countPO = PurchaseOrderApproval::where('approver_by', $username)
                ->whereIn('approval_status', ['In Progress', 'Waiting Approval'])
                ->count();
            $countRA = RegistrationAssetApproval::where('approver_by', $username)
                ->whereIn('approval_status', ['In Progress', 'Waiting Approval'])
                ->count();

            $res = [
                'pr' => $countPR,
                'po' => $countPO,
                'ra' => $countRA,
            ];
                
            return response()->json([
                'success' => true,
                'data' => $res,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    
    // Notes

    public static function approvalCM($cabang)
    {
        // pengecekan orang cabang atau dengan jabatan
         try {
            $result = \DB::connection('db2')
                ->table('tbluser as t')
                ->select(
                    't.username',
                    't.active',
                    't2.nm_depan',
                    't2.nm_belakang',
                    't2.email',
                    't3.kd_jabatan',
                    't3.nm_jabatan as jabatan',
                    't.fk_cabang_user',
                )
                ->join('tblkaryawan as t2', 't.fk_karyawan', '=', 't2.npk')
                ->join('tbljabatan as t3', 't2.fk_jabatan', '=', 't3.kd_jabatan')
                ->where('t3.nm_jabatan', 'Ka Area')
                ->where('t.fk_cabang_user', $cabang)
                ->where('t.active', true)
                ->limit(1)
                ->get();
                

            return $result;
        } catch (QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Internal Server Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
