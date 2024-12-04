<?php
namespace App\Services\Depositor;

use App\Models\Organization;
use Illuminate\Support\Facades\Http;

class Summary {

    public $data;
    private $gpt_key;
    public $message;
    public $organization;

    public function __construct($organization) {
        $this->data = false;
        $this->organization = $organization;
        $this->setApiKey();
        $this->setMessage();
    }

    public function generate() {
        $response = Http::withHeaders($this->getHeaders())
            ->post($this->url(), [
                "model" => "gpt-3.5-turbo",
                "messages" => $this->message
        ]);
        if ($response->successful()) {
            $data = $response->object();
            return $data->choices[0]->message->content;
        } 

        return false;
    } 

    protected function setApiKey() {
        $this->gpt_key = config('services.chatgpt.api_key');
    }

    public function getHeaders() {
        return [
            "Content-Type" => "application/json",
            "Authorization" => "Bearer ". $this->gpt_key
        ];
    }

    public function url() {
        return "https://api.openai.com/v1/chat/completions";
    }
    
    public function setMessage() {
        $this->message = [
                    [
                    "role" => "user", 
                    "content" => $this->content()
                    ]
            ];
    }

    public function content() {
        return "I need you to act as the sales closer expert who started a company and is asked to write a professional company summary with the following information name= ".$this->organization->name.", address=".$this->organization->demographicData->address1.", province=".$this->organization->demographicData->province.", city= ".$this->organization->demographicData->city.", website= ".$this->organization->demographicData->website.", industry= ".naics_description($this->organization->naics_code_id).", total potential yearly deposit= ".deposit_band($this->organization->potential_yearly_deposit_id).". Summary should not be above 100 words, you should not mention anything about you.";
    }
}