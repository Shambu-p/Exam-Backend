<?php
use Absoft\Line\HTTP\Route;
use Absoft\App\Administration\Administration;

Route::setDefault("AbnetController", "index");

/**
 * @Remember_this:
 * Route::set("/Users/show", "/toke/type"); @means
 * assume the machine address is localhost:1111 then
 * [http://localhost:1111/Users/show/the_token/the_type]
   this apply for all routes except setDefault("controller", "method")
 **/

Administration::routes();

Route::set("/Auth/login", "/password/email");
Route::set("/Auth/logout", "/token");
Route::set("/Auth/login_update", "/token/password");
Route::set("/Auth/view", "/token");
Route::set("/Auth/authorization", "/token");

Route::set("/Exam/show", "");
Route::set("/Exam/view", "/exam_id");
Route::set("/Exam/take", "/exam_id");
Route::set("/Exam/save", "/title/subject/description");
Route::set("/Exam/add_question", "/exam_id/text/subject/token");
Route::set("/ExamResult/show", "/token");
Route::set("/ExamResult/view", "/result_id/token");
Route::set("/ExamResult/save", "/exam_id/token");
Route::set("/ExamResult/add_result", "/result_id/question/choice/token");
Route::set("/ExamResult/update_score", "/score/result_id");
Route::set("/Exam/update_count", "/question_count");
Route::set("/Question/view", "/question_id");
Route::set("/Question/save", "/text/subject/user_id");
Route::set("/Question/add_choice", "/question_id/text");
Route::set("/Question/update_answer", "/question_id/answer");
Route::set("/Subjects/show", "");
Route::set("/Exam/detail", "/exam_id");
Route::set("/Users/register", "/name/email/age/grade/password/confirm_password");