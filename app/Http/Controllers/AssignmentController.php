<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use App\Models\AssignmentUser;
use App\Notifications\SubmitAssignment;
use App\Repositories\CourseRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use Spatie\MediaLibrary\Models\Media;

class AssignmentController extends Controller
{
    private $assignment;
    private $assignmentUser;

    public function __construct(Assignment $assignment)
    {
        $this->assignment = $assignment;
    }

    public function show(Assignment $assignment)
    {
        $assignmentMedia = Media::all()->where('model_type', Assignment::class)->where('model_id', $assignment->id)->first();
        return view('frontend.courses.assignments.show', compact('assignment', 'assignmentMedia'));
    }

    public function submitAssignment(Request $request, Assignment $assignment)
    {
        $this->assignmentUser = auth()->user()->assignment_user->where('assignment_id', $assignment->id)->first();


        $this->validate($request, [
        'assignment_file' => 'required|mimes:pdf,docx,doc,avi,mp4,mpeg,ppt,pptx'
        ]);

        if($this->assignmentUser) {
            $this->assignmentUser->update([
                'attached_file' => $request->file('assignment_file')->getClientOriginalName()
            ]);
        } else {
            $this->assignmentUser = AssignmentUser::query()->create([
                'assignment_id' => $assignment->id,
                'user_id' => auth()->user()->id,
                'attached_file' => $request->file('assignment_file')->getClientOriginalName()
            ]);
        }

        Notification::send(User::query()->where('id', $assignment->user_id)->first(), new SubmitAssignment($this->assignmentUser));

        $this->assignmentUser->addMediaFromRequest('assignment_file')->toMediaCollection('user_assignment_attached_file');

        return redirect()->route('courses.view-assignment-feedback', $this->assignmentUser)->with('message', 'Your assignment was successfully submitted');
    }

    public function viewFeedback(AssignmentUser $assignmentUser)
    {
        $assignment = Assignment::query()->findOrFail($assignmentUser->assignment_id);
        $assignmentMedia = Media::all()->where('model_type', Assignment::class)->where('model_id', $assignment->id)->first();
        return view('frontend.courses.assignments.view-feedback', compact('assignment', 'assignmentMedia', 'assignmentUser'));
    }

    public function testPptToImage()
    {
        return view('frontend.courses.test-ppt');
    }

    public function testPostPptToImage(Request $request)
    {
        $endpoint = "https://sandbox.zamzar.com/v1/jobs";
        $apiKey = "GiVUYsF4A8ssq93FR48H";
        $sourceFile = "https://s3.amazonaws.com/zamzar-samples/sample.ppt";
        $targetFormat = "png";

        $postData = array(
            "source_file" => $sourceFile,
            "target_format" => $targetFormat
        );

        $ch = curl_init(); // Init curl
        curl_setopt($ch, CURLOPT_URL, $endpoint); // API endpoint
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as a string
        curl_setopt($ch, CURLOPT_USERPWD, $apiKey . ":"); // Set the API key as the basic auth username
        $body = curl_exec($ch);
        curl_close($ch);

        $response = json_decode($body, true);

        echo "Response:\n---------\n";
        print_r($response);
    }
}
