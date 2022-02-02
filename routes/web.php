<?php

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
Route::namespace('Auth')->group(function () {
Route::get('/login-by-otp', 'LoginController@loginOTPView')->name('loginOTPView');
Route::post('/loginWithOtp', 'LoginController@loginWithOtp')->name('loginWithOtp');
Route::post('/contactLoginOtp', 'LoginController@contactLoginOtp')->name('contactLoginOtp');
Route::post('/contactLoginOtpVerify', 'LoginController@contactLoginOtpVerify')->name('contactLoginOtpVerify');
Route::get('/account/verify/{token}', 'RegisterController@verifyUser')->name('user.verify'); 

});

Auth::routes();
Route::get('/', 'HomeController@index')->name('front');
Route::get('/home', 'HomeController@index')->name('home');
Route::get('/course-list/{CourseId}', 'HomeController@courseList')->name('course-list');
Route::get('/course-details/{subjectId}', 'HomeController@courseDetails')->name('course-details');
Route::get('/course-search', 'HomeController@courseSearch')->name('course-search');
Route::get('/auto-search', 'HomeController@autoSearch')->name('auto-search');

/* ----------------------------------------------------------------------- */

/*
 * Frontend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Frontend', 'as' => 'frontend.'], function () {
Route::get('/contact-us', 'PageController@getContact')->name('contactUs');
Route::get('/about-us', 'PageController@aboutUs')->name('aboutUs');
Route::post('/contact-us', 'PageController@sendContact')->name('contactUsPost');
Route::post('/save-newsletter', 'PageController@saveNewsletter')->name('newsletterSave');
Route::get('/privacy-policy', 'PageController@privacy')->name('privacyPolicy');
Route::get('/terms-conditions', 'PageController@terms')->name('termsConditions');

//Route::namespace('Auth')->group(function () {
	 /* Payment Route */
	Route::get('/payment', 'PaymentController@index')->name('payment');
	Route::post('/payment', 'PaymentController@paymentPost')->name('paymentpost');
	Route::get('/success', 'PaymentController@paymentSuccess')->name('paymentSuccess');
	Route::get('/faild', 'PaymentController@paymentFaild')->name('paymentFaild');
    Route::post('/apply-coupon', 'PaymentController@applyCoupon')->name('applyCoupon');
    Route::post('/remove-coupon', 'PaymentController@removeCoupon')->name('removeCoupon');

    /* Student Profiule Route */
    Route::get('/profile-first', 'StudentController@profileFirst')->name('profile.first');
    Route::get('/profile', 'StudentController@profile')->name('profile');
	Route::post('/profile', 'StudentController@updateProfileTutor')->name('updateProfileTutor');
	Route::post('/profile-student', 'StudentController@updateProfileStudent')->name('updateProfileStudent');
	Route::get('/my-mylearning-list', 'StudentController@mylearningList')->name('mylearningList');
	Route::get('/my-mylearning-details/{id}/{subjectId}/{videoUid?}/{tab?}', 'StudentController@mylearningStart')->name('mylearningStart');
    Route::delete('/destroy-favourites/{id}', 'StudentController@destroyFavourite')->name('destroyFavourites');
    Route::get('/set-favourites', 'StudentController@setFavourites')->name('setFavourites');
    Route::get('/student-favourites', 'StudentController@studentFavourites')->name('studentFavourites');
    Route::post('/fleg-video', 'StudentController@flegVideo')->name('flegVideo');
    Route::get('/rating-video', 'StudentController@setRatingvideo')->name('ratingvideo');
	Route::get('/my-payment', 'PaymentController@myPayment')->name('myPayment');
    Route::post('/upload-urofile', 'StudentController@uploadProfile')->name('uploadProfile');
    Route::post('/change-avatar', 'StudentController@changeAvatar')->name('changeAvatar');
	Route::get('/change-password', 'StudentController@changePassword')->name('changePassword');
	Route::post('/change-password', 'StudentController@changePasswordSave')->name('changePasswordSave');
//});
	//Topic routes
	Route::get('/topics', 'TutorController@index')->name('topics');
    Route::get('/topic/create', 'TutorController@create')->name('topic.create');
    Route::post('/topic/store', 'TutorController@store')->name('topic.store');
    Route::get('topic/edit/{id}', 'TutorController@edit')->name('topic.edit');
    Route::post('topic/update/{id}', 'TutorController@update')->name('topic.update');
    Route::delete('topic/delete/{id}', 'TutorController@destroy')->name('topic.destroy');
	
	//Topic routes
	Route::get('/videos', 'TutorController@indexVideo')->name('videos');
    Route::get('/video/create', 'TutorController@createVideo')->name('video.create');
    Route::post('/video/store', 'TutorController@storeVideo')->name('video.store');
    Route::get('video/edit/{id}', 'TutorController@editVideo')->name('video.edit');
    Route::post('video/update/{id}', 'TutorController@updateVideo')->name('video.update');
    Route::delete('video/delete/{id}', 'TutorController@destroyVideo')->name('video.destroy');
   

    


});

Route::group(['prefix' => 'admin', 'as' => 'admin'], function () {
Route::namespace('Auth')->group(function () {
    /* Below route for all register get routes land on that route */
	Route::get('/', 'LoginController@loginView')->name('loginView');
});
});



/* ----------------------------------------------------------------------- */
/*
 * Backend Ajax Routes
 * prefix indicate the url common profix
 */
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'ajax.'], function () {
    Route::post('/category/schools/{std_filter?}', 'AjaxController@getSchools')->name('category.schools');
    Route::post('/school-departments', 'AjaxController@getSchoolDepartments')->name('school.departments');
    Route::post('/school-courses', 'AjaxController@getSchoolCourses')->name('school.courses');
    Route::post('/stdfilter-courses/{std_filter?}', 'AjaxController@getStudentfilterCourses')->name('school.stdfiltercourses');
    Route::post('/department-courses', 'AjaxController@getDepartmentCourses')->name('department.courses');
    Route::post('/school-courseclasses', 'AjaxController@getSchoolCourseclasses')->name('school.courseclasses');
    Route::post('/stdfilter-courseclasses/{std_filter?}', 'AjaxController@getStudentfilterCourseclasses')->name('school.stdfiltercourseclasses');
    Route::post('/class-subject', 'AjaxController@getClassSubjects')->name('class.subject');
    Route::post('/class-periods', 'AjaxController@getClassPeriods')->name('class.period');
    Route::post('/subject-topics', 'AjaxController@getSubjectTopics')->name('subject.topics');
	Route::post('/subject-topics-tutor', 'AjaxController@getSubjectTopicsTutor')->name('subject.topics.tutor');
    Route::post('/school-classsubjects', 'AjaxController@getSchoolClassSubjects')->name('school.classsubjects');
    Route::post('/school-filterclasssubjects/{std_filter?}', 'AjaxController@getSchoolfilterClassSubjects')->name('school.filterclasssubjects');
    Route::post('/school-tutors', 'AjaxController@getSchoolTutors')->name('school.tutors');
    Route::post('/upload-video', 'AjaxController@dropzoneStore')->name('dropzone.upload.video');
    Route::post('/upload-note', 'AjaxController@dropzoneNoteStore')->name('dropzone.upload.note');

    Route::post('/state/zones/{std_filter?}', 'AjaxController@getZonesByState')->name('state.zones');
    Route::post('/zones/district/{std_filter?}', 'AjaxController@getDistrictsByZone')->name('zone.district');
    Route::post('/district/cities/{std_filter?}', 'AjaxController@getCitiesByDistrict')->name('district.city');
    Route::post('/city/colleges/{std_filter?}', 'AjaxController@getCollegesByCity')->name('city.colleges');

});
/*
 * Backend Routes
 * Namespaces indicate folder structure
 */
Route::group(['namespace' => 'Backend', 'prefix' => 'admin', 'as' => 'backend.', 'middleware' => ['admin', 'preventBackHistory']], function () {
		Route::get('/', 'DashboardController@index');
    Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
    //Route::resource('schools', 'SchoolController')->name('schools');
    //schools routes
  /*  Route::get('/schools', 'SchoolController@index')->name('schools');
    Route::get('/school/create', 'SchoolController@create')->name('school.create');
    Route::post('/school/store', 'SchoolController@store')->name('school.store');
    Route::get('school/edit/{id}', 'SchoolController@edit')->name('school.edit');
    Route::post('school/update/{id}', 'SchoolController@update')->name('school.update');
    Route::delete('school/delete/{id}', 'SchoolController@destroy')->name('school.destroy');
    Route::get('/school/details/{id}', 'SchoolController@show')->name('school.show');
    Route::post('/savesemester', 'SchoolController@savesemester')->name('school.savesemester');*/

    //category routes
    //Route::resource('categories', 'CategoryController');

    //course routes
    Route::get('/institutions', 'CourseController@index')->name('courses');
    Route::get('/institute/create/{id?}', 'CourseController@create')->name('course.create');
    Route::post('/institute/store', 'CourseController@store')->name('course.store');
    Route::get('institute/edit/{id}/{school_id?}', 'CourseController@edit')->name('course.edit');
    Route::post('institute/update/{id}', 'CourseController@update')->name('course.update');
    Route::delete('institute/delete/{id}/{school_id?}', 'CourseController@destroy')->name('course.destroy');
    Route::get('/institute/{id}', 'CourseController@show')->name('course.show');
    Route::post('institute/edit', 'CourseController@edit_ajax')->name('course.edit_ajax');
    //classroom routes
    Route::resource('classrooms', 'ClassroomController');

    //Subject routes

    Route::get('/courses', 'SubjectController@index')->name('subjects.index');
    Route::get('/courses/create/{id?}', 'SubjectController@create')->name('subjects.create');
    Route::post('/courses/store', 'SubjectController@store')->name('subjects.store');
    Route::get('courses/edit/{id}', 'SubjectController@edit')->name('subjects.edit');
    Route::put('courses/update/{id}', 'SubjectController@update')->name('subjects.update');
    Route::delete('courses/delete/{id}', 'SubjectController@destroy')->name('subjects.destroy');
    Route::get('/courses/{id}', 'SubjectController@show')->name('subjects.show');

    //Route::resource('courses', 'SubjectController');
  //  Route::post('course/edit', 'SubjectController@edit_ajax')->name('subjects.edit_ajax');

    //class routes
    Route::get('/schools', 'ClassesController@index')->name('classes.index');
    Route::get('/school/create/{id?}', 'ClassesController@create')->name('classes.create');
    Route::post('/school/store', 'ClassesController@store')->name('classes.store');
    Route::get('school/edit/{id}', 'ClassesController@edit')->name('classes.edit');
    Route::put('school/update/{id}', 'ClassesController@update')->name('classes.update');
    Route::delete('school/delete/{id}', 'ClassesController@destroy')->name('classes.destroy');
    Route::get('/school/{id}', 'ClassesController@show')->name('classes.show');


    //Route::resource('classes', 'ClassesController');
    //Route::post('class/edit', 'ClassesController@edit_ajax')->name('classes.edit_ajax');

    //Topic routes
    Route::resource('topics', 'TopicController');
    Route::post('topics/ordering/save', 'TopicController@saveOrdering')->name('topics.ordering.save');
    Route::post('topic/edit', 'TopicController@edit_ajax')->name('topics.edit_ajax');

    //Videos routes
    Route::resource('videos', 'VideoController');
	 Route::get('videos/lists', 'VideoController@csvUploadVideo')->name('videos.list');
	 Route::get('video/upload-csv', 'VideoController@csvUploadVideo')->name('video.upload.csv');
	 Route::post('video/upload-csv/save', 'VideoController@csvUploadVideoPost')->name('video.upload.csv.save');
    Route::get('video/upload-files/{uuid}', 'VideoController@uploadFiles')->name('video.upload.files');

    //Students routes
    Route::resource('students', 'StudentController');
    Route::get('/students/assignedclasses/{uuid}', 'StudentController@assignedclasses')->name('students.assignedclasses');
    Route::post('students/assignedclasses/store', 'StudentController@save_assignedclasses')->name('students.saveassignedclasses');

    //Tutors routes
    Route::resource('tutors', 'TutorController');

    Route::get('/contact-inquiries', 'InquiryController@ContactInquiries')->name('ContactInquiries');
    Route::delete('/contact-inquiries-delete/{id}', 'InquiryController@destroyContactInquiry')->name('ContactInquiriesDelete');

    Route::get('/newsletters', 'InquiryController@newsletters')->name('newsletters');
    Route::delete('/newsletters-delete/{id}', 'InquiryController@destroyNewsletters')->name('destroyNewsletters');
    //profile routes
    Route::resource('profile', 'ProfileController');
     //reports routes
    Route::get('reports/favourited-videos-list', 'ReportsController@favouritedVideosList')->name('reports.favourited.videos.list');
    Route::get('reports/student-videos-watch', 'ReportsController@studentVideoswatch')->name('reports.student.videos.watch');
    Route::get('reports/total-videos-watch', 'ReportsController@totalVideoswatch')->name('reports.total.videos.watch');

    //Coupon routes
    Route::resource('coupons', 'CouponController');

    Route::get('/pages', 'PagesController@index')->name('pages.index');
    Route::get('/pages/{id}', 'PagesController@edit')->name('pages.edit');
    Route::put('pages/edit/{id}', 'PagesController@update')->name('pages.update');

    //setting routes
    Route::resource('settings', 'SettingController');

    
    
});


/* Function for print array in formated form */
if (!function_exists('pr')) {
    function pr($array)
    {
        echo "<pre>";
        print_r($array);
        echo "</pre>";
    }

}

/* Function for print query log */
if (!function_exists('qLog')) {
    DB::enableQueryLog();
    function qLog()
    {
        pr(DB::getQueryLog());
    }

}

Route::any('/tus/{any?}', function () {
    $response = app('tus-server')->serve();

    return $response->send();
})->where('any', '.*');

