<?php

namespace App\DataTables\Admin;

use App\Student_Type;
use App\TypeJoin;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request as HttpRequest;

class TypeSubscriptionDataTable extends DataTable
{
    // protected $view = "dashboard.students.basic_subscriptions.";
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query, HttpRequest $request)
    {
        return datatables()
            ->eloquent($query)

            // ->addColumn('action', 'dashboard.type_subscribtions.action')

            ->editColumn("student_name", function ($query) {
                return $query->student->name ?? "";
            })
            ->editColumn("student_phone", function ($query) {
                return $query->student->phone ?? "" ?? "";
            })
            ->editColumn("center_name", function ($query) {
                return $query->type_course ? ($query->type_course->center->name ?? "المنصه العامه") : "";
            })
            ->editColumn("teacher_name", function ($query) {
                return $query->type_course ? ($query->type_course->user->name ?? "") : "";
            })
            ->editColumn("year_name", function ($query) {
                return $query->type_course ? ($query->type_course->year->year_ar ?? " ") : "";
            })
            ->editColumn("subject_name", function ($query) {
                return $query->type_course ? ($query->type_course->subject->name_ar ?? " ") : "";
            })
            ->editColumn("course_name", function ($query) {
                return $query->type_course->name_ar ?? "";
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
            })
            ->filter(function ($query) use ($request) {
                //search
                if (
                    $request->has('search') && isset($request->input('search')['value'])
                    && !empty($request->input('search')['value'])
                ) {
                    $searchValue = $request->input('search')['value'];
                    $query->where(function ($query) use ($searchValue) {
                        $query->whereHas('student', function ($q) use ($searchValue) {
                            $q->where('name', 'LIKE', "%$searchValue%")
                                ->orWhere('phone', 'LIKE', "%$searchValue%");
                        });
                    })->orwhereHas('stdcenters', function ($q) use ($searchValue) {
                        $q->where('name', 'LIKE', "%$searchValue%");
                    });
                }
                //filter
                $query
                    ->when($request->stage_id != null && $request->stage_id != 0, function ($q) use ($request) {
                        // dd($request->all());
                        return $q->whereHas('type_course.stage', function ($stuentd_q) use ($request) {
                            return $stuentd_q->where('stages.id', (int)$request->stage_id);
                        });
                    })
                    ->when($request->years_id != null && $request->years_id != 0, function ($q) use ($request) {
                        return $q->whereHas('type_course.year', function ($stuentd_q) use ($request) {
                            return $stuentd_q->where('years.id', (int)$request->years_id);
                        });
                    })
                    ->when($request->subject_id != null && $request->subject_id != 0, function ($q) use ($request) {
                        return $q->whereHas('type_course.subject', function ($subject_q) use ($request) {
                            return $subject_q->where('subjects.id', (int)$request->subject_id);
                        });
                    })
                    ->when($request->type_id != null && $request->type_id != 0, function ($q) use ($request) {
                        return $q->where('type_id', (int)$request->type_id);
                    })
                    ->when($request->subscription_type != null, function ($stuentd_q) use ($request) {
                        return $stuentd_q->where('type', (int)$request->subscription_type);
                    });
            })




            ->rawColumns([

                'action',
            ]);
    }
    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Admin/TypeSubscriptionDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Student_Type $model)
    {
        return $model->newQuery()->orderBy("id", "desc");
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
            ["data" => "teacher_name", "title" => 'المدرس', 'exportable' => false, 'orderable' => false],
            ["data" => "year_name", "title" => 'السنه', 'exportable' => false, 'orderable' => false],
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
        return 'Admin/TypeSubscription_' . date('YmdHis');
    }
}
