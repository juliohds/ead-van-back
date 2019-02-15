<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.


|
*/
/*
    W A R N I N G!
This file needs refactoring. There are some bad approach and it must be revisted.

*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->post(
    'api/login',
    [
       'uses' => 'AuthController@authenticate'
    ]
);
$router->put(
    'api/refresh-token',
    [
       'uses' => 'AuthController@refreshToken'
    ]
);

$router->group(
    ['middleware' => 'jwt.auth'],
    function() use ($router) {
        $router->get('users', function() {
            $users = \App\Contact::all();
            return response()->json($users);
        });
    }
);

$router->group(['prefix' => 'api','middleware' => 'jwt.auth'], function () use ($router) {

    $router->get('contacts',  ['uses' => 'ContactController@showAll']);

    $router->get('contacts/{id}', ['uses' => 'ContactController@showOneContact']);

    $router->post('contacts', ['uses' => 'ContactController@create']);

    $router->delete('contacts/{id}', ['uses' => 'ContactController@delete']);

    $router->put('contacts/{id}', ['uses' => 'ContactController@update']);

    //aws
    $router->get('aws-s3-service/name/{name}/type/{type}', ['uses' => 'AwsPreSignedUrlController@showUrlPreSigned']);

    //Users routers
    $router->put('users/{id}', ['uses' => 'UserController@update']);

    //evaluation
    $router->post('{idNetwork}/target-object/{idTargetObject}/evaluation', ['uses' => 'EvaluationController@registerEvaluation']);
    $router->put('{idNetwork}/target-object/{idTargetObject}/evaluation', ['uses' => 'EvaluationController@updateEvaluation']);

    //state network
    $router->post('state-network',  ['uses' => 'StateNetworkController@saveAll']);
    $router->delete('state-network/{id}',  ['uses' => 'StateNetworkController@remove']);

    //favority-target-object
    $router->post('{idNetwork}/target-object/{idTargetObject}/favority-target-object', ['uses' => 'FavorityTargetObjectController@updateFavorityTargetObject']);
    $router->get('{idNetwork}/target-object/{idTargetObject}/favority-target-object', ['uses' => 'FavorityTargetObjectController@showFavorityTargetObject']);
    $router->get('{idNetwork}/my-favoritys-target-object', ['uses' => 'FavorityTargetObjectController@showAllFavorityTargetObject']);
    $router->get('{idNetwork}/oda-is-favority/{id_target_oda}', ['uses' => 'FavorityTargetObjectController@showFavorityObjectByOdaId']);
    $router->get('{idNetwork}/my-favoritys', ['uses'=>'FavorityTargetObjectController@myFavority']);

    //comments
    $router->post('{idNetwork}/target-object/{idTargetObject}/comments', ['uses' => 'CommentController@registerComment']);
    $router->put('{idNetwork}/comments/{id}', ['uses' => 'CommentController@update']);
    $router->delete('{idNetwork}/comments/{id}', ['uses' => 'CommentController@delete']);

    //user-list
    $router->get('{idNetwork}/user-list', ['uses' => 'UserListController@showUserList']);
    $router->post('{idNetwork}/user-list', ['uses' => 'UserListController@create']);
    $router->post('{idNetwork}/user-list/{id}/add', ['uses' => 'UserListController@addOda']);
    $router->delete('{idNetwork}/user-list/{id}/remove', ['uses' => 'UserListController@removeOda']);
    $router->put('{idNetwork}/user-list/{id}', ['uses' => 'UserListController@edit']);
    $router->delete('{idNetwork}/user-list/{id}', ['uses' => 'UserListController@delete']);
    $router->get('{idNetwork}/user-list/{id}/favoritys', ['uses' => 'UserListController@showFavority']);
    $router->get('{idNetwork}/user-list-total', ['uses' => 'UserListController@showListAndTotalFavority']);

    //services
    $router->post('service/upload-image', ['uses' => 'ServiceController@uploadImage']);
});


//Open routes
$router->group(['prefix' => 'api'], function () use ($router) {

    //Register routes
    $router->get('profiles',  ['uses' => 'ProfileController@showAll']);
    $router->get('genders',  ['uses' => 'GenderController@showAll']);
    $router->get('grades',  ['uses' => 'GradeController@showAll']);
    $router->get('interests',  ['uses' => 'InterestController@showAll']);
    $router->get('states',  ['uses' => 'StateController@showAll']);
    $router->get('states/{id}/cities',  ['uses' => 'StateController@showCities']);
    $router->get('types/schools',  ['uses' => 'TypeSchoolController@showAll']);
    $router->get('schools',  ['uses' => 'SchoolController@showByTypeAndCity']);

    //User
    $router->get('users-all', ['uses'=>'UserController@showAllNetworks']);

    //network
    $router->get('networks',  ['uses' => 'NetworkController@show']);

    //state network
    $router->get('state-network',  ['uses' => 'StateNetworkController@showAll']);

    //bncc
    $router->get('bncc-abilities',  ['uses' => 'AbilityBnccController@show']);
    $router->get('bncc-abilities-title',  ['uses' => 'AbilityBnccController@searchByBncc']);
    $router->get('bncc-components',  ['uses' => 'ComponentBnccController@showAll']);

    

    $router->get('apimec/{id}',  ['uses' => 'MainObjectController@showByOldId']);
    
});

//network  routers
$router->group(['prefix' => 'api/{idNetwork}','middleware' => 'network'], function () use ($router) {

    //Form contato
    $router->post('send/contact',  ['uses' => 'ContactController@contato']);
    
    //user
    $router->get('users', ['uses'=>'UserController@showAll']);
    $router->post('register',  ['uses' => 'UserController@register']);
    $router->post('register-with-facebook',  ['uses' => 'UserController@registerWithFacebook']);
    $router->post('register-with-google-account',  ['uses' => 'UserController@registerWithGoogleAccount']);
    $router->get('filter/profile', ['uses'=>'UserController@filterByProfile']);
    $router->get('users/search/email', ['uses'=>'UserController@searchByEmail']);

    //academic
    $router->get('subjects', ['uses'=>'AcademicController@subjects']);
    $router->get('grades', ['uses'=>'AcademicController@grades']);

    //custom-pages
    $router->get('pages/{slug}',  ['uses' => 'CustomPageController@show']);
    $router->get('pages',  ['uses' => 'CustomPageController@showAll']);

    //role 
    $router->put('role/{id}', ['uses'=>'RoleController@update']);

    //search
    $router->get('search',  ['uses' => 'SearchController@searchText']);
    $router->get('home-facets',  ['uses' => 'SearchController@homeFacets']);

    //main object
    $router->get('main-object/{id}',  ['uses' => 'MainObjectController@showById']);
    $router->post('main-object',  ['uses' => 'MainObjectController@create']);
    $router->put('main-object/{id}',  ['uses' => 'MainObjectController@update']);
    $router->delete('main-object/{id}',  ['uses' => 'MainObjectController@delete']);
    $router->get('suggested', ['uses'=>'MainObjectController@suggestedHome']);

    //facets
    $router->get('facets',  ['uses' => 'FacetController@showAll']);
    $router->get('facets/{id}',  ['uses' => 'FacetController@showById']);
    $router->get('facet-options/{id}',  ['uses' => 'NetworkFacetController@getFacetOptionsById']);

    //comments
    $router->get('target-object/{idTargetObject}/comments', ['uses' => 'CommentController@showComments']);
    $router->get('comments', ['uses' => 'CommentController@showAll']);
    $router->put('comments/status/{id}', ['uses' => 'CommentController@changeStatus']);

    //evaluation
    $router->get('target-object/{idTargetObject}/evaluation',  ['uses' => 'EvaluationController@showEvaluation']);
    $router->get('evaluation',  ['uses' => 'EvaluationController@showAll']);
    $router->get('evaluation/filter',  ['uses' => 'EvaluationController@filterByRating']);

    //user list
    $router->get('lists-home', ['uses' => 'UserListController@showListHome']);
    $router->get('user-list', ['uses' => 'UserListController@showAll']);
    $router->get('user-list-public/{slug}', ['uses' => 'UserListController@showListPublicBySlug']);

    //reset password with token
    $router->post('forgot-password',  ['uses' => 'AuthController@forgotPassword']);
    $router->put('reset-password-with-token',  ['uses' => 'AuthController@resetPasswordWithToken']);

    //menu
    $router->get('menu/{id}',  ['uses' => 'MenuController@showAll']);
    $router->get('menu/{idNC}/id/{idMenu}',  ['uses' => 'MenuController@showAllById']);

 

    //modulo-tab
    $router->get('modulo-tab',  ['uses' => 'ModuloTabController@showAll']);
    

    $router->get('institution-menu/{id}',  ['uses' => 'InstitutionMenuController@show']);
});

//Routes network & auth middleware
$router->group(['prefix' => 'api/{idNetwork}','middleware' =>['network','jwt.auth']], function () use ($router) {


    //Exports Jobs
    // $router->get('report/comments', ['uses' => 'ReportsController@NetworkComments']);
    // $router->get('report/lists', ['uses' => 'ReportsController@NetworkLists']);
    // $router->get('report/evaluations', ['uses' => 'ReportsController@NetworkEvaluations']);
    // $router->get('report/users', ['uses' => 'ReportsController@NetworkUsers']);

    //custom pages
    $router->post('pages',  ['uses' => 'CustomPageController@create']);
    $router->put('pages/{slug}',  ['uses' => 'CustomPageController@update']);
    $router->delete('pages/{slug}',  ['uses' => 'CustomPageController@delete']);


    //institution_menu
    $router->get('institution-menu/{id}/id/{idInst}',  ['uses' => 'InstitutionMenuController@showById']);
    $router->post('institution-menu/{id}',  ['uses' => 'InstitutionMenuController@create']);
    $router->put('institution-menu/{id}/id/{idInst}',  ['uses' => 'InstitutionMenuController@update']);
    $router->delete('institution-menu/{id}/id/{idInst}',  ['uses' => 'InstitutionMenuController@delete']);

    //estatisticas
    $router->get('estatisticas-qtd',  ['uses' => 'NetworkController@showAllAmount']);

    //network home facet
    $router->get('network-home-facet/{idnc}',  ['uses' => 'NetworkHomeFacetController@showAll']);
    $router->post('network-home-facet/{idnc}',  ['uses' => 'NetworkHomeFacetController@insert']);
    $router->put('network-home-facet/{idnc}/id/{idhf}',  ['uses' => 'NetworkHomeFacetController@update']);

    //menu
    $router->post('menu',  ['uses' => 'MenuController@insert']);
    $router->put('menu/{id}',  ['uses' => 'MenuController@update']);
    $router->delete('menu/{id}',  ['uses' => 'MenuController@delete']);

    //main object
    $router->get('main-object/{id}/versions',  ['uses' => 'MainObjectController@versions']);
    $router->get('main-object',  ['uses' => 'MainObjectController@showAll']);
    $router->get('main-object-others',  ['uses' => 'MainObjectController@showAllOthers']);
    $router->get('main-object-user-info',  ['uses' => 'MainObjectController@userInfo']);
    $router->post('main-object',  ['uses' => 'MainObjectController@create']);
    $router->post('main-object/import',  ['uses' => 'MainObjectController@import']);
    $router->put('main-object/{id}',  ['uses' => 'MainObjectController@update']);
    $router->delete('main-object/{id}',  ['uses' => 'MainObjectController@delete']);

    //facet
    $router->get('facet-options',  ['uses' => 'NetworkFacetController@showAll']);

    //workflow
    $router->get('workflows',  ['uses' => 'WorkflowController@showAll']);
    $router->get('workflows/auth',  ['uses' => 'WorkflowController@showByPermission']);

    //collaborate
    $router->post('collaborate/oda', ['uses'=>'CollaborateController@saveOda']);
    $router->delete('collaborate/oda/{id}', ['uses'=>'CollaborateController@deleteODa']);
    $router->get('users/me',  ['uses' => 'UserController@userInfo']);
    $router->get('users/{id}',  ['uses' => 'UserController@userInfoById']);

    //user
    $router->put('user-disable/{id}', ['uses'=>'UserController@ativarDesativarUser']);
    $router->delete('user/{id}', ['uses'=>'UserController@delete']);
    $router->get('user-qtd', ['uses'=>'UserController@qtdUser']);

    //comments
    $router->get('comment-qtd', ['uses'=>'CommentController@qtdComment']);

    //evaluation
    $router->get('evaluation-qtd', ['uses'=>'EvaluationController@qtdEvaluation']);

    //user list
    $router->get('user-list-qtd', ['uses'=>'UserListController@qtdUserList']);

    //role
    $router->get('role', ['uses'=>'RoleController@showAll']);

    //network
    $router->delete('networks', ['uses'=>'NetworkController@delete']);

});

//Routes admin
$router->group(['prefix' => 'api/{idNetwork}','middleware' =>['network','jwt.auth','admin']], function () use ($router) {

    $router->post('facets', ['uses'=>'FacetController@create']);
    $router->put('facets/{id}', ['uses'=>'FacetController@update']);
    $router->put('facets-all', ['uses'=>'FacetController@updateAll']);
    $router->put('facets-options-all', ['uses'=>'FacetOptionController@updateAll']);
    $router->post('facets/{idFacet}/options', ['uses'=>'FacetOptionController@create']);
    $router->put('facets/{idFacet}/options/{id}', ['uses'=>'FacetOptionController@update']);

    //network config
    $router->put('config',  ['uses' => 'NetworkConfigController@update']);
    $router->get('config/show-by-network-id',  ['uses' => 'NetworkConfigController@showByNetworkId']);

    //network
    $router->put('network',  ['uses' => 'NetworkController@update']);
    $router->post('network/update-with-image',  ['uses' => 'NetworkController@updateWithImage']);

    //modulo-tab
    $router->get('modulo-tab/{id}',  ['uses' => 'ModuloTabController@show']);
    $router->post('modulo-tab',  ['uses' => 'ModuloTabController@insert']);
    $router->put('modulo-tab/{id}',  ['uses' => 'ModuloTabController@update']);
    $router->delete('modulo-tab/{id}',  ['uses' => 'ModuloTabController@delete']);

});
