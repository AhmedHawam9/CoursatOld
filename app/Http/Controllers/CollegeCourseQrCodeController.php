<?php

namespace App\Http\Controllers;

use App\DataTables\Admin\PatchDataTable;
use App\DataTables\Admin\QrCodeDataTable;
use App\DataTables\Admin\TypeCollegePatchDataTable;
use App\Http\Controllers\Controller;
use App\Models\Course\Course;
use App\Models\Patch;
use App\Models\QrCode as QrCodeModel;
use App\Services\QrcodeService;
use App\Traits\ApiTrait;
use App\Type;
use App\TypesCollege;

use App\User;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class CollegeCourseQrCodeController extends Controller
{
    use ApiTrait;
    protected $view = 'dashboard.patches.';
    protected $route = 'patches.';

    protected  QrcodeService $qrcodeService;

    public function __construct(QrcodeService $qrcodeService)
    {
        $this->qrcodeService = $qrcodeService;
    }
    public function patch_index(TypeCollegePatchDataTable $dataTable, $id)
    {
        $course = TypesCollege::whereId($id)->firstorFail();
        $dataTable->id = $id;
        return $dataTable->render($this->view . 'typecollege_patches', compact('id', 'course'));
    }

    public function index(QrCodeDataTable $dataTable, $id)
    {
        $patch = Patch::whereId($id)->firstorFail();
        $dataTable->id = $id;
        return $dataTable->render($this->view . 'typecollege_qrcodes', compact('id', 'patch'));
    }


    public function store(Request $request)
    {
        $data = $this->qrcodeService->store($request,TypesCollege::class,'TypesCollege');
        return  $data;
    }


}
