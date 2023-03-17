<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\Candidato;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDataBase;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class CandidatoTest extends TestCase
{
    use RefreshDataBase;

    public function test_post_success()
    {
        $this->createPermissions();
        $this->createRoles();

        $user_manager = $this->createUserManager();
        $response_auth = $this->getAuthToken();
        $user_agent = $this->createUserAgent();
        $payload = $this->setDataPost($user_manager, $user_agent);

        $this->json('post', '/api/lead', $payload, ['Authorization' => "Bearer {$response_auth['data']['token']}"])
        ->assertStatus(201)
        ->assertJsonStructure([
            'meta' => [
                'success',
                'errors',
            ],
            'data' => [
                'id',
                'name',
                'source',
                'created_by',
                'owner',
                'created_at',
                'updated_at',
            ],
        ]);
    }

    public function test_post_unauthorized()
    {
        $this->createPermissions();
        $this->createRoles();

        $user_manager = $this->createUserManager();
        $user_agent = $this->createUserAgent();
        $payload = $this->setDataPost($user_manager, $user_agent);

        $this->json('post', '/api/lead', $payload, ['Authorization' => "Bearer XXXXXXXXXXX"])
        ->assertStatus(401)
        ->assertJsonStructure([
            'meta' => [
                'success',
                'errors',
            ]
        ]);
    }

    public function test_get_lead_success()
    {
        $this->createPermissions();
        $this->createRoles();

        $user_manager = $this->createUserManager();
        $response_auth = $this->getAuthToken();
        $user_agent = $this->createUserAgent();
        $candidato = $this->createCandidato($user_manager, $user_agent);

        $this->json('get', "/api/lead/{$candidato->id}", [], ['Authorization' => "Bearer {$response_auth['data']['token']}"])
        ->assertStatus(200)
        ->assertJsonStructure([
            'meta' => [
                'success',
                'errors',
            ],
            'data' => [
                'id',
                'name',
                'source',
                'created_by',
                'owner',
                'created_at',
                'updated_at',
            ],
        ]);
    }
    
    public function test_get_lead_unauthorized()
    {
        $this->createPermissions();
        $this->createRoles();

        $user_manager = $this->createUserManager();
        $user_agent = $this->createUserAgent();
        $candidato = $this->createCandidato($user_manager, $user_agent);

        $this->json('get', "/api/lead/{$candidato->id}", [], ['Authorization' => "XXXXXXXXXXXXX"])
        ->assertStatus(401)
        ->assertJsonStructure([
            'meta' => [
                'success',
                'errors',
            ],
        ]);
    }

    public function test_get_lead_not_found()
    {
        $this->createPermissions();
        $this->createRoles();

        $user_manager = $this->createUserManager();
        $response_auth = $this->getAuthToken();
        $user_agent = $this->createUserAgent();
        $candidato = $this->createCandidato($user_manager, $user_agent);

        $this->json('get', "/api/lead/{9999}", [], ['Authorization' => "Bearer {$response_auth['data']['token']}"])
        ->assertStatus(404);
    }

    public function test_get_leads_success()
    {
        $this->createPermissions();
        $this->createRoles();

        $user_manager = $this->createUserManager();
        $response_auth = $this->getAuthToken();
        $user_agent = $this->createUserAgent();
        $candidato = $this->createCandidato($user_manager, $user_agent);

        $this->json('get', "/api/leads", [], ['Authorization' => "Bearer {$response_auth['data']['token']}"])
        ->assertStatus(200)
        ->assertJsonStructure([
            'meta' => [
                'success',
                'errors',
            ],
            'data' => [
                [
                    'id',
                    'name',
                    'source',
                    'created_by',
                    'owner',
                    'created_at',
                    'updated_at',
                ],
            ],
        ]);
    }

    public function test_get_leads_unauthorized()
    {
        $this->createPermissions();
        $this->createRoles();

        $user_manager = $this->createUserManager();
        $user_agent = $this->createUserAgent();
        $candidato = $this->createCandidato($user_manager, $user_agent);

        $this->json('get', "/api/leads", [], ['Authorization' => "XXXXXXXXXXXXX"])
        ->assertStatus(401)
        ->assertJsonStructure([
            'meta' => [
                'success',
                'errors',
            ],
        ]);
    }

    private function getAuthToken(){
        return $this->json('POST', 'api/auth', ['email' => 'manager@manager.com', 'password' => 'password', 'device' => 'windows']);
    }

    private function setDataPost($user_manager, $user_agent){
        return [
            'name' => 'Candidato 1',
            'source' => 'Ipsum',
            'created_by' => $user_manager->id,
            'owner' => $user_agent->id,
        ];
    }

    private function createPermissions(){
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::create(['name' => 'candidatos.access']);
        Permission::create(['name' => 'candidatos.access_own']);
        Permission::create(['name' => 'candidatos.create']);
        Permission::create(['name' => 'candidatos.edit']);
        Permission::create(['name' => 'candidatos.delete']);
    }
    
    private function createRoles(){
        Role::create([
            'name' => 'manager',
        ])->givePermissionTo(Permission::all()->pluck('name'));
        Role::create([
            'name' => 'agent',
        ])->givePermissionTo('candidatos.access_own', 'candidatos.edit');
    }

    private function createUserManager(){
        return User::factory()->create([
            'name' => 'Manager',
            'email' => 'manager@manager.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ])->assignRole('manager');
    }
    
    private function createUserAgent(){
        return User::factory()->create([
            'name' => 'Agent',
            'email' => 'agent@agent.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
    }
    
    private function createCandidato($user_manager, $user_agent){
        return Candidato::factory()->create([
            'name' => 'Candidato 1',
            'source' => 'Ipsum',
            'created_by' => $user_manager->id,
            'owner' => $user_agent->id,
        ]);
    }
}
