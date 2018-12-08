$(document).ready(function () {
   
$('.datepicker').pickadate({
    selectMonths: true, // Creates a dropdown to control month
    selectYears: 100,// Creates a dropdown of 15 years to control year
     format: 'yyyy-mm-dd'
  });

    // $('#register').submit(function (event) {
    //     var psword;
    //     psword = $('#password').val();
    //     if (psword.trim().length === 0) {
    //         $('#password_error').removeClass('hide');
    //         has_errors = true;
    //     } else
    //         $('#password_error').addClass('hide');
    //     var pswordconf;
    //     pswordconf = $('#password_confirmation').val();
    //     if (psword.trim() !== pswordconf.trim()) {
    //         $('#conf_password_error').removeClass('hide');
    //         has_errors = true;
    //         event.preventDefault();
            
    //     } else {
    //         $('#password_error').addClass('hide');
    //     }
    // });
    
     //$(".button-collapse").sideNav();
     
     
    //  $(".loading-overlay .cssload-thecube").fadeOut(5000, function () {
    //     // Show The Scroll
    //     $("body").css("overflow", "auto");

    //     $(this).parent().fadeOut(5000, function () {// what is parent??????????

    //         $(this).remove();
    //     });
    // });
    
    
 
 
});