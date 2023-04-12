$(document).ready(function () {
  $('.addBenefit').click(function () {
    console.log('clicked');
    $newInput = `<div class="d-flex mt-3">
                      <input type="text" class="form-control" id="benefits" name="benefits[]" required>
                      <button type="button" class="btn btn-danger waves-effect waves-light ms-3 deleteBenefit">
                        <i class="bx bx-x font-size-16 align-middle"></i>
                      </button>
                    </div> `;
    $('.addBenefit').before($newInput);
  });

  $(document).on('click', '.deleteBenefit', function () {
    $(this).parent().remove();
  });
});
