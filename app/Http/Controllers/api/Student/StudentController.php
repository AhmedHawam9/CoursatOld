<?php

namespace App\Http\Controllers\api\Student;

use App\Http\Controllers\Controller;
use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    use ApiTrait;

    public function switchCenter(Request $request)
    {
        $user = auth()->user();
        $rules = [
            "key" => "required|integer",//|between:1,2
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->first(), 400);
        }
        $user->is_public_platform_or_private_platform = (int)$request->key;

        $user->save();
        return $this->successResponse("User was updated");
    }

    public function change_online_status(Request $request)
    {

        try {
            $user = auth()->user();
            $rules = [
                "is_online" => "required|integer",//|between:1,0
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->first(), 400);
            }

            $user->update([
                "is_online" => (int)$request->is_online,
                "online_date" => (int)$request->is_online == 1 ? now() : null,
                "offline_date" =>  (int)$request->is_online == 0 ?  now() : null,
            ]);
            return $this->successResponse("User was updated");
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 400);
        }
    }


    public function fetch_online_status(Request $request)
    {

        try {
            $user = auth()->user();

            $online_status = (object)[
                "is_online" => $user->is_online,
                "online_date" => $user->online_date,
                "offline_date" => $user->offline_date,
            ];

            return $this->dataResponse("User status returned successfully", $online_status);
        } catch (\Throwable $th) {
            return $this->errorResponse($th->getMessage(), 400);
        }
    }
}
