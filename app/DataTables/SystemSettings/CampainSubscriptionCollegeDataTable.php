<?php

namespace App\DataTables\SystemSettings;

// use App\Models\SystemSettings/CampainSubscriptionCollegeDataTable;
use App\Student_Type;
use App\Student_Typecollege;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CampainSubscriptionCollegeDataTable extends DataTable
{
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', 'systemsettings/campainsubscriptioncollegedatatable.action')

            ->editColumn("student_name", function ($query) {
                return $query->student->name ?? "";
            })
            ->editColumn("student_phone", function ($query) {
                return $query->student->phone ?? "" ?? "";
            })
            ->editColumn("center_name", function ($query) {
                return $query->typescollege ? ($query->typescollege->center->name ?? "المنصه العامه") : "";
            })
            ->editColumn("teacher_name", function ($query) {
                return $query->typescollege ? ($query->typescollege->doctor->name ?? "") : "";
            })
            ->editColumn("year_name", function ($query) {
                return $query->typescollege ? ($query->typescollege->division->name_ar ?? " ") : "";
            })
            ->editColumn("subject_name", function ($query) {
                return $query->typescollege ? ($query->typescollege->subjectscollege->name_ar ?? " ") : "";
            })
            ->editColumn("course_name", function ($query) {
                return $query->typescollege->name_ar ?? "";
            })
            ->editColumn('created_at', function ($row) {
                if ($row->created_at != null) {
                    return Carbon::createFromFormat('Y-m-d H:i:s', $row->created_at)->format('Y-m-d');
                } else {
                    return 'no date';
                }
            })
            ->editColumn("admin_name", function ($query) {
                return $query->user->name ?? "";
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\SystemSettings/CampainSubscriptionCollegeDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Student_Typecollege $model)
    {
        return $model->newQuery()->whereHas('student', function ($studentq) {
            $studentq->where('college_id', $this->campain->college_id)
                ->where('university_id', $this->campain->university_id);
            // ->where([["category_id", $this->campain->category_id], ["category_id", "!=", null]])
            // ->where([["created_at", ">=", $this->campain->start_date], ["created_at", "<=", $this->campain->end_date]])
        });
    }

    /**
     * Optional method if you want to use html builder.
     *
     * @return \Yajra\DataTables\Html\Builder
     */
    public function html()
    {
        return $this->builder()
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->parameters([
                // 'dom' => 'Blfrtip',
                'order' => [0, 'desc'],
                'lengthMenu' => [
                    [10, 25, 50, -1], [10, 25, 50, 'all record']
                ],
                'buttons'      => ['export'],
            ]);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            ["data" => "student_name", "title" => 'اسم الطالب', 'exportable' => false, 'orderable' => false],
            ["data" => "student_phone", "title" => 'رقم الطالب', 'exportable' => false, 'orderable' => false],
            ["data" => "center_name", "title" => 'المنصه', 'exportable' => false, 'orderable' => false],
            ["data" => "teacher_name", "title" => 'الدكتور', 'exportable' => false, 'orderable' => false],
            ["data" => "year_name", "title" => 'الفرقه', 'exportable' => false, 'orderable' => false],
            ["data" => "subject_name", "title" => 'الماده', 'exportable' => false, 'orderable' => false],
            ["data" => "course_name", "title" => 'الكورس', 'exportable' => false, 'orderable' => false],
            ["data" => "created_at", "title" => 'تاريخ الانضمام', 'exportable' => false, 'orderable' => false],
            ["data" => "admin_name", "title" => 'الادمن', 'exportable' => false, 'orderable' => false],
            ["data" => "type_format", "title" => 'طريقه الاشتراك', 'exportable' => false, 'orderable' => false],

            //  ['data'=>'action','title'=>"الاعدادات",'printable'=>false,'exportable'=>false,'orderable'=>false,'searchable'=>false],
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'SystemSettings/CampainSubscriptionCollege_' . date('YmdHis');
    }
}
