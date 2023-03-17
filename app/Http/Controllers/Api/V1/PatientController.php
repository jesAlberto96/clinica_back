<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Requests\StorePatientRequest;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ResponseController;
use App\Repositories\Patient\PatientsRepository;
use App\Models\Candidato;

class PatientController extends Controller
{
    private $response;

    public function __construct(){
        $this->response = new ResponseController;
        $this->patientsRepository = new PatientsRepository;
    }
    
    public function index()
    {
        return $this->patientsRepository->getAll();
    }

    public function store(StorePatientRequest $request)
    {
        try {
            return $this->response->sendResponse(true, $this->patientsRepository->create([
                'user_id' => auth()->user()->id,
                'name' => $request->input('name'),
                'birth_date' => $request->input('birth_date'),
                'gender' => $request->input('gender'),
                'height' => $request->input('height'),
                'weight' => $request->input('weight'),
            ]), [], 201);
        } catch (Exception $ex) {
            return $this->response->sendError(["Ocurrio un error inesperado"], 500);
        }
    }

    public function show($id)
    {
        $response = $this->patientsRepository->getByID($id);

        if (is_null($response)){
            return $this->response->sendError(["Recurso no encontrado"], 404);
        }
        return $this->response->sendResponse(true, $response);
    }

    public function destroy($id)
    {
        $this->patientsRepository->delete($id);

        return $this->response->sendResponse(true, "Recurso eliminado");
    }
}
