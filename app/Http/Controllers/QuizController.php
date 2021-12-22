<?php

namespace App\Http\Controllers;

use App\Models\Lecture;
use App\Models\Quiz;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\Models\Media;

class QuizController extends Controller
{
    private $resource;

    public function __construct(Quiz $quiz)
    {
        $this->middleware('auth');
        $this->resource = $quiz;
    }

    public function answerQuiz($id)
    {
        $currentQuiz = $this->resource->with('questions')->findOrFail($id);
        $currentLecture = $currentQuiz->lecture;
        $course = $currentQuiz->course;
        $lectures = $course->lectures;
        $userLectures = auth()->user()->learningLectures;
        $lecturesMedias = Media::all()->where('model_type', Lecture::class);
        if ($currentLecture) {
            $nextQuiz = $currentLecture->quizzes()->orderBy('id')->where('id', '>', $currentQuiz->id)->first();
        } else {
            $nextQuiz = $course->quizzes()->orderBy('id')->where('id', '>', $currentQuiz->id)->where('lecture_id', null)->first();
        }
        $previousQuiz = $course->quizzes()->orderBy('id', 'desc')->where('id', '<', $currentQuiz->id)->first();
        $downloadOption = $course->downloadable_option;
        if($currentLecture) {
            $nextLecture = $course->lectures()->orderBy('id')->where('id', '>', $currentLecture->id)->first();
            $previousLecture = $course->lectures()->orderBy('id', 'desc')->where('id', '<', $currentLecture->id)->first();

            return view('frontend.courses.quiz', compact(
                'currentQuiz', 'currentLecture', 'course', 'nextLecture', 'previousLecture', 'nextQuiz',
                'previousQuiz', 'lectures', 'userLectures', 'lecturesMedias', 'downloadOption'
            ));
        }
        return view('frontend.courses.quiz', compact(
            'currentQuiz', 'currentLecture', 'course', 'nextQuiz','previousQuiz', 'lectures', 'userLectures',
            'lecturesMedias', 'downloadOption'
        ));
    }

    public function checkAnswer(Request $request)
    {
        $quiz = $this->resource->findOrFail($request->quiz);
        $questions = [];

        if($quiz->type == 'true_false') {
            foreach($quiz->questions()->with('true_false_answer')->get() as $question) {
                $questions[] = $question;
            }
        } elseif ($quiz->type == 'multiple_choice') {
            foreach($quiz->questions()->with('multiple_answers')->get() as $question) {
                $questions[] = $question;
            }
        } elseif($quiz->type == 'matching') {
            foreach($quiz->questions()->with('matching_answer')->get() as $question) {
                $questions[] = $question;
            }
        } elseif($quiz->type == 'blank') {
            foreach($quiz->questions()->with('blank_answer')->get() as $question) {
                $questions[] = $question;
            }
        } else if($quiz->type == 'short_question') {
            foreach($quiz->questions()->with('short_answer')->get() as $question) {
                $questions[] = $question;
            }
        } else {
            foreach($quiz->questions()->with('rearrange_answer')->get() as $question) {
                $questions[] = $question;
            }
        }

        return response()->json(['question' => $questions]);
    }
}
