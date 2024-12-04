<?php

namespace App\Http\Controllers;

use App\Services\MTService;
use Illuminate\Http\Request;

class ProcessFileContent extends Controller
{
    protected $mtService;

    public function __construct(MTService $mtService)
    {
        $this->mtService = $mtService;
    }

    public function storeFromTxtFile(Request $request)
    {
        $file = $request->file('file');
        $message = file_get_contents($file->getRealPath());
        $data = $this->mtService->processMessage($message);

        return response()->json(['message' => 'File processed successfully','data'=>$data]);
    }
}
