<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\StaterkitController;
use App\Http\Controllers\AuthenticationController;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Round;
use App\Http\Controllers\MailController;
use Illuminate\Support\Facades\DB;
use App\Models\Sub_admin;

use App\Http\Controllers;
use Illuminate\Tests\Integration\Database\EloquentHasManyThroughTest\Category;

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
Route::middleware('auth')->group(function () {
   
});

Route::get('login', [AuthenticationController::class, 'login_cover'])->name('home');
Route::any('login', [AuthenticationController::class, 'login_cover'])->name('login');
// Route::get('/dashboard', function () {
//     // Your logic goes here

//     return view('Dashboard.Dashboard');
// })->name('dashboard');



// locale Route
Route::group(['prefix' => ''], function () {
    Route::get('login', [AuthenticationController::class, 'login_cover'])->name('auth-login-cover');
    Route::post('login-form', [AuthController::class, 'login'])->name('auth-login-form');
    Route::post('register-form', [AuthController::class, 'register'])->name('auth-register-form');

    Route::get('forget-password', [AuthenticationController::class, 'forgot_password_cover'])->name('forget.password.get');
    Route::post('forget-password', [AuthController::class, 'submitForgetPasswordForm'])->name('forget.password.post');
    Route::get('reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('reset.password.get');
    Route::post('reset-password', [AuthController::class, 'submitResetPasswordForm'])->name('reset.password.post');
    Route::post('reset-password-self', [AuthController::class, 'selfresetPassword'])->name('reset-password-self');
    Route::post('update-profile', [AuthController::class, 'updateProfile'])->name('update-profile');
    Route::post('admin-update-profile', [AuthController::class, 'adminupdateProfile'])->name('admin-update-profile');
});

// Route::get('logout', [AuthController::class, 'logout'])->name('logout');

Route::group(['prefix' => '', 'middleware' => 'auth'], function () {
   

});

Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function () {




});


Route::get('lang/{locale}', [LanguageController::class, 'swap']);
Route::group(['prefix' => 'api/', 'middleware' => 'auth'], function () {


});


// ----------------------------- DEFAULT ------------------------------------

Route::get('/admin', [App\Http\Controllers\Admin\AdminRegistrationController::class, 'home']);


// new controller
Route::get('/', [App\Http\Controllers\Admin\UserController::class, 'home']);
Route::get('/blog', [App\Http\Controllers\Admin\UserController::class, 'blog']);
Route::get('/vendor-load-list', [App\Http\Controllers\Admin\UserController::class, 'vendor_list']);
Route::get('/product-load-list', [App\Http\Controllers\Admin\UserController::class, 'product_list']);


Route::get('/single_data/{id?}', [App\Http\Controllers\Admin\UserController::class, 'single_data']);
Route::get('/blog_single_profile/{id?}', [App\Http\Controllers\Admin\UserController::class, 'blog_single_profile']);

Route::get('/contact_us', [App\Http\Controllers\Admin\UserController::class, 'contact']);
Route::get('/term_condition', [App\Http\Controllers\Admin\UserController::class, 'term_condition']);
Route::get('/privacy_policy', [App\Http\Controllers\Admin\UserController::class, 'privacy_policy']);


// Route::get('/sub-admin', [App\Http\Controllers\Admin\UserController::class, 'index']);
Route::get('/admin/add', [App\Http\Controllers\Admin\UserController::class, 'add']);
Route::post('/admin/submit', [App\Http\Controllers\Admin\UserController::class, 'save']);
Route::get('/sub_admin/edit', [App\Http\Controllers\Admin\UserController::class, 'edit']);
Route::get('/sub_admin/delete/{id}', function ($id) {
    DB::table('users')->delete($id);
    $find = DB::table('user_access')->where('userId', $id)->first();
    DB::table('user_access')->delete($find->id);

    return redirect('/sub-admin');
});
Route::post('/sub_admin/change-status/{id}', [App\Http\Controllers\Admin\UserController::class, 'changeStatus']);

Route::post('/follow', [App\Http\Controllers\Admin\UserController::class, 'follow'])->name('follow');
Route::post('/unfollow', [App\Http\Controllers\Admin\UserController::class, 'unfollow'])->name('unfollow');


Route::get('/state', [App\Http\Controllers\Admin\VendorController::class, 'state']);
Route::post('/state/submit', [App\Http\Controllers\Admin\VendorController::class, 'submit']);
// Route::get('/city', [App\Http\Controllers\Admin\VendorController::class, 'city']);
Route::post('/city/submit', [App\Http\Controllers\Admin\VendorController::class, 'submitcity']);
Route::get('/city/add', [App\Http\Controllers\Admin\VendorController::class, 'addcity']);
// Route::get('/vendor-list1', [App\Http\Controllers\Admin\VendorController::class, 'index1']);
Route::get('/vendor/add', [App\Http\Controllers\Admin\VendorController::class, 'add']);
Route::post('/vendor/submit', [App\Http\Controllers\Admin\VendorController::class, 'save']);
Route::get('/vendor/edit', [App\Http\Controllers\Admin\VendorController::class, 'edit']);
Route::get('/city/edit', [App\Http\Controllers\Admin\VendorController::class, 'cityedit']);


Route::get('/profile-review/{id}', function ($id) {
   $user = DB::table('review')
    ->leftJoin('users', 'review.user_id', '=', 'users.id')
    ->select('users.*', 'review.*')
    ->where('review.vendor_id', '=', $id)
        ->get();

    return view('Index.profile-review',['user'=>$user]);
});
Route::get('/product-review/{id}', function ($id) {
    $user = DB::table('product_review')
     ->leftJoin('users', 'product_review.user_id', '=', 'users.id')
     ->select('users.name as user_name', 'product_review.*')
     ->where('product_review.vendor_id', '=', $id)
     ->get();
 
     return view('Index.product-review',['user'=>$user]);
 });

 Route::get('/manage-post', function () {
    $data = DB::table('post_requiremen')->where('user_id',Auth::id())->get();
     return view('Index.manage-post',['data'=>$data]);
 });

 Route::get('/my_product/{id}', function ($id) {
    $data = DB::table('product')->where('vendor_id',$id)->get();
     return view('Index.my-product',['data'=>$data]);
 });
Route::get('/vendor/delete/{id}', function ($id) {
    $vendor = DB::table('vendor_details')->delete($id);
    return redirect('/vendor-list');
});
Route::get('/city/delete/{id}', function ($id) {
    $vendor = DB::table('cities')->delete($id);
    return redirect('/city');
});

Route::get('/vendor/details', [App\Http\Controllers\Admin\VendorController::class, 'vendor_details']);
Route::get('/changeStatus', [App\Http\Controllers\Admin\VendorController::class, 'changeStatus'])->name("changeStatus");
Route::get('/paidUnpaid', [App\Http\Controllers\Admin\VendorController::class, 'paidUnpaid'])->name("paidUnpaid");


// Route::get('/user-list', [App\Http\Controllers\Admin\UsersController::class, 'index']);
Route::get('/user/add', [App\Http\Controllers\Admin\UsersController::class, 'add']);
Route::post('/user/submit', [App\Http\Controllers\Admin\UsersController::class, 'save']);
Route::get('/user/edit', [App\Http\Controllers\Admin\UsersController::class, 'edit']);
Route::get('/main_user/delete/{id}', function ($id) {
    $user = DB::table('users')->delete($id);
    return redirect('/user-list');
});


// Route::get('/category-list', [App\Http\Controllers\Admin\CategoryController::class, 'index']);
Route::get('/category/add', [App\Http\Controllers\Admin\CategoryController::class, 'add']);
Route::post('/category/submit', [App\Http\Controllers\Admin\CategoryController::class, 'save']);
Route::get('/category/edit', [App\Http\Controllers\Admin\CategoryController::class, 'edit']);
Route::get('/category/delete/{id}', function ($id) {
    $category = DB::table('category')->delete($id);
    return redirect('/category-list');
});
Route::post('/categorys/change-status/{id}', [App\Http\Controllers\Admin\CategoryController::class, 'changeStatus']);

Route::post('/subcategory/show/{id}', [App\Http\Controllers\Admin\subcategoryController::class, 'show']);
// Route::get('/sub-category-list', [App\Http\Controllers\Admin\subcategoryController::class, 'index']);
Route::get('/sub-category/add', [App\Http\Controllers\Admin\subcategoryController::class, 'add']);
Route::post('/sub-category/submit', [App\Http\Controllers\Admin\subcategoryController::class, 'save']);
Route::get('/sub-category/edit', [App\Http\Controllers\Admin\subcategoryController::class, 'edit']);
Route::get('/sub-category/delete/{id}', function ($id) {
    $category = DB::table('subcategory')->delete($id);
    return redirect()->back();
});
Route::post('/sub-categorys/change-status/{id}', [App\Http\Controllers\Admin\subcategoryController::class, 'changeStatus']);


// Route::get('/blog-list', [App\Http\Controllers\Admin\BlogController::class, 'index']);
Route::get('/blog/add', [App\Http\Controllers\Admin\BlogController::class, 'add']);
Route::post('/blog/submit', [App\Http\Controllers\Admin\BlogController::class, 'save']);
Route::get('/blog/edit', [App\Http\Controllers\Admin\BlogController::class, 'edit']);
Route::get('/blogs/delete/{id}', function ($id) {
    $blog = DB::table('blog')->delete($id);
    return redirect('/blog-list');
});
// Route::post('/blogs/change-status/{id}', [App\Http\Controllers\Admin\BlogController::class, 'changeStatus']);

// Route::get('/faqs-list', [App\Http\Controllers\Admin\FAQsController::class, 'index']);
Route::get('/faqs/add', [App\Http\Controllers\Admin\FAQsController::class, 'add']);
Route::post('/faqs/submit', [App\Http\Controllers\Admin\FAQsController::class, 'save']);
Route::get('/faqs/edit', [App\Http\Controllers\Admin\FAQsController::class, 'edit']);
Route::get('/faqss/delete/{id}', function ($id) {
    $faqs = DB::table('faq')->delete($id);
    return redirect('/faqs-list');
});

// Route::get('/product-list', [App\Http\Controllers\Admin\ProductController::class, 'index']);
Route::get('/product/add', [App\Http\Controllers\Admin\ProductController::class, 'add']);
Route::get('/product/add1', [App\Http\Controllers\Admin\ProductController::class, 'add1']);
Route::post('/product/submit', [App\Http\Controllers\Admin\ProductController::class, 'save']);
Route::get('/product/edit', [App\Http\Controllers\Admin\ProductController::class, 'edit']);
Route::get('/products/delete/{id}', function ($id) {
    $product = DB::table('create_product_question')->delete($id);
   return redirect('/product-category-question');
});
Route::get('/equipes/search', [App\Http\Controllers\Admin\ProductController::class, 'search']);
Route::get('/answer/search', [App\Http\Controllers\Admin\ProductController::class, 'answer_search']);


// Route::get('/Subscription-Category', [App\Http\Controllers\Admin\SubscriptionController::class, 'index']);
Route::get('/Subscription/add', [App\Http\Controllers\Admin\SubscriptionController::class, 'add']);
Route::post('/Subscription/submit', [App\Http\Controllers\Admin\SubscriptionController::class, 'save']);
Route::get('/Subscription/edit', [App\Http\Controllers\Admin\SubscriptionController::class, 'edit']);
Route::get('/Subscriptions/delete/{id}', function ($id) {
    $Subscription = DB::table('subscription')->delete($id);
    return redirect('/Subscription-Category');
});

// Route::get('/Subscription-month', [App\Http\Controllers\Admin\MonthController::class, 'index']);
// Route::get('/Month/add', [App\Http\Controllers\Admin\MonthController::class, 'add']);
// Route::post('/Subscription/submit', [App\Http\Controllers\Admin\MonthController::class, 'save']);
// Route::get('/Month/edit', [App\Http\Controllers\Admin\MonthController::class, 'edit']);
// Route::get('/Months/delete/{id}', function ($id) {
//     $month = DB::table('subscription_monthly')->delete($id);
//     return redirect('/Subscription-month');
// });

// Route::get('/Subscription-total-list', [App\Http\Controllers\Admin\SubscriptionTotalController::class, 'index']);
Route::get('/Subscription-total/add', [App\Http\Controllers\Admin\SubscriptionTotalController::class, 'add']);
Route::post('/Subscription-total/submit', [App\Http\Controllers\Admin\SubscriptionTotalController::class, 'save']);
Route::get('/Subscription-total/edit', [App\Http\Controllers\Admin\SubscriptionTotalController::class, 'edit']);
Route::get('/Subscription-totals/delete/{id}', function ($id) {
    $Subscription_total = DB::table('subscription_plain')->delete($id);
    return redirect('/Subscription-total-list');
});

// Route::get('/caller-list', [App\Http\Controllers\Admin\CallerController::class, 'index']);
Route::get('/caller/add', [App\Http\Controllers\Admin\CallerController::class, 'add']);
Route::post('/caller/submit', [App\Http\Controllers\Admin\CallerController::class, 'save']);
Route::get('/caller/edit', [App\Http\Controllers\Admin\CallerController::class, 'edit']);
Route::get('/callers/delete/{id}', function ($id) {
    $Subscription_total = DB::table('callers')->delete($id);
    return redirect('/caller-list');
});
Route::get('/caller/send', [App\Http\Controllers\Admin\CallerController::class, 'send']);
Route::get('/vendor/send', [App\Http\Controllers\Admin\VendorController::class, 'send']);
Route::post('/send/submit', [App\Http\Controllers\Admin\CallerController::class, 'caller_clint']);

Route::get('/signup', [App\Http\Controllers\Admin\RegistrationController::class, 'Registration']);
Route::post('/otp/send', [App\Http\Controllers\Admin\RegistrationController::class, 'otp']);
Route::post('/signup/submit', [App\Http\Controllers\Admin\RegistrationController::class, 'save']);


Route::get('/login', [App\Http\Controllers\Admin\RegistrationController::class, 'login']);
Route::post('/login/submit', [App\Http\Controllers\Admin\RegistrationController::class, 'login_user']);
Route::post('otp/send/login', [App\Http\Controllers\Admin\RegistrationController::class, 'login_otp']);
// Route::get('/reset_page', [App\Http\Controllers\Admin\RegistrationController::class, 'reset_page']);
// Route::post('/reset/submit', [App\Http\Controllers\Admin\RegistrationController::class,'reset'])->name('reset');
Route::any('/logout', [App\Http\Controllers\Admin\RegistrationController::class, 'logout'])->name('logout');
// Route::get('/logout', [App\Http\Controllers\Admin\RegistrationController::class, 'logout'])->name('logout');


Route::get('/freelisting', function () {
    return view('Index.freelisting');
});

Route::get('/freelistform', function () {
    return view('Index.freelistingform');
});

Route::get('/freelisting_step1', function () {
    return view('Index.freelisting_step1');
});


Route::get('/freelisting_form1', function () {
    return view('Index.freelisting_btn1');
});

Route::get('/freelisting_form2', function () {
    return view('Index.freelisting_btn2');
});

Route::get('/freelisting_form3', function () {
    return view('Index.freelisting_btn3');
});

Route::get('/freelisting_form4', function () {
    return view('Index.freelisting_btn4');
});
Route::get('vendor/details/{id}', function () {
    return view('Index.vendor_details');
});

Route::get('/Myaccount', function () {
    return view('Index.vendor_account');
});

Route::get('/About-us', function () {
    return view('Index.about');
});

Route::get('/subscription', [App\Http\Controllers\Admin\SubscriptionTotalController::class, 'adds']);

//Reoptimized class loader:
Route::get('/optimize', function () {
    $exitCode = Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});




Route::get('/post-requirement-list', [App\Http\Controllers\Admin\PostRequirementController::class, 'index']);
Route::get('/post-requirement/add', [App\Http\Controllers\Admin\PostRequirementController::class, 'add']);
Route::post('/post-requirement/submit', [App\Http\Controllers\Admin\PostRequirementController::class, 'save']);
Route::get('/post-requirement/edit', [App\Http\Controllers\Admin\PostRequirementController::class, 'edit']);
Route::get('/post-requirements/delete/{id}', function ($id) {
    $Subscription_total = DB::table('post_requiremen')->delete($id);
    return redirect('/post-requirement-list');
});
Route::get('/post-requirement/send', [App\Http\Controllers\Admin\PostRequirementController::class, 'send']);
Route::post('requirement/send/submit', [App\Http\Controllers\Admin\PostRequirementController::class, 'post_clint']);
Route::get('/publice/send/{post_id}', [App\Http\Controllers\Admin\PostRequirementController::class, 'public_data']);

Route::post('/listing_form1/submit', [App\Http\Controllers\Admin\FormController::class, 'save']);
Route::post('buliding-and-material-form', [App\Http\Controllers\Admin\FormController::class, 'vendor_form'])->name('buliding-and-material-form');
Route::post('architect-and-interior-form', [App\Http\Controllers\Admin\FormController::class, 'vendor_form'])->name('architect-and-interior-form');


Route::get('vendorinfo/{id}/{catid}', [App\Http\Controllers\Admin\VendorController::class, 'vendorinfo']);

Route::get('productinfo/{id}', [App\Http\Controllers\Admin\ProductController::class, 'productinfo']);

// Route::get('/Subscription-city', [App\Http\Controllers\Admin\SubscriptionCityController::class, 'index']);
Route::get('/Subscription-city/add', [App\Http\Controllers\Admin\SubscriptionCityController::class, 'add']);
Route::post('/Subscription-city/submit', [App\Http\Controllers\Admin\SubscriptionCityController::class, 'save']);
Route::get('/Subscription-city/edit', [App\Http\Controllers\Admin\SubscriptionCityController::class, 'edit']);
Route::get('/Subscription-city/delete/{id}', function ($id) {
    $Subscription_total = DB::table('subscription_city')->delete($id);
    return redirect('/Subscription-city');
});


// Route::get('/subscription-plan', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'index']);
Route::get('/subscription-plan/add', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'add']);
Route::post('/subscription-plan/submit', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'save']);
Route::get('/subscription-plan/edit', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'edit']);
Route::get('/subscription-plan/delete/{id}', function ($id) {
    $Subscription_total = DB::table('subscription_city')->delete($id);
    return redirect('/subscription-plan');
});
Route::get('/city/plan', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'city_plan']);
Route::get('/city/plan_update', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'city_plan']);



// Route::get('/create-product-category', [App\Http\Controllers\Admin\CreateProductCategory::class, 'index_category']);
Route::get('/create-product-category/add', [App\Http\Controllers\Admin\CreateProductCategory::class, 'add_category']);
Route::post('/create-product-category/submit', [App\Http\Controllers\Admin\CreateProductCategory::class, 'save_cat']);

// Route::get('/product-category-question', [App\Http\Controllers\Admin\CreateProductCategory::class, 'index_quest']);
Route::get('/product-category-question/add', [App\Http\Controllers\Admin\CreateProductCategory::class, 'add_quest']);
Route::post('/product-category-question/submit', [App\Http\Controllers\Admin\CreateProductCategory::class, 'save_quest']);

// Route::get('/product-category-answer', [App\Http\Controllers\Admin\CreateProductCategory::class, 'index_ans']);
Route::get('/product-category-answer/add', [App\Http\Controllers\Admin\CreateProductCategory::class, 'add_ans']);
Route::post('/product-category-answer/submit', [App\Http\Controllers\Admin\CreateProductCategory::class, 'save_ans']);
Route::get('/create-product-category/edit', [App\Http\Controllers\Admin\CreateProductCategory::class, 'edit']);
Route::get('/create-product-category/delete/{id}', function ($id) {
    $Subscription_total = DB::table('create_product_category')->delete($id);
    return redirect('/create-product-category');
});
Route::get('/admin/login', [App\Http\Controllers\Admin\AdminRegistrationController::class, 'admin_login'])->name('admin.login');
Route::post('/checklogin', [App\Http\Controllers\Admin\AdminRegistrationController::class, 'checklogin']);


Route::post('/save-review', [App\Http\Controllers\Admin\VendorController::class, 'saveReview'])->name('save-review');
Route::post('/save-product-review', [App\Http\Controllers\Admin\VendorController::class, 'saveproductReview'])->name('save-product-review');
Route::post('/vendor/send/submit', [App\Http\Controllers\Admin\VendorController::class, 'vendor_client']);
Route::get('/caller/send1', [App\Http\Controllers\Admin\CallerController::class, 'send1']);
Route::post('/send/submit1', [App\Http\Controllers\Admin\CallerController::class, 'caller_clint1']);
Route::get('/vendor/send1', [App\Http\Controllers\Admin\VendorController::class, 'send1']);
Route::post('/vendor/send/submit1', [App\Http\Controllers\Admin\VendorController::class, 'vendor_client1']);

Route::get('/reports', [App\Http\Controllers\Admin\ReportsController::class, 'index']);

Route::get('/vendor-list', [App\Http\Controllers\Admin\VendorController::class, 'index']);
Route::get('/dashboard', function () {      // Your logic goes here   
    return view('Dashboard.Dashboard');
})->name('dashboard');
Route::get('/blog-list', [App\Http\Controllers\Admin\BlogController::class, 'index']);
Route::get('/sub-admin', [App\Http\Controllers\Admin\UserController::class, 'index']);
Route::get('/user-list', [App\Http\Controllers\Admin\UsersController::class, 'index']);
Route::get('/category-list', [App\Http\Controllers\Admin\CategoryController::class, 'index']);
Route::get('/product-list', [App\Http\Controllers\Admin\ProductController::class, 'index']);
Route::get('/create-product-category', [App\Http\Controllers\Admin\CreateProductCategory::class, 'index_category']);
Route::get('/product-category-answer', [App\Http\Controllers\Admin\CreateProductCategory::class, 'index_ans']);
Route::get('/product-category-question', [App\Http\Controllers\Admin\CreateProductCategory::class, 'index_quest']);
Route::get('/Subscription-Category', [App\Http\Controllers\Admin\SubscriptionController::class, 'index']);
Route::get('/sub-category-list', [App\Http\Controllers\Admin\subcategoryController::class, 'index']);
Route::get('/Subscription-total-list', [App\Http\Controllers\Admin\SubscriptionTotalController::class, 'index']);
Route::get('/post-requirement-list', [App\Http\Controllers\Admin\PostRequirementController::class, 'index']);
Route::get('/Subscription-city', [App\Http\Controllers\Admin\SubscriptionCityController::class, 'index']);
Route::get('/subscription-plan', [App\Http\Controllers\Admin\SubscriptionPlanController::class, 'index']);
Route::get('/faqs-list', [App\Http\Controllers\Admin\FAQsController::class, 'index']);
Route::get('/caller-list', [App\Http\Controllers\Admin\CallerController::class, 'index']);
Route::get('/vendor-list1', [App\Http\Controllers\Admin\VendorController::class, 'index1']);
Route::get('/city', [App\Http\Controllers\Admin\VendorController::class, 'city']);



Route::get('myprofile/{id}', [App\Http\Controllers\Admin\VendorController::class, 'myprofile']);
Route::get('analytic/{id}', [App\Http\Controllers\Admin\VendorController::class, 'analytic']); 
Route::get('subcategory/{id}', [App\Http\Controllers\Admin\subcategoryController::class, 'showsubcategory']); 
Route::post('/reports/submit', [App\Http\Controllers\Admin\ReportsController::class, 'save']);
Route::any('/click-count', [App\Http\Controllers\Admin\Click_countController::class, 'count']);
Route::any('/vendor-count', [App\Http\Controllers\Admin\Click_countController::class, 'index']);
