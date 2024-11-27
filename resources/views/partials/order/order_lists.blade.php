@foreach ($orders as $order)
<div class="card mb-3">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="order-header mb-0">
                Order #{{ $order->id }} - {{ $order->created_at->format('d M Y') }}
                <span class="badge bg-{{ $order->status->style }}">{{ $order->status->order_status }}</span>
            </h5>
            @if (auth()->user()->role_id == 1) <!-- Admin Actions -->
            <div class="d-flex">
                <button type="button" class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#ModalRejectOrder" data-order-id="{{ $order->id }}">
                    Reject
                </button>
                <form method="post" action="{{ url('/order/approve_order/' . $order->id) }}" class="me-2">
                    @csrf
                    <button class="btn btn-outline-success btn-sm">Approve</button>
                </form>
                <form method="post" action="{{ url('/order/end_order/' . $order->id) }}">
                    @csrf
                    <button class="btn btn-outline-info btn-sm">Done</button>
                </form>
            </div>
            @endif
        </div>
        <span class="badge bg-primary">{{ $order->payment->payment_method }}</span>
        <span class="order-detail-link" title="order detail" style="cursor: pointer;" 
              data-id="{{ $order->id }}" data-dipesan="{{ $order->created_at->format('d M Y') }}">
            Detail
        </span>
    </div>
    <div class="card-body">
        <!-- Produk-produk dalam Order -->
        <div class="order-products">
            @foreach ($order->orderDetails as $detail)
            <div class="row mb-2">
                <div class="col-md-1">
                    <img src="{{ asset('storage/' . $detail->product->image) }}" 
                         class="media-object img-thumbnail" />
                </div>
                <div class="col-md-11">
                    <strong>{{ $detail->product->product_name }}</strong> <br />
                    Quantity: {{ $detail->quantity }} <br />
                    Total price: Rp. {{ $detail->price * $detail->quantity }}
                </div>
            </div>
            @endforeach
        </div>

        <!-- Informasi Tambahan -->
        <small>
            Notes: {{ isset($order->refusal_reason) ? $order->refusal_reason : $order->note->order_notes }}
        </small><br />

        <!-- Action upload transfer proof if payment method is bank transfer and role is customer -->
        @if ($order->payment->payment_method == "Transfer Bank" && auth()->user()->role_id == 2)
        <div class="mt-3">
            <small>Action</small>
            <a data-bs-placement="top" class="uploadProof" title="Upload Transfer Proof" data-id="{{ $order->id }}">
              <div class="btn btn-danger btn-xs fa fa-fw fa-camera label-bukti"
                   style="font-size: 0.75rem; padding: 0.3rem; color: white;">
              </div>
            </a>
        </div>
        @endif

        @if ($order->is_done == 1)
        <div style="margin-top: 0.5rem;">
            <button class="btn btn-primary btn-sm download-invoice" data-id="{{ $order->id }}" 
                    style="font-size: 0.8rem; padding: 0.2rem 0.5rem;">
                Download Invoice
            </button>
        </div>
        @endif
    </div>
    <div class="card-footer text-muted">
        Order {{ $order->is_done ? 'ends' : 'created' }} at {{ $order->updated_at->format('d M Y') }} by 
        @if ($order->is_done)
        <span class="link-danger" style="cursor: pointer;">@admin</span>
        @else
        <a href="{{ auth()->user()->role_id == 1 ? "/home/customers?username=" . $order->user->username : "/profile/my_profile" }}" 
            style="text-decoration: none;">
            {{ "@" . $order->user->username }}
        </a>
        @endif
    </div>
</div>
@endforeach

<script>
    document.querySelectorAll('.download-invoice').forEach(button => {
      button.addEventListener('click', function() {
          let orderId = this.getAttribute('data-id');
          
          fetch(`/order/invoice/${orderId}`, {
              method: 'GET',
              headers: {
                  'Content-Type': 'application/pdf'
              }
          })
          .then(response => {
              if (response.ok) {
                  return response.blob();
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
