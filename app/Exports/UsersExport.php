<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class UsersExport implements FromView {

    protected $userRole;
    protected $userId;

    function __construct($userRole, $userId) {
        $this->userRole = $userRole;
        $this->userId = $userId;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View {
        $role = $this->userRole;
        $userId = $this->userId;
        if ($userId != null) {
            return view('user::export-view', [
                'users' =>
                User::where('id', $userId)->where('status', 1)->get()
                , 'role' => $role
            ]);
        } else {
            if ($role == 'all') {
                return view('user::export-view', [
                    'users' =>
                    User::where('status', 1)->get()
                    , 'role' => $role
                ]);
            } else {
                return view('user::export-view', [
                    'users' =>
                    User::whereHas('roles', function($q) use($role) {
                                $q->where('slug', $role);
                            })->where('status', 1)->get()
                    , 'role' => $role
                ]);
            }
        }
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection() {
        
    }

}
