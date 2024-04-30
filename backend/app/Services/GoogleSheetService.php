<?php

namespace App\Services;

class GoogleSheetService
{
    protected $client;

    public function __construct()
    {
        $this->client = $this->init();
    }

    public function init(): \Google_Client
    {
        $client = new \Google_Client();

        $client->addScope(['https://www.googleapis.com/auth/drive', 'https://www.googleapis.com/auth/spreadsheets']);
        
        $client->setAuthConfig(config('google'));
        
        return $client;
    }

    function create($title)
    {   
        $this->client->useApplicationDefaultCredentials();
        $this->client->addScope(\Google\Service\Drive::DRIVE);
        $service = new \Google_Service_Sheets($this->client);
        
        try{
            $SpreadsheetProperties = new \Google_Service_Sheets_SpreadsheetProperties(); 
            $SpreadsheetProperties->setTitle($title);
            $Spreadsheet = new \Google_Service_Sheets_Spreadsheet(); 
            $Spreadsheet->setProperties($SpreadsheetProperties); 
            $response = $service->spreadsheets->create($Spreadsheet);

            return $response;
        }
        catch(Exception $e) {
            // TODO(developer) - handle error appropriately
            echo 'Message: ' .$e->getMessage();
        }
    }

    public function getClient(): \Google_Client
    {
        return $this->client;
    }
    
    public function sheet(): \Google_Service_Sheets
    {
        return new \Google_Service_Sheets($this->client);
    }

    public function appendSheetRow(string $sheetId, $values, string $range = 'Sheet1')
    {
        $service = $this->sheet();
        
        $body = new \Google_Service_Sheets_ValueRange([
            'values' => $values,
        ]);

        $params  = [
            'valueInputOption' => 'RAW',
        ];

        $this->client->useApplicationDefaultCredentials();
        $this->client->addScope(\Google\Service\Drive::DRIVE);

        $service->spreadsheets_values->append(
            $sheetId,
            $range,
            $body,
            $params,
        );
    }
}
