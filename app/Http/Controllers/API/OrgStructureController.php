<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\CompanyStructureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrgStructureController extends Controller
{
    /**
     * @var CompanyStructureService
     */
    protected $companyStructureService;

    /**
     * OrgStructureController constructor.
     * @param  CompanyStructureService  $companyStructureService
     */
    public function __construct(CompanyStructureService $companyStructureService) {
        $this->companyStructureService = $companyStructureService;
    }

    public function index(Request $request) {
        $type = $request->get('type', 'json');
        $structure = $this->companyStructureService->getStructure($type);
        $response = response()->json($structure, JsonResponse::HTTP_OK);

        if ($type === 'xml') {
            $response = response()->streamDownload(function () use ($structure) {
                echo $structure;
            },'structure.xml', ['Content-Type' => 'text/xml']);
        }

        return $response;
    }

    /**
     * Main endpoint store organization structure json
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request) {
        $structure = $request->all();

        if ($request->has('file')) {
            $structure = $request->get('file');
        }

        $validator = $this->validator($structure);

        if ($validator->fails()) {
            return response()->json(
                ['messages' => $validator->getMessageBag()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        try {
            $this->companyStructureService->storeStructure($structure['structure']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }

        $structure = $this->companyStructureService->getStructure('json');

        return response()->json($structure, JsonResponse::HTTP_OK);
    }

    protected function validator(array $data) {
        return Validator::make($data, [
            'structure' => 'required|array'
        ]);
    }
}
