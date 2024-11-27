<!-- Modal -->
<div class="modal fade" id="OrderDetailModal" tabindex="-1" aria-labelledby="OrderDetailModalLabel" aria-hidden="true"
  style="text-transform: capitalize" data-bs-keyboard="false" data-bs-backdrop="static">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="OrderDetailModalLabel">Detail Order</h5>
        <button type="button" class="btn m-0 p-0 d-flex justify-content-center align-items-center"
          data-bs-dismiss="modal" aria-label="Close"><i class="fa fa-fw fa-solid fa-xmark"
            style="color: white;font-size:1.5rem; padding:0"></i></button>
      </div>
      <div class="modal-body detail">
        <div class="row g-0">
          <div class="col-md-12">
            <div class="status p-3">
              <table class="table table-borderless">
                <tbody>
                  <tr>
                    <td>
                      <div class="d-flex flex-column">
                        <span class="heading d-block">Order by</span>
                        <span class="subheadings" id="username_detail"></span>
                      </div>
                    </td>
                    <td>
                      <div class="d-flex flex-column">
                        <span class="heading d-block">Order Date</span>
                        <span class="subheadings" id="order_date_detail"></span>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex flex-column">
                        <span class="heading d-block">Product Name</span>
                        <span class="subheadings" id="product_name_detail"></span>
                      </div>
                    </td>
                    <td>
                      <div class="d-flex flex-column">
                        <span class="heading d-block">Quantity</span>
                        <span class="subheadings" id="quantity_detail"></span>
                      </div>
                      <tbody id="order_items_detail"></tbody>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex flex-column">
                        <span class="heading d-block">Address</span>
                        <span class="subheadings" id="address_detail"></span>
                      </div>
                    </td>
                    <td>
                      <div class="d-flex flex-column">
                        <span class="heading d-block">Payment</span>
                        <span class="subheadings" id="payment_method_detail"></span>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex flex-column">
                        <span class="heading d-block">Status</span>
                        <span class="subheadings d-flex align-items-center">
                          <div id="style_status_detail" style="margin-right: 8px;"></div>
                          <div id="status_detail"></div>
                        </span>
                      </div>
                    </td>
                    <td>
                      <div class="d-flex flex-column">
                        <span class="heading d-block">Bank</span>
                        <span class="subheadings" id="bank_detail"></span>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex flex-column">
                        <span class="heading d-block">Account Number</span>
                        <span class="subheadings" style="color:red;" id="account_number_detail"></span>
                      </div>
                    </td>
                    <td>
                      <div class="d-flex flex-column">
                        <span class="heading d-block">Payment Proof</span>
                        <span class="d-flex flex-row gallery">
                          <a id="link_transfer_proof">
                            <img id="transaction_doc_detail" src="{{ asset('storage/' .  env('IMAGE_PROOF')) }}"
                              style="cursor:pointer; width:100px;" class="rounded" alt="Proof of Transfer">
                          </a>
                        </span>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex flex-column">
                        <span class="heading d-block">Coupon</span>
                        <span class="subheadings" id="content-kuponUsed"></span>
                      </div>
                    </td>
                    <td>
                      <div class="d-flex flex-column">
                        <span class="heading d-block">Notes</span>
                        <span class="subheadings" id="notes_transaction_detail"></span>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>
                      <div class="d-flex flex-column">
                        <span class="heading d-block">Total Price</span>
                        <span class="subheadings" id="total_price_detail"></span>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <div class="col-md-12">
            <!-- Actions -->
            @if (auth()->user()->role_id == 2)
            <div class="d-flex justify-content-center align-items-center">
              <a id="link_edit_order" title="Edit Order Data" style="text-decoration: none">
                <button class="btn btn-outline-dark">Edit</button>
              </a>
              <form method="post" class="ms-2" id="form_cancel_order">
                @csrf
                <button class="btn btn-outline-danger" id="button_cancel_order">Cancel</button>
              </form>
            </div>
            <em id="message" class="link-danger"></em>
            @endif

      
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
