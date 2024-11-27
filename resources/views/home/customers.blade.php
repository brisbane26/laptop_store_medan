@extends('/layouts/main')

@push('css-dependencies')
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" />
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.5/dist/sweetalert2.min.css" rel="stylesheet">
@endpush

@push('scripts-dependencies')
    <script src="/js/customers_table.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.5/dist/sweetalert2.min.js"></script>

    <script>
        // Menangani penghapusan dengan SweetAlert2
        document.querySelectorAll('.delete-btn').forEach((button) => {
            button.addEventListener('click', function(event) {
                event.preventDefault(); // Mencegah form untuk submit langsung

                const form = this.closest('form');
                const name = form.querySelector('input[name="fullname"]')?.value || 'This item'; // Atur nama item jika diperlukan

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: 'Anda tidak dapat mengembalikannya setelah dihapus!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit(); // Submit form jika konfirmasi diterima
                    }
                });
            });
        });
    </script>
@endpush

@section('content')

<div class="container-fluid mt-4 px-3">

    @include('/partials/breadcumb')

    <!-- Menampilkan pesan sukses atau error -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Input nilai username untuk pencarian -->
    <input type="hidden" name="username" id="username" value="{{ (isset($_GET['username'])) ? $_GET['username'] : '' }}">

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-fw fa-users me-1"></i>
            Customers
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Full Name</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Address</th>
                        <th>Created At</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($customers as $row)
                        <tr>
                            <td>{{ $row->fullname }}</td>
                            <td>{{ $row->username }}</td>
                            <td>{{ $row->email }}</td>
                            <td>{{ $row->role->role_name }}</td>
                            <td>{{ $row->gender == "M" ? "Male" : "Female" }}</td>
                            <td>{{ $row->phone }}</td>
                            <td>{{ $row->address }}</td>
                            <td>{{ $row->created_at->format('d-m-Y') }}</td>
                            <td>
                                <!-- Cek apakah user yang sedang login adalah Admin atau Owner, 
                                     dan pastikan Admin hanya bisa menghapus Customer, bukan Admin atau Owner -->
                                @if (auth()->user()->role->role_name === 'Admin' && $row->role->role_name === 'Customer' ||
                                     (auth()->user()->role->role_name === 'Owner' && auth()->user()->id !== $row->id && $row->role->role_name !== 'Owner'))
                                    <form action="{{ route('users.destroy', $row->id) }}" method="POST" style="display:inline;" class="delete-form">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="fullname" value="{{ $row->fullname }}">
                                        <button type="submit" class="btn btn-danger btn-sm delete-btn">Delete</button>
                                    </form>                            
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
