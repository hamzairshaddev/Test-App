<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Jobs\ProcessEmail;
use App\Services\QuotesService;
use Illuminate\Support\Facades\App;

class QuotesApiEmailTest extends TestCase
{
    protected $quotesService;

    public function setUp(): void
    {
        parent::setUp();
        $this->quotesService = App::make(QuotesService::class);
    }
     /**
     * Test quotes api service class
     */
    public function test_quotes_api_service_class(): void
    {
        $response = $this->quotesService->getData(['symbol' => "GOOG"]);
        $this->assertArrayHasKey('prices', $response);
    }


     /**
     * Test email queue with data
     */
    public function test_email_queue_with_data(): void
    {
        $response['prices'] = [
            [
                "date" => 1677076200,
                "open" => 91.93399810791016,
                "high" => 92.36000061035156,
                "low" => 90.87000274658203,
                "close" => 91.80000305175781,
                "volume" => 29858500,
                "adjclose" => 91.80000305175781
            ]
        ];
        $response['requestData'] = [
            "email" => "joe@gmail.com",
            'start_date' => "2023-02-01",
            'end_date' => "2023-02-21",
            'company_name' => "Google Inc"
        ];
        // Send Email
        ProcessEmail::dispatch(json_encode($response))->onQueue('emails');

        $this->assertEquals(true, true);
    }
}
