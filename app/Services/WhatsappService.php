<?php

namespace App\Http\Services;

use App\Traits\ApiTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WhatsappService
{
    use ApiTrait;
    protected $apiService;
    public function __construct(ApiMethodsService $apiService)
    {
        $this->apiService = $apiService;
    }
    public function send_whatsapp(Request $request){
        try{

            $apiUrl = 'crazyidea.online:3001/api/sendText';

            $headers = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ];


            $response = $this->apiService->withHeaders($headers)->postApiData($apiUrl, $request->all());
            $responseData = $response->json();
            // dd($response);

            $message = "send_whatsapp";

        return $this->dataResponse($message, $responseData, 200);
        }catch(\Exception $ex){
            return $this->returnException($ex->getMessage(),500);
        }
    }

}
