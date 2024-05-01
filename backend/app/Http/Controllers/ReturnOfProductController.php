<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReturnOfProduct;
use App\Models\ReturnOfProduct;
use App\Services\GoogleDriveService;
use App\Services\GoogleSheetService;
use Illuminate\Http\Request;

class ReturnOfProductController extends Controller
{

    private $columnTitles = [
        'first_name' => 'Имя',
        'last_name' => 'Фамилия',
        'phone' => 'Телефон',
        'application_text' => 'Текст заявки',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ReturnOfProduct::orderBy('id','desc')->get();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReturnOfProduct $request)
    {
        $validated = $request->validated();

        $adaptToGoogleForm = [];
        $adaptToGoogleForm []= [];
        foreach ($validated as $key => $value) {
            $adaptToGoogleForm[0] []= $this->columnTitles[$key];
        }
        $adaptToGoogleForm []= [];
        foreach ($validated as $key => $value) {
            $adaptToGoogleForm[1] []= $value;
        }
        
        $googleSheetService = new GoogleSheetService();

        $spreadsheet = $googleSheetService->create('Заявка на возврат товара');
        $googleSheetService->appendSheetRow($spreadsheet->spreadsheetId, $adaptToGoogleForm);

        $googleDriveService = new GoogleDriveService($googleSheetService->getClient());
        $googleDriveService->setDrivePermission($spreadsheet->spreadsheetId);


        $model = new ReturnOfProduct;
        $model->url = $spreadsheet->spreadsheetUrl;
        $model->save();
        return $spreadsheet->spreadsheetUrl;
    }
}

