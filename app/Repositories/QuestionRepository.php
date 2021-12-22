<?php

namespace App\Repositories;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\CoursePrivacy;
use App\Models\MultipleChoiceAnswer;
use App\Models\TrueFalseAnswer;
use App\Models\BlankAnswer;
use App\Models\ShortAnswer;
use App\Models\MultipleAnswer;
use App\Models\RearrangeAnswer;
use App\Models\MatchingAnswer;
use App\Models\Quiz;
use App\Models\Question;
use App\User;
use Carbon\Carbon;
use DB;


class QuestionRepository
{
    protected $model;

    public function __construct(Question $model)
    {
        $this->model = $model;
    }

    public function saveRecord($request, $id = null)
    {
        
        if (isset($id)) {
            $this->model = $this->find($id);
        } else {
            $this->model->user_id = auth()->user()->id;
        }

        $this->model->title = $request->title;
        $this->model->description = $request->description;
        $this->model->quiz_id = $request->quiz_id;
        if ($file = $request->file('attached_file')) {
            $this->model->addMediaFromRequest('attached_file')->toMediaCollection('question_attached_file');
        }
        $this->model->save();
        if ($request->quiz_type == Quiz::MULTIPLE_CHOICE) {
            $this->saveMultipleAnswer($request);
        } else if($request->quiz_type == Quiz::TRUE_FALSE){
            $this->saveTrueFalseAnswer($request);
        } else if($request->quiz_type == Quiz::SHORT_QUESTION){
            $this->saveShortAnswer($request);
        } else if($request->quiz_type == Quiz::BLANK){
            $this->saveBlankAnswer($request);
        } else if($request->quiz_type == Quiz::REARRANGE){
            $this->saveRearrangeAnswer($request);
        } else if($request->quiz_type == Quiz::MATCHING){
            $this->saveMatching($request);
        }   
    }

    private function saveMultipleAnswer($request)
    {

        $multiple_answers = [];
        if($request->answer_A){
            $multiple_answers[] = new MultipleAnswer([
                                                    'name' => 'A',
                                                    'answer' => $request->answer_A,
                                                    'is_right_answer' => in_array('A', $request->right_answer)
                                                ]);
        }

        if($request->answer_B){
            $multiple_answers[] = new MultipleAnswer([
                                                    'name' => 'B',
                                                    'answer' => $request->answer_B,
                                                    'is_right_answer' => in_array('B', $request->right_answer)
                                                ]);
        }

        if($request->answer_C){
            $multiple_answers[] = new MultipleAnswer([
                                                    'name' => 'C',
                                                    'answer' => $request->answer_C,
                                                    'is_right_answer' => in_array('C', $request->right_answer)
                                                ]);
        }

        if($request->answer_D){
            $multiple_answers[] = new MultipleAnswer([
                                                    'name' => 'D',
                                                    'answer' => $request->answer_D,
                                                    'is_right_answer' => in_array('D', $request->right_answer)
                                                ]);
            if($request->answer_E){
                $multiple_answers[] = new MultipleAnswer([
                                                        'name' => 'E',
                                                        'answer' => $request->answer_E,
                                                        'is_right_answer' => in_array('E', $request->right_answer)
                                                    ]);
            }
        }
        if (count($multiple_answers) !== 0 ) {
                if($this->model->multiple_answers != NULL)
                    $this->model->multiple_answers->each->delete();
                $this->model->multiple_answers()->saveMany($multiple_answers);
        }
    }

    private function saveTrueFalseAnswer($request)
    {
        $answers = [
            'answer' => $request->true_or_false,
        ];

        if ($this->model->true_false_answer === null) {
                $answer = new TrueFalseAnswer($answers);
                $this->model->true_false_answer()->save($answer);
        } else {
            $this->model->true_false_answer->update($answers);
        }
    }

    private function saveShortAnswer($request)
    {
        $answers = [
            'answer' => $request->short_answer,
        ];

        if ($this->model->short_answer === null) {
                $answer = new ShortAnswer($answers);
                $this->model->short_answer()->save($answer);
        } else {
            $this->model->short_answer->update($answers);
        }
    }

    public function saveBlankAnswer($request)
    {
        $answers= [
            'answer' => $request->blank_answer,
        ];
        if ($this->model->blank_answer === null) {
                $answer = new BlankAnswer($answers);
                $this->model->blank_answer()->save($answer);
        } else {
            $this->model->blank_answer->update($answers);
        }
    }

    private function saveRearrangeAnswer($request)
    {
        $answers = [
            'answer' => [ 
                        $request->answer_one,
                        $request->answer_two,
                        $request->answer_three
                    ]
        ];

        if($request->answer_four){
            $answers['answer'][] = $request->answer_four;
            if($request->answer_five){
                $answers['answer'][] = $request->answer_five;
            }    
        }

        if ($this->model->rearrange_answer === null) {
                $answer = new RearrangeAnswer($answers);
                $this->model->rearrange_answer()->save($answer);
        } else {
            $this->model->rearrange_answer->update($answers);
        }
    }

    private function saveMatching($request)
    {
        $answers = [
            'answer' => [
                [ 'name_first' => 'A', 'first' => $request->matching_A, 'name_second' => '1', 'second' => $request->matching_One ],
                [ 'name_first' => 'B', 'first' => $request->matching_B, 'name_second' => '2', 'second' => $request->matching_Two ],
                [ 'name_first' => 'C', 'first' => $request->matching_C, 'name_second' => '3', 'second' => $request->matching_Three ]
            ]
        ];

        if($request->matching_D && $request->matching_Four){
            $answers['answer'][] = [ 'name_first' => 'D', 'first' => $request->matching_D, 'name_second' => '3', 'second' => $request->matching_Four ];
            
            if($request->matching_E && $request->matching_Five){
                $answers['answer'][] = [ 'name_first' => 'E', 'first' => $request->matching_E, 'name_second' => '4', 'second' => $request->matching_Five ];
            }
        }

        if ($this->model->matching_answer === null) {
                $answer = new MatchingAnswer($answers);
                $this->model->matching_answer()->save($answer);
        } else {
            $this->model->matching_answer->update($answers);
        }
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function getKeyId()
    {
        return $this->model->id;
    }

    public function getByQuiz($request, $quiz_id)
    {
        $posts = $this->model->where('quiz_id', $quiz_id)
                                ->sortable(['updated_at' => 'desc'])
                                ->get();
        //                         ->paginate($request->input('limit'));
        // $posts->appends($request->all());
        return $posts;
    }


}
