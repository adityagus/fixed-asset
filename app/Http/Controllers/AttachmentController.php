<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

class AttachmentController extends Controller
{
  
  
    public function getFileList () {
      
      $form_number = request()->query('formNumber');
      $result = Attachment::where('form_number', $form_number)->get();
      
      
        return response()->json([
            'success' => true,
            'data' => $result
         ]);
    }
  
  
    public function uploadFile(Request $request): JsonResponse
  {
    // check file yang di append
    try {
      // purchase request, purchase order, asset registration
      $request->validate([
        'form_number' => 'required',
        'url_file' => 'required|file|mimes:pdf|max:10240', // max 10MB | pdf only
      ]);

      /**
       *  Get purchase request by form_number
       * Handle file upload
       * */


      // Handle file upload
      if ($request->hasFile('url_file')) {
        $file = $request->file('url_file');
        $filePath = $file->store($request->get('attachable_type'), 'public'); // Simpan di storage/app/public/purchase_requests
        // add request
        Attachment::create([
          'attachable_type' => $request->get('attachable_type'),
          'form_number' => $request->form_number,
          'size' => $file->getSize(),
          'url_file' => $filePath,
          'file_name' => $file->getClientOriginalName(),
          'uploaded_by' => $request->get('uploaded_by') ?? 'system',
        ]);
      }

      return response()->json([
        'success' => true,
        'message' => 'File uploaded successfully.'
      ]);
    } catch (ValidationException $e) {
      return response()->json([
        'success' => false,
        'errors' => $e->errors()
      ], 422);
    }
  }
  
  public function deleteFile(Request $request): JsonResponse
  {
    try {
      // querty param id
      
      $request->merge(['id' => $request->query('id')]);
      
      $request->validate([
        'id' => 'required|exists:attachments,id'
      ]);

      /**
       *  Get purchase request by form_number
       * Delete file reference
       * */

      $requestModel = Attachment::where('id', $request->id)->first();

      // Delete file reference
      $requestModel->delete();

      return response()->json([
        'success' => true,
        'form_number' => $requestModel->form_number,
        'message' => 'File reference deleted successfully.'
      ]);
    } catch (ValidationException $e) {
      return response()->json([
        'success' => false,
        'errors' => $e->errors()
      ], 422);
    }
  }
}
