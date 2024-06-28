<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ActivityLogController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        if ($request->ajax()) {
            return self::dataTable();
        }
        return view('activity_log.index');
    }

    public static function dataTable()
    {
        $data = ActivityLog::with('user')->get();
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('user_nama', function ($row) {
                return $row->user->nama;
            })
            ->addColumn('created_at', function ($row) {
                return Carbon::parse($row->created_at)->format('d-m-Y H:i:s');
            })
            ->setRowAttr([
                'data-searchable' => 'true'
            ])
            ->make(true);
    }
}
