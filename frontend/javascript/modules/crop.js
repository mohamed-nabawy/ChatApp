function readURL(input) {
  if (input.value != '') {
    console.log(input.value);
    //input.value = '';
    //console.log(input.value);
    var file = input.files[0];

    if (input.files && file) {
      var reader = new FileReader();

      reader.onload = function (e) {
        if ($('#parent').data('croppie') != undefined) {
          $('#parent').croppie('destroy');
          console.log($('#parent').data('croppie'));
        }

        console.log($('#profPicture').data('croppie'));

        if ($('#myModal') != undefined && $('#profPicture').data('croppie') != undefined) {
          //$('#profPicture').data('croppie').destroy();
          //console.log($('#profPicture').data('croppie'));
          $('#x').css('display', 'none');
          $('#y').css('display', 'block');

        }

        console.log($('#profPicture').data('croppie'));

        var x = $('#parent').croppie({
          viewport: {
            // width: 150,
            // height: 150,
            // type: 'circle'
          },
          boundary: {
            width: 300,
            height: 300
          },
          //enableResize: true,
          // points: [77, 469, 280, 739],
          url: e.target.result
        });

        // 
        $('#parent').on('update.croppie', function(ev, cropData) {
          var x1 = cropData.points[0];
          var y1 = cropData.points[1];
          var x2 = cropData.points[2];
          var y2 = cropData.points[3];
          var h = y2 - y1;
          var w = x2 - x1;

          // points that will be sent to server to crop the image
          // try send only the cropped area directly and no crop will occur in server which is better
          $('input[name="x1"]').val(x1);
          $('input[name="y1"]').val(y1);
          $('input[name=w]').val(w);
          $('input[name=h]').val(h);

          // cropped area data
          $('#parent').croppie('result', {}).then(function(x) {
            $('#inner').attr('src', x);
          })
        });
      };

      reader.readAsDataURL(file);
    }
  }
}

$('#myModal').on('hidden.bs.modal', function() {
  // if ($('#profPicture').data('croppie') != undefined) {
  //   $('#profPicture').data('croppie').destroy();
  // }

  if ($('#parent').data('croppie') != undefined) {
    $('#parent').croppie('destroy');
  }
  
  
  $('#file').val('');
  $('#y').css('display', 'none');
});

$("#myModal").on('shown.bs.modal', function() {
  //if ($('#file').val() == '') {
    $('#y').css('display', 'none');
  //}

  $('#x').css('display', 'block');

  console.log(23);
  var x = $('#profPicture').croppie({
    viewport: {
      // width: 150,
      // height: 150,
      // type: 'circle'
    },
    boundary: {
      width: 300,
      height: 300
    },
    //enableResize: true,
    // points: [77, 469, 280, 739],
    //url: e.target.result
    url: $('#myPic').attr('src')
  });

  // 
  $('#profPicture').on('update.croppie', function(ev, cropData) {
    var x1 = cropData.points[0];
    var y1 = cropData.points[1];
    var x2 = cropData.points[2];
    var y2 = cropData.points[3];
    var h = y2 - y1;
    var w = x2 - x1;

    // points that will be sent to server to crop the image
    // try send only the cropped area directly and no crop will occur in server which is better
    $('input[name="x1"]').val(x1);
    $('input[name="y1"]').val(y1);
    $('input[name=w]').val(w);
    $('input[name=h]').val(h);

    console.log(cropData);

    // cropped area data
    $('#profPicture').croppie('result', {}).then(function(x) {
      $('#picInner').attr('src', x);
      //console.log($('#picInner').attr('src'));
    })
  });
  //alert('The modal is about to be shown.');
})

$(document).keydown(function(event) { 
  if (event.keyCode == 27) { 
    $('#myModal').hide();
  }
});