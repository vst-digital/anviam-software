<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\ChatGroupController;
use App\Http\Controllers\FullCalenderController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\MemoController;
use App\Http\Controllers\Admin\StorageController;
use App\Http\Controllers\Admin\CalendarController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\FileManagerController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', function() { return view('auth.login');})->name('home');

// Route::get('/calendar', function () { return view('calendar'); });
Route::namespace('Admin')->prefix('admin')->middleware('auth')->group(function () {

    Route::get('dashboard', [App\Http\Controllers\HomeController::class, 'admin_login']);
    Route::get('companies', [CompanyController::class, 'companies']);
    Route::get('add_companies', [CompanyController::class, 'add_companies']);
    Route::post('save_company', [CompanyController::class, 'save_company']);
    Route::get('edit_company/{id}', [CompanyController::class, 'edit_company']);
    Route::get('view_company/{id}', [CompanyController::class, 'view_company']);
    Route::post('update_company', [CompanyController::class, 'update_company']);
    Route::get('detele_company/{id}', [CompanyController::class, 'detele_company']);
    Route::post('status_change', [CompanyController::class, 'status_change']);
    Route::post('check_email', [CompanyController::class, 'check_email']);
    Route::post('edit_email', [CompanyController::class, 'edit_email']);
    Route::get('user', [CompanyController::class, 'user']);
    Route::get('user_create', [CompanyController::class, 'user_create']);
    Route::get('edit_user/{userId}', [CompanyController::class, 'edit_user']);
    Route::post('save_user', [CompanyController::class, 'save_user'])->name('save_user');
    Route::post('edit_user/{userId}', [CompanyController::class, 'update_user'])->name('edit_user');


    Route::get('role', [CompanyController::class, 'role']);
    Route::get('detele_role_user/{id}', [CompanyController::class, 'detele_role_user']);
    Route::get('view_role_user/{id}', [CompanyController::class, 'view_role_user']);
    Route::post('save_role', [CompanyController::class, 'save_role']);
    Route::get('detele_role/{id}', [CompanyController::class, 'detele_role']);
    Route::post('update_roless', [CompanyController::class, 'update_roless']);
    Route::post('get_role', [CompanyController::class, 'get_role'])->name('roles.get-role');
    Route::get('chat', [MessageController::class, 'conversation']);

	Route::get('conversation/{userId?}', [MessageController::class, 'conversation'])->name('message.conversation');
	Route::get('groupconversation/{groupId?}', [MessageController::class, 'groupconversation'])->name('message.group.conversation');
	Route::post('send-message', [MessageController::class, 'sendMessage'])->name('message.send-message');
	Route::post('send-group-message', [MessageController::class, 'sendGroupMessage'])->name('message.send-group-message');
    Route::get('downloadfile/{fileid}',[MessageController::class, 'download'])->name('download');
    Route::post('check-group', [ChatGroupController::class, 'checkGroup'])->name('group.check-group');
    Route::post('add-user-to-group', [ChatGroupController::class, 'addUserToGroup'])->name('group.add-user-to-group');
    Route::post('get-users-of-group', [ChatGroupController::class, 'getUsersFromGroup'])->name('group.get-users-of-group');


    /*========= Project Route ==================*/
    Route::post('projectsAll', [ProjectController::class, 'projectsAll']);
    Route::get('project_list', [ProjectController::class, 'project_list']);
    Route::get('project_create', [ProjectController::class, 'project_create']);
    Route::get('project_type', [ProjectController::class, 'project_type']);
    Route::post('save_project_type', [ProjectController::class, 'save_project_type']);
    Route::post('update_project_type', [ProjectController::class, 'update_project_type']);
    Route::get('detele_project_type/{id}', [ProjectController::class, 'detele_project_type']);
    Route::post('save_project', [ProjectController::class, 'save_project']);
    Route::get('detele_project/{id}', [ProjectController::class, 'detele_project']);
    Route::get('edit_project/{id}', [ProjectController::class, 'edit_project']);
    Route::post('update_project', [ProjectController::class, 'update_project']);




    //Document Storage

    Route::get('upload_document', [FileManagerController::class, 'index']);
    Route::get('document_storage', [DocumentController::class, 'document_storage']);
    Route::get('document_create', [DocumentController::class, 'document_create']);
    Route::post('attachment_add', [DocumentController::class, 'attachment_add']);
    Route::post('attachment_rm', [DocumentController::class, 'attachment_rm']);
    Route::post('save_document', [DocumentController::class, 'save_document']);
    Route::post('update_document', [DocumentController::class, 'update_document']);
    Route::get('edit_document/{id}', [DocumentController::class, 'edit_document']);
    Route::get('getAttachments/{id}', [DocumentController::class, 'getAttachments']);
    Route::get('detele_document/{id}', [DocumentController::class, 'detele_document']);
    Route::post('add_folder', [DocumentController::class, 'add_folder'])->name('documents.add-folder');
    Route::post('check_folder_exists', [DocumentController::class, 'check_folder_exists'])->name('documents.check-folder');

    Route::post('filefolderpath', [FileManagerController::class, 'filefolderpath']);
    Route::get('download_file/{id}', [FileManagerController::class, 'sharefile']);


    //get project type
    Route::post('get_project_type', [ProjectController::class, 'get_project_type'])->name('project.get-project-type');
    //Memo Routes
    Route::get('issue_list', [MemoController::class, 'memo_list']);
    Route::get('issue_create', [MemoController::class, 'memo_create']);
    Route::post('save_issue', [MemoController::class, 'save_memo']);
    Route::post('update_issue', [MemoController::class, 'update_memo']);
    Route::post('reply_issue', [MemoController::class, 'reply_memo']);
    Route::get('edit_issue/{id}', [MemoController::class, 'edit_memo']);
    Route::get('detele_issue/{id}', [MemoController::class, 'detele_memo']);
    Route::get('check_issue/{id}', [MemoController::class, 'check_memo']);

    //Calendar route
    Route::get('calendar', [CalendarController::class, 'calendar_list']);
    Route::get('calendar_data', [CalendarController::class, 'index']);
    Route::post('calendar_action', [CalendarController::class, 'action']);
    //Storage routes
    Route::get('storage',[StorageController::class,'storage_list_chat']);
    Route::get('group_storage',[StorageController::class,'storage_list_group']);
    Route::get('memo_storage',[StorageController::class,'storage_list_memo']);
    Route::get('project_storage',[StorageController::class,'storage_list_projects']);
    Route::get('storage_list_documents',[StorageController::class,'storage_list_documents']);

    /*      paint image uploade route      */
    Route::post('paint_image_uploade', [CompanyController::class, 'paint_image_uploade']);
    Route::post('deletePaintImage', [CompanyController::class, 'deletePaintImage']);
    Route::get('department', [DepartmentController::class, 'department'])->name('department');
    Route::get('department_create', [DepartmentController::class, 'department_create']);
    Route::get('edit_department/{id}', [DepartmentController::class, 'edit_department']);
    Route::get('detele_department/{id}', [DepartmentController::class, 'detele_department']);
    Route::post('save_department', [DepartmentController::class, 'save_department']);
    Route::post('update_department', [DepartmentController::class, 'update_department']);




});
 /*     File Manager Upload Route  */
Route::post('file-manager/upload', [FileManagerController::class,'upload']);
Route::post('file-manager/create-directory',[FileManagerController::class,'createDirectory']);

Route::get('admin/download_doc/{id}', [DocumentController::class, 'download_doc']);
Route::get('admin/download_document/{id}', [DocumentController::class, 'download_document']);



