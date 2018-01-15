<?php

namespace App\Http\Controllers;

use App\Traits\Helper;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    use Helper;
    private $data = [];

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->data['items'] = User::paginate(20);

        return view('users.list', $this->data);
    }

    public function getForm(Request $request, User $item)
    {
        $this->data['item'] = $item;

        return view('users.form', $this->data);
    }

    public function postForm(Request $request, User $item)
    {
        try {
            if ($request->new_password && $request->new_password == $request->confirm_password) {
                $request->merge([
                    'password' => bcrypt($request->new_password),
                ]);
            }
            $item->autoFill($request);
            $item->syncRoles((array)$request->roles);
        } catch (\Exception $e) {
            return $this->error($e->getMessage());
        }

        return $this->success('User saved.');
    }

    public function delete(Request $request, User $item)
    {
        if ($item->hasRole('admin')) {
            abort(400, 'Can\'t remove admin user.');
        }

        $item->delete();

        return $this->success('User removed.');

    }
}
