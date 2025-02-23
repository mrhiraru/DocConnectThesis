$(document).ready(function() {
  $('#editor').summernote({
    height: 'calc(100vh - 350px)',
    toolbar: [
      ['style', ['bold', 'italic', 'underline', ]],
      // ['font', ['strikethrough', 'superscript', 'subscript']],
      ['fontsize', ['fontsize']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['insert', ['link']], // ['picture' and 'video']
      ['view', ['fullscreen']] // ['codeview', 'help']
    ],
    callbacks: {
      onInit: function() {
        $('.note-editor').css('background-color', 'white');
      },
      onFullscreen: function() {
        $('.note-editable').css('background-color', 'white');
      }
    }
  });
  
  $('#about_content, #vision, #mission, #tech_innovation, #discover_more').summernote({
    height: 'calc(100vh - 350px)',
    toolbar: [
      ['style', ['bold', 'italic', 'underline', ]],
      // ['font', ['strikethrough', 'superscript', 'subscript']],
      ['fontsize', ['fontsize']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      // ['insert', ['link']], // ['picture' and 'video']
      ['view', ['fullscreen']] // ['codeview', 'help']
    ],
    callbacks: {
      onInit: function() {
        $('.note-editor').css('background-color', 'white');
      },
      onFullscreen: function() {
        $('.note-editable').css('background-color', 'white');
      }
    }
  });
});