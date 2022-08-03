<?php
namespace App\Services;

use App\Model\BmVisitTrackMstSizeType;
use Illuminate\Support\Facades\DB;

class BmVisitTrackSettingSizeService {
    protected $model;

    public function __construct(BmVisitTrackMstSizeType $model) {
        $this->model = $model;
    }

    public function getAllData($request) {

        $columns = array(
            0 =>'id',
            1 =>'code',
            2 =>'name',
            3 =>'description',
        );

        $data = DB::table("view_bm_visit_track_mst_size_types");

        $data = $data->where('debtor_acct', auth()->user()->tenant_code);
        
        $totalData = $data->count();
        $totalFiltered = $totalData;

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $columns[$request->input('order.0.column')];
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value') ? $request->input('search.value') : null;

        if(!is_null($search)) {
            $data = $data->where('code', 'LIKE', "%$search%")->orWhere('name', 'LIKE', "%$search%")
            ->orWhere('description', 'LIKE', "%$search%")->orWhere('created_at', 'LIKE', "%$search%")
            ->orWhere('created_by', 'LIKE', "%$search%")->orWhere('updated_at', 'LIKE', "%$search%")
            ->orWhere('updated_by', 'LIKE', "%$search%");
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
                    "code" => $u->code,
                    "name" => ucwords($u->name),
                    "description" => ucwords($u->description),
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
                $nestedData['code'] = ucwords($u->code);
                $nestedData['name'] = ucwords($u->name);
                $nestedData['description'] = ucwords($u->description);
                // $nestedData['status'] = strtoupper($u->status);
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
                $proccess = $this->model->createNew($data);
            } else {
                $find = $this->model->find($id);

                if(!$find) throw new \Exception("Data not found", 404);

                unset($data['code']);
                $data['updated_at'] = date("Y-m-d H:i:s", time());
                $data['updated_by'] = auth()->user()->tenant_code;

                $proccess = $this->model->where('id', $id)->update($data);
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

}