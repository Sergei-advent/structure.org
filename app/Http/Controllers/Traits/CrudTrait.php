<?php

namespace App\Http\Controllers\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;

trait CrudTrait {

    /**
     * @var Model
     */
    protected $model;

    /**
     * @var JsonResource
     */
    protected $resource;

    /**
     * @var string|null
     */
    protected $syncEntity = null;


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request) {
        $collection = $this->model::all();

        if ($request->has('search')) {
            $collection = $this->model::where('name', 'ilike', '%' . $request->get('search') . '%')->get();
        }

        $collection = $this->resource::collection($collection);

        return response()->json($collection, JsonResponse::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        return $this->storeOrUpdateInDataBase($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $employee = $this->resource::make($this->model::findOrFail($id));

        return response()->json($employee, JsonResponse::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param $id
     * @return JsonResponse
     */
    public function update(Request $request, $id)
    {
        $this->model::findOrFail($id);

        return $this->storeOrUpdateInDataBase($request, $id, 'sync');
    }

    /**
     * @param  Request  $request
     * @param  null  $id
     * @param  string  $syncMethod
     * @return JsonResponse
     */
    protected function storeOrUpdateInDataBase(Request $request, $id = null, $syncMethod = 'attach') {
        $params = $request->all();

        $validator = $this->validator($params);

        if ($validator->fails()) {
            return response()->json(
                ['messages' => $validator->getMessageBag()],
                JsonResponse::HTTP_BAD_REQUEST
            );
        }

        if (isset($params['other_information'])) {
            if (count($params['other_information'])) {
                $params['other_information'] = json_encode($params['other_information']);
            } else {
                $params['other_information'] = null;
            }

        }

        $resource = $this->model::updateOrCreate(['id' => $id], $params);

        if ($this->syncEntity) {
            $syncField = $this->syncEntity;
            $syncValue = $request->get($syncField);

            if ($syncValue) {
                $attachValue = [];
                foreach ($syncValue as $value) {
                    $attachValue[$value['id']] = ['position_id' => isset($value['position_id']) ? $value['position_id'] : null];
                }

                $resource->$syncField()->$syncMethod($attachValue);
            }

        }

        return response()->json($this->resource::make($resource), JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $resource = $this->model::findOrFail($id);

        if ($this->syncEntity) {
            $syncField = $this->syncEntity;

            $resource->$syncField()->detach();
        }

        $resource->delete();

        return response()->json(null, JsonResponse::HTTP_NO_CONTENT);
    }

    /**
     * Validate request
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data) {
        return Validator::make($data, [
            'name' => 'required|string'
        ]);
    }
}