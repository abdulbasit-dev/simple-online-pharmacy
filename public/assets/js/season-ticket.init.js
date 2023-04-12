$(document).ready(function () {
  $('#expire_at').val('2024-01-01 00:00:00');
  $('#name').val(`Season Ticket ${new Date().getFullYear()}`);
});

$('#sections').on('change', function () {
  var total = 0;
  $('#sections option:selected').each(function () {
    total += parseInt($(this).data('quantity'));
  });
  $('#quantity').val(total);
});
