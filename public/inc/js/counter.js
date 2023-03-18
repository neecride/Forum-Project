$(document).ready(function(e) {


  $('#zone').keyup(function() {

    var nombreCaractere = $(this).val().length;
    var nombreCaractere = $(this).data('limit') - nombreCaractere;

    var nombreMots = jQuery.trim($(this).val()).split(' ').length;
    if($(this).val() === '') {
     	nombreMots = 0;
    }

    var msg = '( ' + nombreCaractere + ' Caractere(s) restant )';
    $('#legende').text(msg);
    if (nombreCaractere < 1) { $('#legende').addClass("insuffisant"); } else { $('#legende').removeClass("insuffisant"); }

  })

  $('#comment').keyup(function() {

    var nombreCaractere2 = $(this).val().length;
    var nombreCaractere2 = 500 - nombreCaractere2;

    var nombreMots2 = jQuery.trim($(this).val()).split(' ').length;
    if($(this).val() === '') {
     	nombreMots2 = 0;
    }

    var msg2 = '( ' + nombreCaractere2 + ' Caractere(s) restant )';
    $('#comms').text(msg2);
    if (nombreCaractere2 < 1) { $('#comms').addClass("insuffisant"); } else { $('#comms').removeClass("insuffisant"); }

  })

  $('#username').keyup(function() {

    var nombreCaractere2 = $(this).val().length;
    var nombreCaractere2 = 30 - nombreCaractere2;

    var nombreMots2 = jQuery.trim($(this).val()).split(' ').length;
    if($(this).val() === '') {
     	nombreMots2 = 0;
    }

    var msg2 = '( Entre 3 et ' + nombreCaractere2 + ' caractere(s) requis )';
    $('#pseudo').text(msg2);
    if (nombreCaractere2 < 1) { $('#pseudo').addClass("insuffisant"); } else { $('#pseudo').removeClass("insuffisant"); }

  })

  $('#password').keyup(function() {

    var nombreCaractere2 = $(this).val().length;
    var nombreCaractere2 = 15 - nombreCaractere2;

    var nombreMots2 = jQuery.trim($(this).val()).split(' ').length;
    if($(this).val() === '') {
     	nombreMots2 = 0;
    }

    var msg2 = '( Entre 6 et ' + nombreCaractere2 + ' caractere(s) requis )';
    $('#passwd').text(msg2);
    if (nombreCaractere2 < 1) { $('#passwd').addClass("insuffisant"); } else { $('#passwd').removeClass("insuffisant"); }

  })

  $('#password_confirm').keyup(function() {

    var nombreCaractere2 = $(this).val().length;
    var nombreCaractere2 = 15 - nombreCaractere2;

    var nombreMots2 = jQuery.trim($(this).val()).split(' ').length;
    if($(this).val() === '') {
     	nombreMots2 = 0;
    }

    var msg2 = '( Entre 6 et ' + nombreCaractere2 + ' caractere(s) requis )';
    $('#passwdc').text(msg2);
    if (nombreCaractere2 < 1) { $('#passwdc').addClass("insuffisant"); } else { $('#passwdc').removeClass("insuffisant"); }

  })

});
