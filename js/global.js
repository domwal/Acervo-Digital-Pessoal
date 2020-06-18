
// **************************************** VARIAVEIS **************************************** //
var cookie_search_history = "search_history";
var list;
var search;


// **************************************** ADICIONAR HISTORICO DE PESQUISA NO COOKIE **************************************** //
$(document).ready(function(){
  // When someone clicks on a search added to the cookie list:
  $('#form_busca').on('submit', function(e){
      search = $("#search").val();

      // se fez uma pesquisa com o campo vazio
      if (search == "") {
        e.preventDefault();
        return false;
      }

      // carrega para a variavel
      list = new cookieList(cookie_search_history);
      // conta o total de itens que tem
      contaInicio = list.items().length;

      // se nao encontrou a palavra pesquisada na lista
      if(!list.contain(search)) {
        // adiciona no cookie
        list.add(search);
      
        // vamos ler novamente o cookie
        list = new cookieList(cookie_search_history);
        // vamos contar
        contaFim = list.items().length;

        // se a contagem inicial for igual, entao provavelmente nao adicionou
        if (contaInicio == contaFim) {
          // ja que nao adicionou, vamos percorrer o primeiro item e remover
          jQuery.each(list.items(), function(index, value){
            list.remove(value);
            return false;
          });

          // vamos agora adicionar no cookie
          list.add(search);
        }
      }


      // debug only
      // jQuery.each(list.items(), function(index, value){
      //   console.log(index + " - " + value);
      // });
      // e.preventDefault();
      // return false;
  });
});


// **************************************** AUTOCOMPLETE **************************************** //
var historico_busca = [];
/*initiate the autocomplete function on the "myInput" element, and pass along the historico_busca array as possible autocomplete values:*/
$( document ).ready(function() {

  // vamos pegar os valores salvos no cookie
  list = new cookieList(cookie_search_history);
  jQuery.each(list.items(), function(index, value){
    historico_busca.push(value);
  });

  // iniciar o autocomplete
  autocomplete(document.getElementById("search"), historico_busca);
});


// **************************************** HIGHLIGHT TEXT SEARCH TERMS **************************************** //
$( document ).ready(function() {
  $('li.termos-busca').each(function(){
    termo = $(this).text();
    $('.resultado-busca').highlight(termo);
  });
});