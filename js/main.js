
function toHHMMSS(sec_num) {
	var hours = Math.floor(sec_num / 3600);
	var minutes = Math.floor((sec_num - (hours * 3600)) / 60);
	var seconds = sec_num - (hours * 3600) - (minutes * 60);
	var time = '';
	// var time = hours + 'h ' + minutes + 'm ' + seconds + 's';
	if(hours) time+= hours + 'h ';
	if(minutes) time+= minutes + 'm ';
	time+= seconds + 's';

	return time;
}

(function($){
	$(document).ready(function(){
		var editorSelect = $('#editor ul li a');
		console.log(editorSelect);

		$('#editor ul li a').click(function(){
			var selectedEditorText = $(this).text();
			var selectedEditor = $(this).data('editor');

			$.post('templates/sandmancontrol/ajax-models/quickeditor.php', { editor: selectedEditor },function(data){
				// alert(data);
				if (data === 'success') {
					$('#editor .select-active').text(selectedEditorText);
				}
			});
		});		

		// Session Bar

		$.post('templates/sandmancontrol/ajax-models/sessionbar.php', function(data){
			// alert(data);
			var sessionExpire = parseInt(data);
			var sessionTimeRemaining = sessionExpire;

			var sessionProgress = $('.session_progress');
			var sessionTip = $('.session_tip');
			var sessionPercentage = 100;

			
			var sessionInterval = setInterval(function(){
				
				sessionTimeRemaining--;

				sessionTip.text(toHHMMSS(sessionTimeRemaining));
				var sessionWidth = sessionTip.width() + 15;

				sessionPercentage = (sessionTimeRemaining / sessionExpire) * 100;
				sessionProgress.css('width', sessionPercentage + '%');
				sessionTip.show();
				
				if (sessionTimeRemaining <= 10) {
					sessionTip.removeClass('warning-blink').addClass('danger-blink');
				} else if (sessionTimeRemaining <= 30) {
					sessionTip.addClass('warning-blink');
				} 

				if (sessionTimeRemaining <= 0) {
					window.clearInterval(sessionInterval);
					sessionTip.text('Session Expired').addClass('expired').removeClass('danger-blink').removeClass('warning-blink');
					sessionWidth = sessionTip.width() + 15;
				}

				sessionTip.css('left', '-' + sessionWidth + 'px');

			}, 1000);

		});



		// var sessionProgress = $('.session_progress');
		// var sessionPercentage = 100;

		// var sessionInterval = setInterval(function(){
		// 	if(sessionPercentage <= 0) {
		// 		window.clearInterval(sessionInterval);
		// 		return;
		// 	}
		// 	sessionProgress.css('width', sessionPercentage + '%');
		// 	sessionPercentage-=10;
		// }, 1000);
	});
}(jQuery));