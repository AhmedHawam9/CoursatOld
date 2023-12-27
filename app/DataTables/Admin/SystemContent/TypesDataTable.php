<?php

namespace App\DataTables\Admin\SystemContent;

use App\Type;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class TypesDataTable extends DataTable
{
    protected $view = "dashboard.types-basic_courses.";
    /**
     * Build DataTable class.
     *
     * @param mixed $query Results from query() method.
     * @return \Yajra\DataTables\DataTableAbstract
     */
    public function dataTable($query, Request $request)
    {
        return datatables()
            ->eloquent($query)
            ->addColumn('action', $this->view . 'action')
            ->addIndexColumn()
            ->setRowId('id')
            ->editColumn('name_ar', function ($query) {
                return '<a href="' . route('subtypes', $query->id) . '">' .  $query->name_ar . '</a>';
            })
            ->addColumn('user', function ($query) {
                if ($query->user) {
                    return $query->user->name;
                } else {
                    return "غير محدد";
                }
            })
            ->addColumn('center', function ($query) {
                if ($query->center) {
                    return  $query->center->name ?? 'المنصه العامه';
                } else {
                    return 'المنصه العامه';
                }
            })
            ->addColumn('subject', function ($query) {
                if ($query->subject) {
                    return $query->subject->name_ar;
                } else {
                    return "غير محدد";
                }
            })
            ->addColumn('year', function ($query) {
                if ($query->year) {
                    return $query->year->year_ar;
                } else {
                    return "غير محدد";
                }
            })
            ->editColumn('created_at', function ($query) {
                return Carbon::parse($query->created_at)->format('Y-m-d');
            })
            ->rawColumns([
                'action',
                'name_ar',
                'user',
                'center',
                'subject',
                'year',
                'created_at',
            ])->filter(function ($query) use ($request) {
                //search
                if (
                    $request->has('search') && isset($request->input('search')['value'])
                    && !empty($request->input('search')['value'])
                ) {
                    $searchValue = $request->input('search')['value'];
                    $query
                        ->where(function ($query) use ($searchValue) {
                            $query->where('name_ar', 'LIKE', "%$searchValue%")
                                ->orWhere('id', 'LIKE', "%$searchValue%")
                                ->orWhereDate('created_at', 'LIKE', "%$searchValue%");
                        })
                        ->orwhereHas('user', function ($q) use ($searchValue) {
                            $q->where('name', 'LIKE', "%$searchValue%");
                        })
                        ->orwhereHas('subject', function ($q) use ($searchValue) {
                            $q->where('name_ar', 'LIKE', "%$searchValue%");
                        })
                        ->orwhereHas('year', function ($q) use ($searchValue) {
                            $q->where('year_ar', 'LIKE', "%$searchValue%");
                        })
                        ->orwhereHas('center', function ($q) use ($searchValue) {
                            $q->where('name', 'LIKE', "%$searchValue%");
                        });
                        // ->orWhereDoesntHave('center');
                }
                //query
                $query
                    ->when($request->stage_id != null && $request->stage_id != 0, function ($q) use ($request) {
                        return $q->where('stage_id', $request->stage_id);
                    })
                    ->when($request->subjects_id != null && $request->subjects_id != 0, function ($q) use ($request) {
                        return $q->where('subjects_id', $request->subjects_id);
                    })
                    ->when($request->years_id != null && $request->years_id != 0, function ($q) use ($request) {
                        return $q->where('years_id', $request->years_id);
                    })
                    ->when($request->center_id != null && $request->center_id != 0, function ($q) use ($request) {
                        return  $q->where('center_id', $request->center_id);
                    })
                    ->when($request->center_id != null && $request->center_id == 0, function ($q) use ($request) {
                        return  $q->orWhereDoesntHave('center');
                    });
                    // ->when($request->month != null && $request->month != 0, function ($q) use ($request) {
                    //     $next_month = Carbon::parse($request->month)->addMonth()->format('Y-m');
                    //     return $q->whereMonth('created_at', '>=',  $next_month)->whereMonth('created_at', '<=',  $request->month);
                    // });
            });
    }

    /**
     * Get query source of dataTable.
     *
     * @param \App\Models\Admin/SystemContent/TypesDataTable $model
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query(Type $model)
    {
        if (Auth::user() && Auth::user()->isAdmin == 'admin') {
            return $model->newQuery()->orderBy('created_at', 'desc');
        } else if (Auth::user() && Auth::user()->is_student == config('project_types.auth_user_is_student.basic_lecturer')) {
            return $model->newQuery()->orderBy('created_at', 'desc')->where('user_id', Auth::user()->id);
        } else if (Auth::user() && Auth::user()->is_student == config('project_types.auth_user_is_student.center') && Auth::user()->category_id = config('project_types.system_category_type.category_id_basic')) {
            return $model->newQuery()->orderBy('created_at', 'desc')->where('center_id', Auth::user()->id);
        } else {
            return $model->newQuery();
        }
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
            ->dom('Bfrtip')
            ->orderBy(1);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        return [
            Column::make('id'),
            Column::make('name_ar')->title('الدوره االتعلميه الشهريه'),
            Column::make('user')->title('المدرس'),
            Column::make('center')->title('نوع المنصة'),
            Column::make('subject')->title('الماده'),
            Column::make('year')->title('السنه'),
            Column::make('created_at')->title('تاريخ إنشاء الكورس'),
            Column::computed('action')
                ->title('الاعدادات')
                ->exportable(false)
                ->printable(false)
                ->width(60)
                ->addClass('text-center'),
        ];
    }

    /**
     * Get filename for export.
     *
     * @return string
     */
    protected function filename()
    {
        return 'Admin/SystemContent/Types_' . date('YmdHis');
    }
}
