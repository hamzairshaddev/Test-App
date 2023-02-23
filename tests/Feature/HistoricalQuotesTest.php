<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class HistoricalQuotesTest extends TestCase
{
    /**
     *  Test that view page is working correctly.
     */
    public function test_view_page(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    /**
     *  Test that symbols data we are getting companies data from database is working correctly.
     */
    public function test_get_companies_data(): void
    {
        $this->json('GET', '/get-companies', ['Accept' => 'application/json'])
            ->assertStatus(200);
    }
    
    /**
     *  Test that submitting historical quotes request working with correct inputs
    */
    public function test_post_submit_data_with_correct_input(): void
    {
        $data = [
            "symbol" => "GOOG",
            "start_date" => "2023-02-01",
            "end_date" => "2023-02-21",
            "email" => "doe@example.com",
            "company_name" => "Google Inc"
        ];

        $this->json('POST', '/send-receive-historical-quotes', $data, ['Accept' => 'application/json'])
            ->assertStatus(200);
    }

    /**
     *  Test that submitting historical quotes request working with no input data
    */
    public function test_post_submit_data_with_no_inputs(): void
    {
        $data = [];

        $this->json('POST', '/send-receive-historical-quotes', $data, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The symbol field is required. (and 3 more errors)",
                "errors" => [
                    "symbol" => ["The symbol field is required."],
                    "start_date" => ["The start date field is required."],
                    "end_date" => ["The end date field is required."],
                    "email" => ["The email field is required."]
                ]
            ]);
    }


    /**
     *  Test that submitting historical quotes request working with wrong symbol input
    */
    public function test_post_submit_data_with_wrong_symbol(): void
    {
        $data = [
            "symbol" => "ZZZZZ",
            "start_date" => "2023-02-01",
            "end_date" => "2023-02-21",
            "email" => "doe@example.com",
            "company_name" => "Not Found"
        ];

        $this->json('POST', '/send-receive-historical-quotes', $data, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The selected symbol is invalid.",
                "errors" => [
                    "symbol" => ["The selected symbol is invalid."]
                ]
            ]);
    }

    /**
     *  Test that submitting historical quotes request working with start date greater than end date
    */
    public function test_post_submit_data_with_start_date_greater_than_end_date(): void
    {
        $data = [
            "symbol" => "GOOG",
            "start_date" => "2023-02-22",
            "end_date" => "2023-02-21",
            "email" => "doe@example.com",
            "company_name" => "Google Inc"
        ];

        $this->json('POST', '/send-receive-historical-quotes', $data, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The start date field must be a date before or equal to end date. (and 1 more error)",
                "errors" => [
                    "end_date" => ["The end date field must be a date after or equal to start date."],
                    "start_date" => ["The start date field must be a date before or equal to end date."]
                ]
            ]);
    }

    /**
     *  Test that submitting historical quotes request working with wrong email input
    */
    public function test_post_submit_data_with_wrong_email(): void
    {
        $data = [
            "symbol" => "GOOG",
            "start_date" => "2023-02-01",
            "end_date" => "2023-02-21",
            "email" => "test_test",
            "company_name" => "Google Inc"
        ];

        $this->json('POST', '/send-receive-historical-quotes', $data, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->assertJson([
                "message" => "The email field must be a valid email address.",
                "errors" => [
                    "email" => ["The email field must be a valid email address."]
                ]
            ]);
    }

}
