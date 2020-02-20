

jQuery(document).ready(function(evt) {
// alert('vgfgfgf');
  $(".wordpress-ajax-form").submit(function(evt){  
  // alert('fdfdf');
  if( document.article_form.name.value == "") {
  alert( "Please provide Title! " );
  document.article_form.name.focus() ;
  return false;
  }
  if(document.article_form.description.value == "" ) {
  alert( "Please provide your Description! ");
  document.article_form.description.focus() ;
  return false;
  }   
  //return false;
  //  e.preventDefault();
  evt.preventDefault();
  var formData = new FormData($(this)[0]);
  
  $.ajax({
    url: $('#article_form').attr('action'),
    type: 'POST',
    data: formData,
    async: true,
    cache: false,
    contentType: false,
    enctype: 'multipart/form-data',
    processData: false,
    success: function (response) {
    // alert(response);
    alert('Article created Successfully!!!');
    }
  });
  return false;
  });  


  $(".wordpress-ajax-form2").submit(function(evt){  
   
  if( document.articles_catform.name.value == "") {
  alert( "Please provide Title! " );
  document.articles_catform.name.focus() ;
  return false;
  }
  if(document.articles_catform.description.value == "" ) {
  alert( "Please provide your Description! ");
  document.articles_catform.description.focus() ;
  return false;
  }   

   evt.preventDefault();
    
      var $form = $(this);
      $.post($form.attr('action'), $form.serialize(), function(data) {
        //return false;
        //alert('Category Created Successfully...');
        if(data){
         $("#artticle_category").append(data);
          alert('Category Created Successfully...');
        }
        else {
           alert('Already Exists!!!');
        }
       
        //return false;
     });

   }); 

});

