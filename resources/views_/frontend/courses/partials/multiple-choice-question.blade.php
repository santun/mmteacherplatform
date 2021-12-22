@foreach($currentQuiz->questions as $key => $question)
    <div>
        <div class="row mt-5">
            <div class="col-md-12">
                {!! $question->title !!}
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-12">
                @foreach($question->multiple_answers as $answer)
                    <div>
                        <input type="checkbox" name="question{{$question->id}}"
                               value="{{$answer->answer}}"
                               class="mt-1 mr-2" id="question_{{$answer->id}}">
                        <label for="question_{{$answer->id}}">{{ $answer->answer }}</label>
                    </div>
                @endforeach
            </div>
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
    <button class="btn btn-primary btn-sm" id="check-answer" onclick="checkAnswer({{ $currentQuiz }})"
        disabled="true"
    >Check Answer</button>
</div>

@section('script')
    @parent
    <script>

        $(document).ready(function () {
            let quiz = @json($currentQuiz);

            for (let i = 0; i < quiz.questions.length; i++) {
                let selector = 'input[name=' + "question" + quiz.questions[i].id + ']';
                $(selector).on('change', function () {
                    let counter = checkAllInput();
                    if (counter === quiz.questions.length) {
                        $('#check-answer').prop('disabled', false);
                    } else {
                        $('#check-answer').prop('disabled', true);
                    }
                });
            }

            function checkAllInput() {
                let counter = 0;

                for (let i = 0; i < quiz.questions.length; i++) {
                    let selector = 'input[name=' + "question" + quiz.questions[i].id + ']';
                    if($(selector + ":checked").val() !== undefined) {
                        counter++;
                    }
                }
                return counter;
            }
        });

        function checkAnswer(quiz) {
            $.ajaxSetup({
                'X-CSRF-TOKEN': "{{ @csrf_token() }}"
            });

            let url = "{{ route('quiz.check-answer') }}";
            let checkUserAnswer = function (userAnswers, actualAnswers) {
                let isRight = false;
                if(userAnswers.length !== actualAnswers.length) {
                    return isRight;
                }
                for(let i = 0; i < userAnswers.length; i ++) {
                    if(actualAnswers[i] === userAnswers[i]) {
                        isRight = true;
                    } else {
                        isRight = false;
                    }
                }

                return isRight;
            };
            $.ajax({
                type:"POST",
                url: url,
                data: {
                    quiz: quiz.id
                },

                success: function (response) {
                    for (let i = 0; i < response.question.length; i++) {
                        let element = '#question' + response.question[i].id;
                        let userAnswers = [];
                        $('input[name="question' + response.question[i].id +'"]:checked').each(function () {
                            userAnswers.push($(this).val());
                        });

                        let actualAnswers = response.question[i].multiple_answers
                            .filter(answer => answer.is_right_answer)
                            .map(answer => answer.answer);

                        let answerIsTrue = checkUserAnswer(userAnswers, actualAnswers);

                        if(answerIsTrue) {
                            $(element).css('color', 'darkcyan');
                        } else {
                            $(element).css('color', 'darkred');
                        }
                        $(element).html('The right answer is ' + actualAnswers);
                        $('#description' + response.question[i].id).html(response.question[i].description);
                    }
                }
            });
        }
    </script>
@endsection
