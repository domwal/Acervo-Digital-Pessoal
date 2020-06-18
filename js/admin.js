/**
 * quando editar um registro preenche o formulario com os dados 
 */
function populateForm(form, data) {
  $.each(data, function(key, value) {
    if(value !== null && typeof value === 'object' ) {
      this.populateForm(form, value);
    }
    else {
      var ctrl = $('[name='+key+']', form);
      switch(ctrl.prop("type")) {
        case "radio": case "checkbox":
        ctrl.each(function() {
          $(this).prop("checked",value);
        });
        break;
        default:
          ctrl.val(value);
        }
    }
  }.bind(this));
}

/**
 * quando visualizar um registro seta a div com os dados 
 * a div tem quer ter a classe: data-show-content
 * e id comecando por: #imprime-
 */
function populateDiv(classe, data) {
  $.each(data, function(key, value) {
    if(value !== null && typeof value === 'object' ) {
      this.populateDiv(value);
    }
    else {
      var ctrl = $("div.data-show-content#imprime-" + key);
      ctrl.text(value);
      ctrl.animate({ scrollTop: 0 }, "fast");
    }
  }.bind(this));
}

/**
 * retorna tipo do objeto
 */
function getType(p) {
    if (Array.isArray(p)) return 'array';
    else if (typeof p == 'string') return 'string';
    else if (p != null && typeof p == 'object') return 'object';
    else return 'other';
}

function sendFormAjax(form,url) {
  // remove the error class
  $('.form-group').removeClass('has-error');
  // remove the error text
  $('#help-block').empty();
  
  formData = form.serialize();

  // process the form
  $.ajax({
    type    : 'POST', // define the type of HTTP verb we want to use (POST for our form)
    url     : url, // the url where we want to POST
    data    : formData, // our data object
    dataType  : 'json', // what type of data do we expect back from the server
    encode    : true
  })
  // using the done promise callback
  .done(function(data) {
    // log data to the console so we can see
    // console.log(data.errors);

    // here we will handle errors and validation messages
    if ( ! data.success) {
      if (data.errors) {
        var errorMsg = "Favor verificar os erros listados abaixo:";
        $.each(data.errors, function(k, v) {
          // console.log( k + ' : ' + v);
          // add the error class to show red input
          $('#' + k + '-group').addClass('has-error');
          errorMsg += "<li>" + v + "</li>";
        });
        // add the actual error message under our input
        $('#help-block').append('<div class="col-lg-12"><div class="alert alert-dismissable alert-danger">' +
            '<button type="button" class="close" data-dismiss="alert">&times;</button>' + errorMsg + '</div></div>');
      }
    } else {
      // ALL GOOD! just show the success message!
      $('#help-block').append('<div class="col-lg-12"><div class="alert alert-dismissable alert-success">' +
          '<button type="button" class="close" data-dismiss="alert">&times;</button>' + data.message + '</div></div>');

      if (data.message == 'Cadastrado com sucesso') {
        // reseta o form
        form.trigger("reset");


        // remove as barras de progresso
        if ($('#progress-upload-group').length) {
          $('#progress-upload-group').empty();
        }
        // usually after form submission, you'll want to redirect
        // window.location = '/thank-you'; // redirect a user to another page
      }
    }
  })

  // using the fail promise callback
  .fail(function(data) {
    $('#help-block').append('<div class="col-lg-12"><div class="alert alert-dismissable alert-danger">' +
        '<button type="button" class="close" data-dismiss="alert">&times;</button>Erro! Tente novamente mais tarde.</div></div>');
    // show any errors
    // best to remove for production
    // console.log(data);
  });

  // stop the form from submitting the normal way and refreshing the page
  event.preventDefault();
}

function updateFormFromDataAjax(form, codigo, url, a) {
  // remove the error class
  $('.form-group').removeClass('has-error');
  // remove the error text
  $('#help-block').empty();
  

  var formData = {
    'codigo'    : codigo,
    'a'         : a,
  };

  // process the form
  $.ajax({
    type    : 'POST', // define the type of HTTP verb we want to use (POST for our form)
    url     : url, // the url where we want to POST
    data    : formData, // our data object
    dataType  : 'json', // what type of data do we expect back from the server
    encode    : true
  })
  // using the done promise callback
  .done(function(data) {

    // here we will handle errors and validation messages
    if ( ! data.success) {
        $('#help-block').append('<div class="col-lg-12"><div class="alert alert-dismissable alert-danger">' +
            '<button type="button" class="close" data-dismiss="alert">&times;</button>Erro! Tente Novamente Mais Tarde.</div></div>');
    } else {
      // ALL GOOD! just show the success message!
      if (data.ok) {
        populateForm(form, data.ok);
      }
    }
  })

  // using the fail promise callback
  .fail(function(data) {
    $('#help-block').append('<div class="col-lg-12"><div class="alert alert-dismissable alert-danger">' +
        '<button type="button" class="close" data-dismiss="alert">&times;</button>Erro! Tente novamente mais tarde.</div></div>');
  });

  // stop the form from submitting the normal way and refreshing the page
  event.preventDefault();
}

function getContentAjax(codigo, url, a) {
  var formData = {
    'codigo'    : codigo,
    'a'         : a,
  };

  $.ajax({
    type    : 'POST', // define the type of HTTP verb we want to use (POST for our form)
    url     : url, // the url where we want to POST
    data    : formData, // our data object
    dataType  : 'json', // what type of data do we expect back from the server
    encode    : true
  })
  // using the done promise callback
  .done(function(data) {

    // here we will handle errors and validation messages
    if ( ! data.success) {
      alert("Erro! Tente Novamente Mais Tarde.");
    } else {
      // ALL GOOD! just show the success message!
      populateDiv('data-show-content', data.ok);
    }
  })

  // using the fail promise callback
  .fail(function(data) {
    alert("Erro! Tente Novamente Mais Tarde.");
  });

  // stop the form from submitting the normal way and refreshing the page
  event.preventDefault();
}

function deleteDataAjax(codigo, url, a) {
  
  var formData = {
    'codigo'    : codigo,
    'a'         : a,
  };

  // process the form
  $.ajax({
    type    : 'POST', // define the type of HTTP verb we want to use (POST for our form)
    url     : url, // the url where we want to POST
    data    : formData, // our data object
    dataType  : 'json', // what type of data do we expect back from the server
    encode    : true
  })
  // using the done promise callback
  .done(function(data) {

    // here we will handle errors and validation messages
    if ( ! data.success) {
      alert("Erro! Tente Novamente Mais Tarde.");
    } else {
      // ALL GOOD! just show the success message!
      alert(data.message);
      location.reload();
    }
  })

  // using the fail promise callback
  .fail(function(data) {
    alert("Erro! Tente Novamente Mais Tarde.");
  });

  // stop the form from submitting the normal way and refreshing the page
  event.preventDefault();
}





$(document).ready(function(){

  // ---------------------- Disciplinas ------------------------
  $('#formDisciplina').on('submit', function(e){
    e.preventDefault();
    sendFormAjax($(this), 'process.php');
  });

  $('#btnSaveDisciplina').click(function() {
    $('#formDisciplina').submit();
  });

  // ---------------------- Conteudo ------------------------

  $('#formConteudo').on('submit', function(e){
    e.preventDefault();
    sendFormAjax($(this), 'process.php');
  });

  $('#btnSaveConteudo').click(function() {
    $('#formConteudo').submit();
  });

  $("#fileArquivo").on("change", function (e, data) {
    $('#help-block').empty();

    var files = $(this)[0].files;
    var totalFiles = files.length;
    var maxUploadSize = $('#serverUploadMaxSize').attr('value');
    var hash = $('#inputHash').attr('value');

    var listInvalidFiles = "";

    for (i=0; i<totalFiles; i++) {
      file = $(this)[0].files[i];
      upload = new Upload(file, "process.php", hash, 'upload-conteudo');
      divIdentify = $.md5(upload.getName() +  upload.getSize());

      if ("application/pdf" == upload.getType() && maxUploadSize > upload.getSize() && !$('#progress-wrp-' + divIdentify).length) {
        $('#progress-upload-group').append(
            '<input type="hidden" name="inputListFiles[]" value="' + upload.getName() + '">' +
            '<div class="resultado-' + divIdentify + '"> ' + upload.getName() + ' </div>' +
            '<div id="progress-wrp-' + divIdentify + '" class="progress-wrp">' +
            '<div class="progress-bar"></div>'+
            '<div class="status">0%</div>' +
            '</div>');

        upload.doUpload();
      }
      else {
        listInvalidFiles += "<li>" + upload.getName() + "</li>\n";
      }

    }

    if (listInvalidFiles) {
      $('#help-block').append('<div class="col-lg-12"><div class="alert alert-dismissable alert-danger">' +
          '<button type="button" class="close" data-dismiss="alert">&times;</button>Alguns arquivos não foram anexados:<br>' + listInvalidFiles + '</div></div>');
    }
  });




  // ---------------------- Global ------------------------

  // ao clicar no botao [Adicionar Novo], zera o form
  $('#btnAdicionarNovo').click(function() {
    $('#help-block').empty();
    $('.form-group').removeClass('has-error');
    var totalForm = $('form').length;
    if (totalForm > 0) {
      form = $('form').eq(totalForm-1);
      form.trigger("reset");
      // $(this).closest('form').trigger("reset");
      // $(this).closest('form').find("input[type=text], textarea").val("");

      // remove as barras de progresso
      if ($('#progress-upload-group').length) {
        $('#progress-upload-group').empty();
      }

      // define um novo valor
      if ($('#inputHash').length) {
        var time = Math.round((new Date()).getTime() / 1000);
        $('#inputHash').attr('value', $.md5(time))
      }
    }
  });
  
  $('.btnEditar').click(function() {
    var totalForm = $('form').length;
    if (totalForm > 0) {
      form = $('form').eq(totalForm-1);
      form.trigger("reset");
      var codigo = $(this).data('codigo');
      var tipo = $(this).data('tipo');
      updateFormFromDataAjax(form, codigo, 'process.php', tipo);
    }
  });

  $('.btnVisualizar').click(function() {
    var codigo = $(this).data('codigo');
    var tipo = $(this).data('tipo');
    getContentAjax(codigo, 'process.php', tipo);
  });

  $('.btnExcluir').click(function() {
    var codigo = $(this).data('codigo');
    $('#btnConfirmarExcluir').data('codigo', codigo);
  });

  $('#btnConfirmarExcluir').click(function() {
    var codigo = $(this).data('codigo');
    var tipo = $(this).data('tipo');
    deleteDataAjax(codigo, 'process.php', tipo);
    $('#confirm').modal('toggle');
  });

}); // end document.ready