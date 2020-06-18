
var Upload = function (file, url, hash, a) {
  this.file = file;
  this.url  = url;
  this.hash = hash;
  this.a    = a;
};

Upload.prototype.getType = function() {
  return this.file.type;
};
Upload.prototype.getSize = function() {
  return this.file.size;
};
Upload.prototype.getName = function() {
  return this.file.name;
};
Upload.prototype.doUpload = function () {
  var that = this;
  var formData = new FormData();

  var divIdentify = $.md5(that.getName() +  that.getSize());
  var progress_bar_id = "#progress-wrp-" + divIdentify;

  // add assoc key values, this will be posts values
  formData.append("file", this.file, this.getName());
  formData.append("a", this.a);
  formData.append("inputHash", this.hash);

  $.ajax({
      type: "POST",
      url: this.url,

      xhr: function(){
          //Get XmlHttpRequest object
           var xhr = $.ajaxSettings.xhr() ;
          //Set onprogress event handler
           xhr.upload.onprogress = function(data){
              var percent = Math.round((data.loaded / data.total) * 100);

              // update progressbars classes so it fits your code
              $(progress_bar_id + " .progress-bar").css("width", +percent + "%");
              $(progress_bar_id + " .status").text(percent + "%");

           };
           return xhr ;
      },
      success: function (data) {
        // your callback here
        try {
          data = jQuery.parseJSON(data);
        }
        catch (err) {
          // maybe not loged in
          location.reload();
          return false;
        }

        if (data.success == 1) {
            $(".resultado-" + divIdentify).append('<span class="label label-success">Success</span>');
        } else {
            $(".resultado-" + divIdentify).append('<span class="label label-danger">Error</span>');
        }
          
      },
      error: function (error) {
          // handle error
          $(".resultado-" + divIdentify).append('<span class="label label-danger">Error</span>');
      },
      async: true,
      data: formData,
      cache: false,
      contentType: false,
      processData: false,
      timeout: 60000
  });
};
