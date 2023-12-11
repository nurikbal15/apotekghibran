@extends('layout')
@section('content')
                <div class="container-fluid">

                    <!-- Page Heading -->
                    <h1 class="h3 mb-4 text-gray-800">Manajemen Penjualan</h1>

                    <div class="card shadow mb-4">
                        {{-- <div class="card-header py-3">
                          <h6 class="m-0 font-weight-bold text-primary">DataTables Example</h6>
                          <div class="m-0 card-header py-3 text-right">
                            <button type="submit" class="btn btn-primary">Submit</button>
                          </div>
                        </div> --}}


                        <div class="card-header py-3 d-flex justify-content-between align-items-center">
                            <h6 class="m-0 font-weight-bold text-primary">Data Penjualan</h6>
                        </div>

                        <div class="card-body">
                          <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>ID Transaksi</th>
                                        <th>Obat</th>
                                        <th>Harga Jual</th>
                                        <th>Jumlah</th>
                                        <th>Subtotal</th>
                                        <th>Karyawan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($penjualan as $index => $penjualan)
                                        <tr>
                                            <td>{{ $index+1 }}</td>
                                            {{-- <td>{{ $penjualan->id }}</td> --}}
                                            <td>{{ $penjualan->transaksi_id }}</td>
                                            <td>{{ $penjualan->obat->nama_obat }}</td>
                                            <td>{{ $penjualan->harga_jual }}</td>
                                            <td>{{ $penjualan->jumlah }}</td>
                                            <td>{{ $penjualan->subtotal }}</td>
                                            <td>{{ $penjualan->transaksi->user->name }}</td>

                                        </tr>
                                    @endforeach
                                </tbody>
                                    </table>
                            <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-scrollable" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalScrollableTitle">Edit User</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        </div>
                                        <div class="modal-body">
                                            <div id="modal-content-placeholder">
                                                <!-- Konten dari tampilan "edit" akan dimuat di sini -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                </div>
                <!-- /.container-fluid -->
@endsection
@push('scripts')
<script>
    $(document).ready(function() {
        $('.edit-button').click(function() {
            var userId = $(this).data('id');
            $('#editModal').modal('show');

            // Load the "edit" view content into the modal
            $.get("{{ route('user.edit', ':id') }}".replace(':id', userId), function(data) {
                // Proses data yang diterima dari server
                $('#modal-content-placeholder').html(data);
            });
        });
    });
</script>
<script>
    function confirmDelete() {
        if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
            document.getElementById('delete-form').submit();
        }
    }
</script>
@endpush
