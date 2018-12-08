function readURL(input) {
  if (input.value != '') {
    var file = input.files[0];

    if (input.files && file) {
      var reader = new FileReader();

      reader.onload = function (e) {
        if ($('#parent').data('croppie') != undefined) {
          $('#parent').croppie('destroy');
        }



        if ($('#myModal') != undefined && $('#profPicture').data('croppie') != undefined) {
          $('#x').css('display', 'none');
          $('#y').css('display', 'block');
        }

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

          //console.log($('input[name=h]').val());

          // cropped area data
          $('#parent').croppie('result', {}).then(function(x) {
            //console.log($('#x').attr('src'));
            //console.log($('#y').attr('src'));
            //console.log(e.currentTarget.result);
            //$scope.imageUrl = e.currentTarget.result;
            $('#inner').attr('src', x);
            $('#myimg').attr('src', e.target.result);
            //console.log($('#myimg').attr('src'));

          })


        });
      };

      reader.readAsDataURL(file);
    }
  }
}

$('#myModal').on('hidden.bs.modal', function() {
  if ($('#profPicture').data('croppie') != undefined) {
    $('#profPicture').croppie('destroy');
  }

  if ($('#parent').data('croppie') != undefined) {
    $('#parent').croppie('destroy');
  }
  
  $('#file').val('');
  $('#y').css('display', 'none');
});

$("#myModal").on('shown.bs.modal', function() {
  $('#y').css('display', 'none');
  $('#x').css('display', 'block');

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
    })
  });
})

$(document).keydown(function(event) { 
  if (event.keyCode == 27) { 
    $('#myModal').hide();
  }
});