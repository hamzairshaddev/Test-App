<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessEmail;
use Illuminate\Http\Request;
use App\Services\QuotesService;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\QuotesRequest;

class QuotesController extends Controller
{
    public function __construct(private QuotesService $quotesService){}

    public function getCompanies(): object
    {
        $companies = DB::table('companies')->select('symbol', 'name')->get();

        return response()->json($companies);
    }

    public function sendRecieveHistoricalQuotes(QuotesRequest $request): object
    {
        $requestData = $request->all();

        // Get Data From Api based on parameters
        $data = $this->quotesService->getData(['symbol' => $requestData['symbol']]);
        $data['requestData'] = $requestData;

        // Send Email
        ProcessEmail::dispatch(json_encode($data))->onQueue('emails');

        return response()->json($data);
    }
}
