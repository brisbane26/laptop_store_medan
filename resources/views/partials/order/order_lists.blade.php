<!-- Order Data -->
@foreach ($orders as $row)
<div class="row">
  <div class="col-md-1">
    <img src="{{ asset('storage/'. $row->product->image) }}" class="media-object img-thumbnail" />
    <div class="order-detail">
      <span class="order-detail-link" title="order detail" style="cursor: pointer;"
        data-id="{{ $row->id }}" data-dipesan="{{ $row->created_at->format('d M Y') }}">
        detail
      </span>
    </div>
  </div>
  <div class="col-md-11">
    <div class="row">
      <div class="col-md-12">
        <div class="float-end">
          <label class="badge bg-{{ $row->status->style }}">
            {{ $row->status->order_status }}
          </label>
        </div>
        <span>
          <strong>{{ $row->product->product_name }}</strong>
        </span>
        <span class="badge bg-primary">{{ $row->payment->payment_method }}</span> <br />
        Quantity: {{ $row->quantity }}, Total price: Rp. {{ $row->total_price }} <br />
        <small>
          Notes: {{ isset($row->refusal_reason) ? $row->refusal_reason : $row->note->order_notes }}
        </small><br />

        @if ($row->payment->payment_method == "Transfer Bank" && auth()->user()->role_id == 2)
        <small>Action</small>
        <a data-bs-placement="top" class="uploadProof" title="Upload Transfer Proof" data-id="{{ $row->id }}">
          <div class="btn btn-danger btn-xs fa fa-fw fa-camera label-bukti"
            style="font-size: 0.75rem; padding: 0.3rem; color: white;">
          </div>
        </a>
        @endif

        @if (isset($row->product_id) && auth()->user()->role_id == 2 && $row->is_done == 1)
        <div>
          <a href="/review/product/{{ $row->product_id }}" class="link-info"
            style="text-decoration: none; font-size: 0.9rem;">
            Review Now!
          </a>
        </div>
        @endif

        @if ($row->is_done == 1)
        <!-- Tombol Download Invoice -->
        <div style="margin-top: 0.5rem;">
          <button class="btn btn-primary btn-sm download-invoice" data-id="{{ $row->id }}" 
                  style="font-size: 0.8rem; padding: 0.2rem 0.5rem;">
              Download Invoice
          </button>
      </div>
        @endif
      </div>
      @php
      if (auth()->user()->role_id == 1) {
      $dest = "/home/customers?username=" . $row->user->username;
      }
      else {
      $dest = "/profile/my_profile";
      }
      @endphp

      @if ($row->is_done == '1')
      <div class="col-md-12">
        Order ends at {{ $row->updated_at->format('d M Y') }} by
        <span class="link-danger" style="cursor: pointer;">@admin</span>
      </div>
      @else
      <div class="col-md-12">
        Order created at {{ $row->created_at->format('d M Y') }} by
        <a href="{{ $dest }}" style="text-decoration: none;">
          {{ "@" . $row->user->username }}
        </a>
      </div>
      @endif
    </div>
  </div>
</div>
@endforeach

<script>
  document.querySelectorAll('.download-invoice').forEach(button => {
    button.addEventListener('click', function() {
        let orderId = this.getAttribute('data-id');
        
        // Menggunakan fetch untuk melakukan request ke route untuk unduh invoice
        fetch(`/order/invoice/${orderId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/pdf'
            }
        })
        .then(response => {
            if (response.ok) {
                return response.blob();  // Create a blob from the PDF response
            } else {
                throw new Error('Failed to generate PDF');
            }
        })
        .then(blob => {
            const link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = `invoice-${orderId}.pdf`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        })
        .catch(error => {
            console.error(error);
        });
    });
});

</script>
