<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\StoreCompanyStructureService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrgStructureController extends Controller
{
    /**
     * @var StoreCompanyStructureService
     */
    protected $storeCompanyStructureService;

    /**
     * OrgStructureController constructor.
     * @param  StoreCompanyStructureService  $storeCompanyStructureService
     */
    public function __construct(StoreCompanyStructureService $storeCompanyStructureService) {
        $this->storeCompanyStructureService = $storeCompanyStructureService;
    }

    public function index()
    {
        //
    }

    /**
     * Main endpoint store organization structure json
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        $structure = $request->all();

        $validator = $this->validator($structure);

        if ($validator->fails()) {
            return response()->json(
                ['messages' => $validator->getMessageBag()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        try {
            $this->storeCompanyStructureService->storeStructure($structure['structure']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], JsonResponse::HTTP_BAD_REQUEST);
        }

        return response()->json([], JsonResponse::HTTP_OK);
    }

    public function update(Request $request)
    {
        //
    }

    public function destroy($id)
    {
        //
    }

    protected function validator(array $data) {
        return Validator::make($data, [
            'structure' => 'required|array'
        ]);
    }
}
