<div class="modal fade" id="ModalRejectOrder" tabindex="-1" aria-labelledby="ModalRejectOrderLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form method="post" action="" id="rejectOrderForm">
              @csrf
              <div class="modal-header">
                  <h5 class="modal-title" id="ModalRejectOrderLabel">Reject Order</h5>
                  <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>
              <div class="modal-body">
                  <div class="mb-3">
                      <label for="refusal_reason" class="form-label">Refusal Reason</label>
                      <textarea name="refusal_reason" id="refusal_reason" class="form-control" required></textarea>
                  </div>
              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                  <button type="submit" class="btn btn-danger">Submit</button>
              </div>
          </form>
      </div>
  </div>
</div>

<script>
  // Menangani klik tombol reject untuk mengubah action form modal
  const modalRejectOrder = document.getElementById('ModalRejectOrder');
  modalRejectOrder.addEventListener('show.bs.modal', function (event) {
      var button = event.relatedTarget; // Tombol yang memicu modal
      var orderId = button.getAttribute('data-order-id'); // Ambil ID order
      var form = modalRejectOrder.querySelector('form');
      form.action = '/order/reject_order/' + orderId; // Ubah action form modal
  });
</script>
