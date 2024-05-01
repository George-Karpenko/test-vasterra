<?php

namespace App\Services;

class GoogleDriveService
{
    protected $client;

    public function __construct($client)
    {
        $this->client = $client;
        $this->client->addScope(['https://www.googleapis.com/auth/drive', 'https://www.googleapis.com/auth/spreadsheets']);
    }

    function setDrivePermission($spreadsheetId)
    {   
        try {
            $drive = new \Google_Service_Drive($this->client);
            $drivePermission = new \Google_Service_Drive_Permission();
            $drivePermission->setType('user'); 
            // $drivePermission->setEmailAddress(env('GOOGLE_EMAIL_DRIVE_PERMISSION')); 
            $drivePermission->setEmailAddress('georgekarpenko1@gmail.com'); 
            $drivePermission->setRole('writer'); 
            $drive->permissions->create($spreadsheetId, $drivePermission);
        }
        catch(Exception $e) {
            // TODO(developer) - handle error appropriately
            echo 'Message: ' .$e->getMessage();
        }
    }
}
