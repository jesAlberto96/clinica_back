<?php

namespace App\Repositories\Patient;

use App\Models\Patient;
use App\Http\Resources\PatientResource;
use App\Http\Resources\PatientCollection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PatientsRepository
{
    public function getAll() 
    {
        return new PatientCollection(Patient::paginate(2));
    }

    public function getByID($id) 
    {
        return Patient::where('id', $id)->first();
    }

    public function create($data)
    {
        return Patient::create($data);
    }

    public function delete($orcid)
    {
        return Patient::where('id', $orcid)->delete();
    }
}