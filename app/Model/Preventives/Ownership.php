<?php

namespace App\Model\Preventives;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Ownership extends Model
{
    
    public $table = 'pm_asset_ownerships';

    public $timestamps = false;

    public $fillable = [
        "asset_id", 'entity_project', "project_code", "location_id",
        "created_at", "created_by", "updated_at", "updated_by", "quantity", "asset_detail_id", "tenant_id",
    ];

    public function getAllData() {
        $data = DB::table("view_pm_asset_ownerships")
            ->where("entity_project", auth()->user()->entity_project)
            ->where('project_code', auth()->user()->project_no)
        ;
        return $data;
    }

    public function getDataAssetsForSelect($request)
    {
        $page = (int) $request->get('page');
        $resulCount = 10;
        $keyword = $request->get('keyword');

        $offset = ($page - 1) * $resulCount;
        $endCount = $offset + $resulCount;
        
        $results = DB::table("pm_assets")
        ->selectRaw("id, CONCAT(brand, ' - ', type, ' - ' , specification) AS text")
        ->where('status', '=', 'active')
        ->whereRaw("CONCAT(brand, ' - ', type, ' - ' , specification) LIKE '%$keyword%'");

        $count = $results->count();
        
        $morePages = $endCount > $count;

        return [
            "results" => $results->skip($offset)->take($resulCount)->get(),
            "pagination" => [
                "more" => $morePages,
            ]
        ];
    }

    public function getDataAssetDetailsForSelect($request)
    {
        $page = (int) $request->get('page');
        $resulCount = 10;
        $keyword = $request->get('keyword');

        $offset = ($page - 1) * $resulCount;
        $endCount = $offset + $resulCount;
        
        $results = DB::table("pm_asset_details")
        ->selectRaw("id, barcode AS text")
        ->where('pm_asset_id', '=', (int) $request->asset_id)
        ->where('status', '=', 'active')
        ->whereNull('is_assigned')
        // ->where('last_date_pm', '!=', null)
        ->whereRaw("barcode LIKE '%$keyword%'");
        
        $count = $results->count();
        
        $morePages = $endCount > $count;

        return [
            "results" => $results->skip($offset)->take($resulCount)->get(),
            "pagination" => [
                "more" => $morePages,
            ]
        ];
    }

    public function getDataLocationsForSelect($request)
    {
        $page = (int) $request->get('page');
        $resulCount = 10;
        $keyword = $request->get('keyword');

        $offset = ($page - 1) * $resulCount;
        $endCount = $offset + $resulCount;
        
        $results = DB::table("pm_locations")->distinct()
        ->selectRaw("id, name AS text");

        if(is_array($request->entity_project))
        {
            $results->whereIn('pm_locations.entity_project', $request->entity_project);
        } else {
            $results->where('pm_locations.entity_project', $request->entity_project);
        }

        if(is_array($request->project_code)) {
            $results->whereIn('pm_locations.project_code', $request->project_code);
        } else {
            $results->where('pm_locations.project_code', $request->project_code);
        }

        $results->whereRaw("name LIKE '%$keyword%'");
        
        $count = $results->count();
        
        $morePages = $endCount > $count;

        return [
            "results" => $results->skip($offset)->take($resulCount)->get(),
            "pagination" => [
                "more" => $morePages,
            ]
        ];
    }
}
