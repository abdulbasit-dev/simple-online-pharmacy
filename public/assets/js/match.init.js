// warning counter to show warning message
let counter = 0;

// when home team is selected
$('#home').on('select2:select', function (e) {
  console.log('as');
  // get image from option img attribute
  let img = $(e.params.data.element).attr('img');
  let data = e.params.data;
  //remove d-none class from homeDiv
  $('#homeDiv').removeClass('d-none');
  $('#homeDiv img').attr('src', img);
  $('#homeDiv span').text(data.text);
  $('#vs').removeClass('d-none');
});

// when away team is selected
$('#away').on('select2:select', function (e) {
  // get image from option img attribute
  let img = $(e.params.data.element).attr('img');
  let data = e.params.data;
  //remove d-none class from awayDiv
  $('#awayDiv').removeClass('d-none');
  $('#awayDiv img').attr('src', img);
  $('#awayDiv span').text(data.text);
});

// set today as default value for match_time
let today = new Date().toISOString().slice(0, 16);
$('#match_time').val(today);

// init select2
$('.select2').select2({
  templateResult: function templateResult(e) {
    // only for option with img attribute
    if (e.element && e.element.attributes.img) {
      let img = $(e.element).attr('img');
      return e.id ? $('<span><img src="' + img + '" class="avatar-sm me-3" /> ' + e.text + '</span>') : e.text;
    } else {
      return e.text;
    }
  },
});

// home and away team should not be same
$('#away').on('change', function () {
  let away = $(this).val();
  let home = $('#home').val();

  if (home == away) {
    counter++;
    if (counter >= 2) {
      swal
        .fire({
          text: $('#duplicateSelectedTeam').text(),
          icon: 'warning',
          timer: 2000,
        })
        .then(result => {
          if (result.isDismissed) {
            $('#away').val('').trigger('change');
          }
        });
      return;
    }
    $('#away').val('').trigger('change');
  }
});
