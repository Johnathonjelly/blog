//tinymce setup credit to https://codepen.io/nirajmchauhan/pen/EjQLpV?editors=1010
$(document).ready(function() {
  tinymce.init({
    selector: "textarea",
    theme: "modern",
    paste_data_images: true,
    plugins: [
      "advlist autolink lists link image charmap print preview hr anchor pagebreak",
      "searchreplace wordcount visualblocks visualchars code fullscreen",
      "insertdatetime media nonbreaking save table contextmenu directionality",
      "emoticons template paste textcolor colorpicker textpattern"
    ],
    toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
    toolbar2: "print preview media | forecolor backcolor emoticons",
    image_advtab: true,
    file_picker_callback: function(callback, value, meta) {
      if (meta.filetype == 'image') {
        $('#upload').trigger('click');
        $('#upload').on('change', function() {
          var file = this.files[0];
          var reader = new FileReader();
          reader.onload = function(e) {
            callback(e.target.result, {
              alt: ''
            });
          };
          reader.readAsDataURL(file);
        });
      }
    }
  });
});

(function() {
//class of elements for any elements of the dom I may need reference to
  class Elements {
    constructor() {
      this.showAllPosts = document.querySelector('.showButton');
    }
  }
  
const element = new Elements();
    function unhide() {
        document.getElementById("eventInfoTable").classList.remove('hidden');
        console.log('unhide');
    }
    element.showAllPosts.addEventListener('click', unhide);
})();