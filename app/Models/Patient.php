<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'birth_date',
        'gender',
        'height',
        'weight',
        'user_id',
    ];

    protected $appends = [
        'des_gender',
        'recomendations',
    ];

    public function getDesGenderAttribute()
    {
        switch ($this->gender) {
            case 'M':
                $des_gender = "Masculino";
                break;
            case 'F':
                $des_gender = "Femenino";
                break;
            default:
                $des_gender = "Otro";
                break;
        }
        return $des_gender;
    }

    public function getRecomendationsAttribute(){
        $message = "";
        $age = Carbon::parse($this->birth_date)->age;
        switch (true) {
            case ($age < 18):
                $message = $this->setMessageRecomendationsYounger();
                break;
            case ($age >= 18):
                $message = $this->setMessageRecomendationsOlder();
                break;
        }
        return $message;
    }

    private function setMessageRecomendationsYounger(){
        return "Hola {$this->name} eres {$this->setAdjectiveGender()} joven muy saludable, te recomiendo salir a jugar al aire libre durante {$this->findFibonacciSmaller()} horas diarias";
    }

    private function setMessageRecomendationsOlder(){
        return "Hola {$this->name} eres una persona muy saludable, te recomiendo comer {$this->setAdjectiveWeight()} y salir a correr {$this->getNumberRound()} km diarios";
    }

    private function setAdjectiveGender(){
        switch ($this->gender) {
            case 'M':
                return 'un';
                break;
            case 'F':
                return 'una';
                break;
            default:
                return 'un(a)';
                break;
        }
    }

    private function setAdjectiveWeight(){
        switch (true) {
            case ($this->weight < 30):
                return 'menos';
                break;
            case ($this->weight >= 30):
                return 'más';
                break;
        }
    }

    private function findFibonacciSmaller(){
        if($this->height == 0 || $this->height == 1)
            return $this->height;

        $a = 0;
        $b = 1;

        while($b <= $this->height) {
            $temp = $a;
            $a = $b;
            $b += $temp;
        }

        return $a;
    }

    private function getNumberRound(){
        $year = substr(Carbon::parse($this->birth_date)->year, -2);

        return round(sqrt($year), 2);
    }
}
