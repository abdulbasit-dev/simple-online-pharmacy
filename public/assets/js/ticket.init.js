$(document).ready(function () {
  setExpireAt();
});

const getSeats = categoryId => {
  if (categoryId) {
    $('#seat').html('');
    $.ajax({
        // don;t append the url current url use base url
      url: '/clubadmin/seats/seat-by-category/' + categoryId,
      success: function (data) {
        //if data is not empty
        if (data != '') {
          //make seat select required
          $('#seat').attr('required', true);
          // add none option to data
          data.unshift({
            id: '',
            text: $('#noneOption').text(),
          });
          //set seat select2 options
          $('#seat').select2({
            data: data,
          });
        } else {
          // Remove required attribute
          $('#seat').attr('required', false);
          $('#seat').select2({
            data: [
              {
                id: '',
                text: $('#noSeatAvailable').text(),
              },
            ],
          });
        }
      },
    });
  } else {
    $('#seat').empty();
  }
};

const getGates = categoryId => {
  if (categoryId) {
    $('#gate').html('');
    $.ajax({
      url: '/clubadmin/gates/gate-by-category/' + categoryId,
      success: function (data) {
        //if data is not empty
        if (data != '') {
          //make seat select required
          $('#gate').attr('required', true);
          // add none option to data
          data.unshift({
            id: '',
            text: $('#noneOption').text(),
          });
          //set gate select2 options
          $('#gate').select2({
            data: data,
          });
        } else {
          // Remove required attribute
          $('#gate').attr('required', false);
          $('#gate').select2({
            data: [
              {
                id: '',
                text: 'No gate available',
              },
            ],
          });
        }
      },
    });
  } else {
    $('#gate').empty();
  }
};

getGateLimit = gateId => {
  if (gateId) {
    $.ajax({
      url: '/clubadmin/gates/gate-limit/' + gateId,
      success: function (data) {
        //if data is not empty
        if (data != '') {
          //set gate limit to quantity input
          $('#quantity').val(data);
        }
      },
    });
  }
};

const setExpireAt = () => {
  // get match expire time
  let expireAt = $('#match').find(':selected').attr('expire-at');
  // replace _ with space
  expireAt = expireAt.replace(/_/g, ' ');
  // set expire time to expire_at input
  $('#expire_at').val(expireAt);
};

$('#category').change(function () {
  let categoryId = $(this).val();

  // reset quantity input
  $('#quantity').val();
  // get attribute data-quantity from selected option
  let quantity = $(this).find(':selected').attr('data-quantity');
  // set quantity to quantity input
  $('#quantity').val(quantity);

  getSeats(categoryId);
  getGates(categoryId);
});

$('#gate').change(function () {
  let gateId = $(this).val();
  getGateLimit(gateId);
});

// when match is selected
$('#match').on('change', () => setExpireAt());
