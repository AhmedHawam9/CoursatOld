<?php

namespace App\DataTables\Admin;

use App\Student_Typecollege;
use App\TypecollegeJoin;
use Carbon\Carbon;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Http\Request as HttpRequest;

class TypeCollegeSubscriptionDataTable extends DataTable
{
    // protected $view = "dashboard.students.college_subscriptions.";
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

            // ->addColumn('action', 'dashboard.typecollege_subscribtions.action')

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
            })

            ->filter(function ($query) use ($request) {
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
                $query->whereHas('student', function ($q) use ($request) {

                    $q
                        ->when($request->university_id != null && $request->university_id != 0, function ($q) use ($request) {
                            return $q->where('university_id', (int)$request->university_id);
                        })
                        ->when($request->college_id != null && $request->college_id != 0, function ($q) use ($request) {
                            return $q->where('college_id', (int)$request->college_id);
                        })
                        ->when($request->division_id != null && $request->division_id != 0, function ($q) use ($request) {
                            return $q->where('division_id', (int)$request->division_id);
                        })
                        ->when($request->section_id != null && $request->section_id != 0, function ($q) use ($request) {
                            return $q->where('section_id', (int)$request->section_id);
                        })
                        ->when($request->subscription_type != null, function ($q) use ($request) {
                            return $q->where('type', (int)$request->subscription_type);
                        })
                        ->when($request->type_college_id != null && $request->type_college_id != 0, function ($q) use ($request) {
                            return $q->whereHas('stutypescollege', function ($typeq) use ($request) {
                                return $typeq->where('typescollege.id', (int)$request->type_college_id);
                            });
                        });
                });
            })
            ->rawColumns([

                'action',
            ]);
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Admin/TypeCollegeSubscriptionDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Student_Typecollege $model)
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
        return 'Admin/TypeCollegeSubscription_' . date('YmdHis');
    }
}
