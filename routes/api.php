<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('register', 'Api\AuthController@register');
Route::post('login', 'Api\AuthController@login');




Route::group(['middleware' => 'auth:api'], function(){
    
    Route::get('project/{id}', 'Api\ProjectController@show');
    Route::post('project', 'Api\ProjectController@store');
    Route::put('project/{id}', 'Api\ProjectController@update');
    Route::delete('project/{id}', 'Api\ProjectController@destroy');
    Route::get('project', 'Api\ProjectController@index');

    Route::get('task/{id}', 'Api\TaskController@show');
    Route::get('listdepedencetask/{id}', 'Api\TaskController@listDepedenceTask');
    Route::post('task', 'Api\TaskController@store');
    Route::put('task/{id}', 'Api\TaskController@update');
    Route::delete('task/{id}', 'Api\TaskController@destroy');
    Route::get('task', 'Api\TaskController@index');
    Route::get('listTaskByYear/{year}', 'Api\TaskController@listTaskByYear');
    Route::get('listTaskByYearUser/{year}', 'Api\TaskController@listTaskByYearUser');

    Route::get('taskreviewing', 'Api\TaskController@indexReview');
    Route::get('taskreviewinguser', 'Api\TaskController@indexReviewUser');
    Route::get('taskassignee', 'Api\TaskController@indexAssignee');
    Route::get('taskassigneeuser', 'Api\TaskController@indexAssigneeUser');


    Route::get('showassigneeticket', 'Api\TaskController@showAssigneeTicket');
    Route::get('listtaskdepedence', 'Api\TaskController@listTaskTypeDepedence');
    Route::get('listtaskuserlogin', 'Api\TaskController@listTaskUserLogin');
    
    Route::get('listtaskexcludebytaskname/{name}', 'Api\TaskController@listTaskExcludeByTaskName');
    Route::get('listtaskexcludebytasknameuser/{name}', 'Api\TaskController@listTaskExcludeByTaskNameUser');

    Route::get('showpiechartevent', 'Api\TaskController@showPieChartEvent');
    Route::get('showpiechartemployee', 'Api\TaskController@showPieChartEmployee');
    Route::get('showtaskbyeventfiltercalendar/{id}', 'Api\TaskController@showTaskByEventFilterCalendar');
    Route::get('showtaskbyeventfiltercalendaruser/{id}', 'Api\TaskController@showTaskByEventFilterCalendarUser');
    
    Route::get('showemployeebyeventfiltercalendar/{id}', 'Api\TaskController@showEmployeeByEventFilterCalendar');
    Route::get('showemployeebyeventfiltercalendaruser/{id}', 'Api\TaskController@showEmployeeByEventFilterCalendarUser');
    
    Route::get('showstatustaskbyeventfiltercalendar/{id}', 'Api\TaskController@showStatusTaskByEventFilterCalendar');
    Route::get('showstatustaskbyeventfiltercalendaruser/{id}', 'Api\TaskController@showStatusTaskByEventFilterCalendarUser');
    
    Route::get('showassigneeview', 'Api\TaskController@showAssigneeView');
    Route::get('showassigneeviewuser', 'Api\TaskController@showAssigneeViewUser');
    
    Route::get('showreviewview', 'Api\TaskController@showReviewView');
    //Route::get('showemployeelogin/{name}', 'Api\TaskController@showEmployeeLogin');
    Route::get('showallemployeelogin', 'Api\TaskController@showAllEmployeeLogin');
    Route::get('showallemployeeloginevent', 'Api\TaskController@showAllEmployeeLoginForCalendarEventUser');
    
    //Route::get('subtask/{id}', 'Api\SubtaskController@show');
    //Route::post('subtask', 'Api\SubtaskController@store');
    //Route::put('subtask/{id}', 'Api\SubtaskController@update');
    //Route::delete('subtask/{id}', 'Api\SubtaskController@destroy');
    //Route::get('subtask', 'Api\SubtaskController@index');

    //Route::get('taskdetail/{id}', 'Api\TaskDetailController@show');
    //Route::post('taskdetail', 'Api\TaskDetailController@store');
    //Route::put('taskdetail/{id}', 'Api\TaskDetailController@update');
    //Route::delete('taskdetail/{id}', 'Api\TaskDetailController@destroy');
    //Route::get('taskdetail', 'Api\TaskDetailController@index');

    Route::put('updatestatus/{id}', 'Api\TaskController@updateStatus');

    Route::get('employee/{id}', 'Api\EmployeeController@show');
    Route::post('employee', 'Api\EmployeeController@store');
    Route::put('employee/{id}', 'Api\EmployeeController@update');
    Route::delete('employee/{id}', 'Api\EmployeeController@destroy');
    Route::get('employee', 'Api\EmployeeController@index');
    Route::get('showemployeelogin', 'Api\EmployeeController@showEmployeeLogin');

    Route::get('company/{id}', 'Api\CompanyController@show');
    Route::post('company', 'Api\CompanyController@store');
    Route::put('company/{id}', 'Api\CompanyController@update');
    Route::delete('company/{id}', 'Api\CompanyController@destroy');
    Route::get('company', 'Api\CompanyController@index');

    Route::get('ticket/{id}', 'Api\TicketController@show');
    Route::post('ticket', 'Api\TicketController@store');
    Route::put('ticket/{id}', 'Api\TicketController@update');
    Route::delete('ticket/{id}', 'Api\TicketController@destroy');
    Route::get('ticket', 'Api\TicketController@index');
    Route::get('indexticketuser', 'Api\TicketController@indexTicketUser');
    Route::put('updatestatusticket/{id}', 'Api\TicketController@updateStatusTicket');
    Route::get('showticket/{id}', 'Api\TicketController@showTicket');
    
    Route::get('department/{id}', 'Api\DepartmentController@show');
    Route::post('department', 'Api\DepartmentController@store');
    Route::put('department/{id}', 'Api\DepartmentController@update');
    Route::delete('department/{id}', 'Api\DepartmentController@destroy');
    Route::get('department', 'Api\DepartmentController@index');

    Route::post('logout', 'Api\AuthController@logout');

});

