<?php

namespace App\Security;
use App\Role;
use App\Workflow;
use App\Exceptions\BadRequestException;
use App\Exceptions\DeniedException;
 /**
   * CuratorialSupervisor
   * Implements rules for curator workflow. 
   * 
   * @package    App
   * @subpackage Security
   * @author     Anderson Lira <contato@andersonlira.com>
   */
class CuratorialSupervisor
{

    private $currentWorkflowId = null;
    private $nextWorkflowId = null;
    private $network = null;
    private $roleId = null;

    private $roles = [
        Role::ADMIN, Role::NET_ADMIN,
        Role::REVISOR,Role::CURATOR
    ];
    private $publishRoles =  [
        Role::ADMIN, Role::NET_ADMIN,
        Role::REVISOR
    ];
    private $workflows = [
        Workflow::NEW,
        Workflow::REVISION,
        Workflow::ARCHIVED,
        Workflow::BROKEN,
        Workflow::DRAFT,
    ];

    public function __construct($currentWorkflowId, $nextWorkflowId, $network,$roleId){
        if(!$currentWorkflowId || !$nextWorkflowId || !$network || !$roleId){
            throw new BadRequestException;
        }
        $this->currentWorkflowId = $currentWorkflowId;
        $this->nextWorkflowId = $nextWorkflowId;
        $this->network = $network;
        $this->roleId = $roleId;
    }

    //Check if role has permission to change workflow status of main object
    public function canDo(){

        if(!in_array($this->roleId,$this->roles)){
            throw new DeniedException;
        }

        $networkConfig = $this->network->networkConfig;
        if(!$networkConfig || ($networkConfig && $networkConfig->use_revisor == false)) {
            array_push($this->publishRoles,Role::CURATOR);
        }

        if(($this->currentWorkflowId == Workflow::PUBLISHED || 
                $this->nextWorkflowId == Workflow::PUBLISHED) &&
                !in_array($this->roleId,$this->publishRoles))
        {
                throw new DeniedException;
            
        }

        return true;
    }

    public function canSee(){

        if(!in_array($this->roleId,$this->roles)){
            throw new DeniedException;
        }
        return true;
    }

    public function workflowIds(){
        if(!in_array($this->roleId,$this->roles)){
            throw new DeniedException;
        }     
        if(in_array($this->roleId,$this->publishRoles) ){
            array_push($this->workflows,Workflow::PUBLISHED);
        }
        if(!$this->network->useRevisor()) {
            array_push($this->workflows ,Workflow::PUBLISHED);
        }
        return $this->workflows;
    }

}