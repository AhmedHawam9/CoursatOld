<?php

namespace App\DataTables\Admin;

use App\Models\PointHistory;
use App\Models\PointRequest;
use App\Models\Reel;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class PointHistoryDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn("student_name",function($query){
                return $query->user->name ?? "";
            })

            // ->addColumn('action', 'dashboard.point_requests.action')
            ->rawColumns([

                'action',
            ]);
    }
    /**
     * Get the query source of dataTable.
     */
    public function query(PointHistory $model): QueryBuilder
    {
        return $model->newQuery()->orderBy("id","desc");
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
        ->columns($this->getColumns())
        ->minifiedAjax()
        ->parameters([
            'dom' => 'Blfrtip',
            'order' => [0, 'desc'],
            'lengthMenu' => [
                [10,25,50,-1],[10,25,50,'all record']
            ],
       'buttons'      => ['export'],
   ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            ["data" => "id" ,"title" => 'ID','orderable'=>false],
            ["data" => "student_name" ,"title" => 'اسم الطالب','orderable'=>false],
            ["data" => "points" ,"title" => 'النقاط','orderable'=>false],
            ["data" => "type_format" ,"title" => 'النوع','orderable'=>false],

            // ['data'=>'action','title'=>"actions",'printable'=>false,'exportable'=>false,'orderable'=>false,'searchable'=>false],
          ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Reel_' . date('YmdHis');
    }
}
