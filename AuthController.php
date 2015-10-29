<?php namespace App\Http\Controllers;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\AuthUser;
use App\Model\Blog;
use Session;
use Input;
use App;
use Log;
use Mail;
class AuthController extends Controller
{

    public function showUsers()
    {
        $obj = App::make('App\Http\Services\UserService');
        $users = $obj->getAllUsers();
        return view('user.user', ['result' => $users]);
    }

    public function users()
    {
        $obj = App::make('App\Http\Services\UserService');
        $result = $obj->getUserList();
        $result->order = 'dsc';
        $result->setPath('');
        return view('admin.user', ['result' => $result]);
    }

    public function signup()
    {
        return view('signup');
    }

    public function Entry()
    {
        return view('login');
    }

    public function createblog()
    {
        return view('blogs.createblog');
    }

    public function store(Request $request)
    {
        $input = Input::all();
        $obj = App::make('App\Http\Services\UserService');
        $obj->validateField($request, $this);
        $result = $obj->checkAuthSignup($input['email'], $input['username']);
        if ($result === 0) {
            return view('signup');
        } else {
            $obj->createUser($input);
            return view('success');
        }
    }

    public function home()
    {
        return view('welcome');
    }

    public function success()
    {
        return redirect('login');
    }

    public function contactus()
    {
        return view('user.contactus');
    }

    public function aboutus()
    {
        $obj = App::make('App\Http\Services\UserService');
        return view('aboutus', ['data' => $obj->aboutus()]);
    }

    public function user()
    {
        $input = Request::all('id');
        $list = new AuthUser();
        $result = $list->getUserList($input);
        return view('user.user', ['result' => $result]);
    }

    public function editUser($userId)
    {
        $result = AuthUser::where('id', '=', $userId)->get();
        return view('user.editUser', ['result' => $result]);
    }

    public function check(Request $request)
    {
        $input = Input::get();
        $blogs = new Blog();
        $obj = App::make('App\Http\Services\UserService');
        $obj->loginValidate($request, $this);
        $result = $obj->checkAuthLogin($input);
        if (empty($result)) {
            $msg = "Enter Correct Email and Password";
            $error = "Sorry Wrong id or Password";
            Log::info('User failed to login.', ['id' => $input['email']]);
            return redirect('login')->withErrors([$error]);
        } else {
            if (!empty($result)) {
                if ($result['level_id'] == 'admin') {
                    return view('admin.manage');
                } else {
                    Session::put('id', $result['id']);
                    Session::put('level_id', $result['level_id']);
                    Session::put('name', $result['first_name'] . ' ' . $result['last_name']);
                    Session::put('status', $result['status']);
                    return redirect('home');
                }
            } else {
                return redirect('login');
            }
        }
    }

    public function term()
    {
        return view('terms');
    }

    public function delete($id)
    {
        $userId = Session::get('id');
        $obj = App::make('App\Http\Services\UserService');
        $userDeletedMsg = $obj->deleteAccount($userId);
        return view('welcome', ['message' => $userDeletedMsg]);
    }

    public function forgot()
    {
        return view('forgot');
    }
      public function logout()
    {
        Session::flush();
        return view('welcome');
    }
    public function mail()
    {
        return view('user.email');
    }
    public function sendMail(Request $request)
    {
        $id=Input::all();
        $user=AuthUser::findOrFail($id['id']);
//        $a=Mail::send('emails.reminder', ['user' => $user], function ($m) use ($user) {
//            $m->to($user->email, $user->first_name)->subject('Your Reminder!');
//                    $m->from('asasas@asa.com','mohit guleriya');
//        });
       $a= Mail::send('emails.reminder', ['user' => $user], function ($message) {
    $message->from('us@example.com', 'Laravel');

    $message->to('mohit.guleriya@bigsteptech.com')->cc('bar@example.com');
});
//        if($a)
//        {   dd(4);
////            return back()->with('msg'.'success');
//        }  else {
//        return back()->with('msg'.'success');
//        }
        }
}
