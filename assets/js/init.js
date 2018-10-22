function openDialog(html, button, email = false, link = false)  {
    var close_button_text = (button) ? button : 'Aceptar';
    var html_content      = (html) ? html.trim() : null;
    if(html_content) {
        $('#modal-container').html(html_content);
        $('.modal .content > .close').text(close_button_text);
        if(link){
        $(".modal .content > .close").attr("href", window.location.protocol + "//" + window.location.host + "/core/bags");
        }else{
            $('.modal .content > .close').on('click', function(e) {
                e.preventDefault();
                $('.modal').fadeOut(300);
                return false;
             });
        }
		if(email) {
			// Will request email in order to register it
			$("#request-email").show();
		}
		
        $('.modal').fadeIn(300).css('display','table');
    }
}

