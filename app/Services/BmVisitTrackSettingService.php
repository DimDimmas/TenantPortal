<?php
namespace App\Services;

use App\Model\BmVisitTrackSetting;
use Illuminate\Support\Facades\DB;

class BmVisitTrackSettingService {
    protected $bmVisitTracSettingModel;

    public function __construct(BmVisitTrackSetting $bmVisitTracSettingModel)
    {
        $this->bmVisitTracSettingModel = $bmVisitTracSettingModel;
    }

    public function getAllData($request) {
        $columns = array(
            0 =>'id',
            1 =>'type',
            2 => 'size_type',
            3 =>'name',
            4 =>'description',
            5 =>'status',
            6 =>'value',
            // 6 => 'action',
            // 5 => 'created_at',
            // 6 => 'created_by',
            // 7 => 'updated_at',
            // 8 => 'updated_by',
        );

        $data = $this->bmVisitTracSettingModel->with('size_type')->where('debtor_acct', auth()->user()->tenant_code);
        
        $totalData = $data->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value') ? $request->input('search.value') : null;

        if(!is_null($search)) {
            $data = $data->where('type', 'LIKE', "%$search%")->orWhere('name', 'LIKE', "%$search%")
            ->orWhere('description', 'LIKE', "%$search%")->orWhere('created_at', 'LIKE', "%$search%")
            ->orWhere('created_by', 'LIKE', "%$search%")->orWhere('updated_at', 'LIKE', "%$search%")
            ->orWhere('updated_by', 'LIKE', "%$search%")
            ->orWhereHas('size_type', function($query) use ($search) {
                $query->where('name', 'LIKE', "%$search%");
            });
        }

        $newData = array();
        $data = $data->offset($start)
        ->limit($limit)
        ->orderBy($order,$dir)
        ->get();

        if(!empty($data)){
            $angka =$start+1;
            foreach ($data as $u){
                $json = [
                    "id" => $u->id,
                    "type" => $u->type,
                    "size_type" => $u->size_type ? $u->size_type->name : null,
                    "bm_visit_track_mst_size_type_id" => $u->size_type ? $u->size_type->id : null,
                    "name" => ucwords($u->name),
                    "description" => ucwords($u->description),
                    "status" => $u->status,
                    "value" => (int) $u->value,
                ];
                // $edit = '<a href="javascript:void(0);" class="btn btn-sm btn-warning" title="Edit : '.$u->id.'" onclick="event.preventDefault();update('.$json.');"><i class="fa fa-edit"></i> </a>';
                // $delete = '<a href="javascript:void(0);" class="btn btn-sm btn-danger sw-delete" title="'.$u->id.'" onclick="event.preventDefault();delete('.$u->id.');"><i class="fa fa-trash-o"></i> </a>';
                // // $view = '<a href="'.$url_view.'" class="btn btn-sm btn-info" title="View : '.$u->id.'"><i class="fas fa-eye"></i> </a>';

                $edit = "<button type='button' class='btn btn-warning btn-sm' onclick='update(`". json_encode($json) ."`)'>
                    <i class='fa fa-edit'></i>
                </button>";

                $delete = "<button type='button' class='btn btn-danger btn-sm sw-delete' data-id='". $u->id ."'>
                    <i class='fa fa-trash-o'></i>
                </button>";

                // $nestedData['angka'] = $angka;
                $nestedData['id'] = $u->id;
                $nestedData['type'] = ucwords($u->type);
                $nestedData['size_type'] = $u->size_type ? ucwords($u->size_type->name) : null;
                $nestedData['name'] = ucwords($u->name);
                $nestedData['description'] = ucwords($u->name);
                $nestedData['status'] = strtoupper($u->status);
                $nestedData['value'] = (int) $u->value;
                $nestedData['action'] = $edit." ".$delete;
                $newData[] = $nestedData;

                $angka++;
            }
        }

        $json_data = array(
            "draw"            => intval($request->input('draw')),
            "recordsTotal"    => intval($totalData),
            "recordsFiltered" => intval($totalFiltered),
            "data"            => $newData
        );

        return $json_data;
    }

    public function createOrUpdate($data, $id) {
        $results = [];
        $proccess = null;
        DB::beginTransaction();
        try {

            if(is_null($id)) {
                $proccess = $this->create($data);
            } else {
                $find = $this->bmVisitTracSettingModel->find($id);

                if(!$find) throw new \Exception("Data not found", 404);

                $proccess = $this->bmVisitTracSettingModel->updateExists($find, $data);
            }

            if(!$proccess) throw new \Exception("Something went wrong,  on proccess data", 500);

            DB::commit();
            return $results = [
                "error" => false,
                "code" => 200,
                "message" => "Data has been saved.",
                "errors" => null
            ];
        } catch (\Exception $e) {
            DB::rollback();
            $results = [
                "error" => true,
                "code" => $e->getCode(),
                "message" => $e->getMessage(),
                "errors" => null
            ];
        }
        return $results;
        
    }

    public function create($data) {
        $check = $this->bmVisitTracSettingModel->where('debtor_acct', auth()->user()->tenant_code)->where('type', $data['type'])
            ->where('bm_visit_track_mst_size_type_id', $data['bm_visit_track_mst_size_type_id'])->count();
        if($check > 0) {
            throw new \Exception("Data already exist", 403);
        }
        return $this->bmVisitTracSettingModel->createNew($data);
    }

}