<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Imports\UsersImport;
use Maatwebsite\Excel\Facades\Excel;
use App\User;
use Illuminate\Support\Facades\Hash;

class ImportUserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['permission:import_user']);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.import.user');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        ini_set('max_execution_time', 0);
        set_time_limit(0);

        $rules = [
            'uploaded_file' => 'required|mimetypes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            // 'batch_no' => 'required',
        ];

        $messages = [
            'required' => 'The :attribute field is required.',
            'mimetypes' => 'The :attribute must be Excel file with .xlsx extension.'
        ];

        $this->validate($request, $rules, $messages);

        try {
            // $import = Excel::import(new UsersImport, request()->file('uploaded_file'));

            /*             $import = new UsersImport();
                        Excel::import($import, request()->file('uploaded_file')); */

            $collection = (new UsersImport)->toCollection(request()->file('uploaded_file'));

            $collection = $collection->first();

            $old_count = 0;
            $new_count = 0;
            $total = $collection->count();

            $columns = [
                "name",
                "username",
                "email",
                "password",
                "mobile_no",
                "notification_channel",
                "type_of_users",
                "education_college",
                "year_of_study_teaching",
                "approved",
                "verified",
                "accessible_right",
                "subjects"
            ];


            if (isset($collection) && $collection->count() > 0) {
                $firstRecord = $collection->first();

                if ($columns != $firstRecord->keys()->toArray()) {
                    return redirect()->route('admin.user.bulk-import')
                        ->with('error', "Invalid Excel format. "
                        ."Please check the columns names. (".implode(', ', $columns) .')');
                }

                $collection->each(function ($row) use (&$old_count, &$new_count) {
                    $row = $row->toArray();

                    $old = User::where('username', $row['username'])
                    ->where('email', $row['email'])->first();

                    if (!$old) {
                        $user = new User();
                        $user->name = $row['name'];
                        $user->username = $row['username'];
                        $user->email = $row['email'];
                        $user->password = Hash::make($row['password']);
                        $user->mobile_no = $row['mobile_no'];
                        $user->notification_channel = $row['notification_channel'];
                        $user->user_type = $row['type_of_users'];
                        $user->ec_college = $row['education_college'];
                        $user->suitable_for_ec_year = $row['year_of_study_teaching'];
                        $user->approved = (int)$row['approved'];
                        $user->verified = (int)$row['verified'];
                        // $user->blocked = $row['blocked'];
                        $user->type = $row['accessible_right'];
                        // $user->verification_code = $row['verification_code'];
                        // $user->email_verified_at = $row['email_verified_at'];
                        // $user->sms_verified_at = $row['sms_verified_at'];

                        $user->save();

                        if ($row['subjects']) {
                            $row['subjects'] = explode(',', $row['subjects']);

                            if (count($row['subjects'])) {
                                $user->subjects()->sync($row['subjects']);
                            }
                        }
                        $new_count++;
                    } else {
                        $old_count++;
                    }
                });
            }

            //$import->import(request()->file('uploaded_file'));
            // dd($import->errors());
        } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            $failures = $e->failures();

            foreach ($failures as $failure) {
                $failure->row(); // row that went wrong
                $failure->attribute(); // either heading key (if using heading row concern) or column index
                $failure->errors(); // Actual error messages from Laravel validator
                $failure->values(); // The values of the row that has failed.
            }
        }

        $i = 0;

        return redirect()->route('admin.user.bulk-import')
        ->with('success', "Found $total total records. $new_count record(s) inserted and $old_count duplicates skipped");
    }
}
