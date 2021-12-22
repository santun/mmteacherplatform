<form action="#">
    @foreach($currentQuiz->questions as $key => $question)
        <div>
            <div class="row mt-5">
                <div class="col-md-12">
                    {!! $question->title !!}
                </div>
            </div>
            <div class="row mt-3">
                @if($arr = $question->matching_answer->answer)
                    @foreach($question->matching_answer->answer as $key => $answer)
                        <div class="col-md-6 mt-3">
                            <div>
                                - {{ $answer['first'] }}
                            </div>
                        </div>
                        @php
                            shuffle($arr);
                        @endphp
                        <div class="col-md-6 mt-3">
                            <select name="question{{$question->id}}" required id="answer{{$question->id . $key}}" class="form-control required match-items">
                                <option value="" class="match-item">SELECT ANSWER</option>
                                @foreach($arr as $matchAnswer)
                                    <option value="{{$matchAnswer['second']}}" class="match-item" title="{{ $matchAnswer['second'] }}">
                                        {{ \Illuminate\Support\Str::limit($matchAnswer['second'], 50, ' ...') }}
                                    </option>
                                @endforeach
                            </select>
                            <span id="{{ $key . $question->id }}" class="mt-3"></span>
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
        <input type="submit" class="btn btn-primary btn-sm" id="check-answer" onclick="checkAnswer({{$currentQuiz}})" value="Check Answer" disabled="true">
    </div>
</form>

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
        $(document).ready(function () {
            let quiz = @json($currentQuiz);

            // for (let i = 0; i < quiz.questions.length; i++) {
            //     for (let j = 0, answer = Object.keys(quiz.questions[i].matching_answer.answer); j < answer.length; j++) {
            //         let selector = '#answer' + quiz.questions[i].id + answer[j];

            //         $(selector).on('change', function () {
            //             let checkInput = checkAllInput();
            //             console.log(checkInput);
            //             // if (counter === quiz.questions.length) {
            //             //     $('#check-answer').prop('disabled', false);
            //             // } else {
            //             //     $('#check-answer').prop('disabled', true);
            //             // }
            //         });
            //     }
            // }

            // function checkAllInput() {
            //     for (let i = 0; i < quiz.questions.length; i++) {
            //         for (let j = 0, answer = Object.keys(quiz.questions[i].matching_answer.answer); j < answer.length; j++) {
            //             let selector = '#answer' + quiz.questions[i].id + answer[j];
            //             console.log($(selector).val() === '');
            //             // if($(selector).val() !== undefined || $(selector).val() !== '') {
            //             //
            //             // }
            //         }
            //     }
            // }
        });

        function checkAnswer(quiz) {
            event.preventDefault();
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
                        for (let i = 0; i < response.question.length; i++) {
                            let keys = Object.keys(response.question[i].matching_answer.answer);
                            for (let j = 0; j < keys.length; j++) {
                                let element = '#' + keys[j] + response.question[i].id;
                                let answerElement = '#answer' + response.question[i].id + keys[j];
                                if(response.question[i].matching_answer.answer[keys[j]].second === $(answerElement).val()) {
                                    $(element).css('color', 'darkcyan');
                                } else {
                                    $(element).css('color', 'darkred');
                                }
                                $(element).html('The right answer is ' + response.question[i].matching_answer.answer[keys[j]].second);
                            }
                            $('#description' + response.question[i].id).html(response.question[i].description);
                        }
                    }
                });

            }
        }
    </script>
@endsection
