@foreach($currentQuiz->questions as $key => $question)
    <div>
        <div class="row mt-5">
            <div class="col-md-12">
                {!! $question->title !!}
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-md-4">
                <input type="text" class="form-control" id="answer{{$question->id}}" placeholder="Type Your Answer"/>
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
    <button class="btn btn-primary btn-sm" id="check-answer" disabled="true" onclick="checkAnswer({{$currentQuiz}})">Check Answer</button>
</div>

@section('script')
    @parent
    <script>
        $(document).ready(function () {
            let quiz = @json($currentQuiz);

            for (let i = 0; i < quiz.questions.length; i++) {
                $('#answer' + quiz.questions[i].id).keyup(function () {
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
                    if($('#answer' + quiz.questions[i].id).val() !== '') {
                        counter++;
                    }
                }
                return counter;
            }
        });

        function checkAnswer(quiz) {
            event.preventDefault();
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
                        let element = '#question' + response.question[i].id;
                        let answer = $('#answer' + response.question[i].id).val().toLowerCase();
                        if(answer !== response.question[i].blank_answer.answer.toLowerCase()) {
                            $(element).css('color', 'darkred');
                        } else {
                            $(element).css('color', 'darkcyan');
                        }
                        $(element).html('The right answer is ' + response.question[i].blank_answer.answer);
                        $('#description' + response.question[i].id).html(response.question[i].description);
                    }
                }
            });
        }

    </script>
@endsection
