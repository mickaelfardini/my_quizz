window.onload = () => {
	var questions = $('article');
	var i = 0;
	$("#startQuizz").click(() => {
		$("#startdiv").hide();
		$(questions[i]).prop('hidden', false);
		$("#bottom-pin").prop('hidden', false);
	})

	$('.form-check').change(() => {
		i++;
		$(questions[i]).prop('hidden', false);
		if (i >= questions.length -3) {
			$("#bottom-pin").prop('hidden', true);
			$("#stopdiv").prop('hidden', false);
		}
	})

	$("#stopQuizz").click(() => {
		$("#postQuizz").submit();
	})
}