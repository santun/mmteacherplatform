@foreach($currentQuiz->questions as $key => $question)
    <div>
        <div class="row mt-5">
            <div class="col-md-12">
                {!! $question->title !!}
            </div>
        </div>
        <div class="row mt-3">
            @if($arr = $question->rearrange_answer->answer)
                @foreach($question->rearrange_answer->answer as $key => $answer)
                    @php
                        shuffle($arr);
                    @endphp
                    <div class="col-md-12 mt-3">
                        <div class="d-flex">
                           <div class="mt-2 mr-3">{{ $key + 1 }}</div>
                            <select name="" id="answer{{ $question->id . $key }}" class="form-control rearrange-items">
                                <option value="">SELECT ANSWER</option>
                                @foreach($arr as $matchAnswer)
                                    <option value="{{$matchAnswer}}" class="rearrange-item" title="{{ $matchAnswer }}">
                                    {{ \Illuminate\Support\Str::limit($matchAnswer, 50, ' ...') }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mt-3 ml-4">
                            <span id="{{ $key . $question->id }}"></span>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="row mt-2 ml-1">
            <span id="question{{$question->id}}"></span>
        </div>
        <div class="row mt-2 ml-1">
            <span id="description{{$question->id}}"></span>
        </div>
    </div>
@endforeach

<div class="row mt-5">
    <button class="btn btn-primary btn-sm" id="check-answer" onclick="checkAnswer({{ $currentQuiz }})" disabled="true">Check Answer</button>
</div>

@section('script')
    @parent
    <script>
        $(document).on('change', '.match-items', function(e){
            check_all_selected();
        })

        function check_aswer_all_question() {
            let answerAllQuestion = true;
            document.querySelectorAll('.match-items').forEach(function(item, index){
                if(item.value == ''){
                    answerAllQuestion = false;
                }
            })
            return answerAllQuestion;
        }

        function check_all_selected() {
            if (check_aswer_all_question()) {
                $('#check-answer').prop('disabled', false);
            } else {
                $('#check-answer').prop('disabled', true);
            }
        }

        function checkAnswer(quiz) {
            if(check_aswer_all_question()){
                $.ajaxSetup({
                    'X-CSRF-TOKEN': "{{ @csrf_token() }}"
                });

                let url = "{{ route('quiz.check-answer') }}";
                $.ajax({
                    type:"POST",
                    url: url,
                    data: {
                        quiz: quiz.id
                    },

                    success: function (response) {
                        // console.log(response);
                        for (let i = 0; i < response.question.length; i++) {
                            let keys = Object.keys(response.question[i].rearrange_answer.answer);
                            for (let j = 0; j < keys.length; j++) {
                                let element = '#' + keys[j] + response.question[i].id;
                                let answerElement = '#answer' + response.question[i].id + keys[j];
                                if(response.question[i].rearrange_answer.answer[keys[j]] === $(answerElement).val()) {
                                    $(element).css('color', 'darkcyan');
                                } else {
                                    $(element).css('color', 'darkred');
                                }
                                $(element).html('The right answer is ' + response.question[i].rearrange_answer.answer[keys[j]]);
                            }
                            $('#description' + response.question[i].id).html(response.question[i].description);
                        }
                    }
                });
            }
        }
    </script>
@endsection
