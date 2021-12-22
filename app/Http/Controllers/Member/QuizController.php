<?php

namespace App\Http\Controllers\Member;

// use Illuminate\Http\Request;
use App\Http\Requests\RequestQuiz as Request;
use App\Http\Controllers\Controller;
use App\Repositories\CourseRepository;
use App\Models\Lecture;
use App\Models\Quiz;
use App\Repositories\QuizRepository;

class QuizController extends Controller
{
    public function __construct(QuizRepository $repository, CourseRepository $courseRepository)
    {
        $this->repository = $repository;
        $this->courseRepository = $courseRepository;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($course_id)
    {
      $course = $this->courseRepository->find($course_id);
    	$quiz_types = Quiz::QUIZ_TYPES;
    	$lectures = Lecture::where('course_id', $course->id)->get()->pluck('lecture_title', 'id');
    	$lectures->prepend($course->title, '');
        return view('frontend.member.quiz.form', compact('course', 'lectures','quiz_types'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $course_id)
    {

        $request->validated();
        $this->courseRepository->find($course_id);
        $this->repository->saveRecord($request);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('member.quiz.create', $course_id)
              ->with(
                  'success',
                  __('Quiz has been successfully saved. And you are ready to create a new quiz.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('member.quiz.edit',  $id)
              ->with(
                  'success',
                  __('Quiz has been successfully saved.')
              );
        } elseif ($request->input('btnSaveAddQuestion')) {
            return redirect()->route('member.question.create', [$id])
              ->with(
                  'success',
                  __('Quiz has been successfully saved and you are ready to create a new question.')
              );
        } else {
            return redirect(route('member.course.show', $course_id).'#nav-quiz')
              ->with(
                  'success',
                  __('Quiz has been successfully saved.')
              );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = $this->repository->find($id);
        $course = $this->courseRepository->find($post->course_id);
        $quiz_types = Quiz::QUIZ_TYPES;
    	  $lectures = Lecture::where('course_id', $course->id)->get()->pluck('lecture_title', 'id');
    	  $lectures->prepend($course->title, '');
        return view('frontend.member.quiz.form', compact('course', 'post', 'lectures', 'quiz_types'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    	// dd($request->all());
        $request->validated();
        $quiz = $this->repository->find($id);
        $this->repository->saveRecord($request, $id);

        $id = $this->repository->getKeyId();

        if ($request->input('btnSaveNew')) {
            return redirect()->route('member.quiz.create', $quiz->course_id)
              ->with(
                  'success',
                  __('Quiz has been successfully updated. And you are ready to create a new quiz.')
              );
        } elseif ($request->input('btnSave')) {
            return redirect()->route('member.quiz.edit', [$id])
              ->with(
                  'success',
                  __('Quiz has been successfully updated.')
              );
        } elseif ($request->input('btnSaveAddQuestion')) {
            return redirect()->route('member.question.create', [$id])
              ->with(
                  'success',
                  __('Quiz has been successfully updated and you are ready to create a new question.')
              );
        } else {
            return redirect(route('member.course.show', $quiz->course_id).'#nav-quiz')
              ->with(
                  'success',
                  __('Quiz has been successfully updated.')
              );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = $this->repository->find($id);
        $course_id = $post->course_id;
        $post->delete();
        return redirect(route('member.course.show', $course_id). '#nav-quiz')
          ->with('success', 'Successfully deleted');
    }
}
