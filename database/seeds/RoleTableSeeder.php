<?php

use Illuminate\Database\Seeder;
use App\Role;
class RoleTableSeeder extends Seeder
{
    public function run()
    {
        $this->admin()->save();
        $this->netAdmin()->save();
        $this->revisor()->save();
        $this->curator()->save();
        $this->user()->save();
    }

    private function admin(){
        $role = new Role;
        $role->id = Role::ADMIN;
        $role->tag = Role::defaultValue(Role::ADMIN);
        $role->title = 'Administrador';
        $role->description = 'Acesso total a plataforma (Qualquer rede)';
        return $role;
    }


    private function netAdmin(){
        $role = new Role;
        $role->id = Role::NET_ADMIN;
        $role->tag = Role::defaultValue(Role::NET_ADMIN);
        $role->title = 'Administrador de Rede';
        $role->description = 'Acesso total na rede';
        return $role;
    }

    private function revisor(){
        $role = new Role;
        $role->id = Role::REVISOR;
        $role->tag = Role::defaultValue(Role::REVISOR);
        $role->title = 'Revisor';
        $role->description = 'Revisar e publicar conteúdos';
        return $role;
    }

    private function curator(){
        $role = new Role;
        $role->id = Role::CURATOR;
        $role->tag = Role::defaultValue(Role::CURATOR);
        $role->title = 'Curador';
        $role->description = 'Criar, editar, revisar e publicar conteúdos';
        return $role;
    }
    
    private function user(){
        $role = new Role;
        $role->id = Role::USER;
        $role->tag = Role::defaultValue(Role::USER);
        $role->title = 'Usuário';
        $role->description = 'Sem permissões de administrador';
        return $role;
    }

}
