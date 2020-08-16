@extends('admin.main')

@section('css')
<link href="{{asset('vendor/datatables-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet">
<link href="{{asset('vendor/datatables-responsive/css/responsive.bootstrap4.min.css')}}" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('main-content')
<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Kelola Voucher</h1>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @elseif (session('warning'))
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    {{ session('warning') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @elseif (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
                <div class="card">
                    <div class="card-body">
                        <button class="btn btn-primary mb-3" data-toggle="modal" data-target="#tambahVoucher">Tambah
                            Voucher</button>
                        <h3>Daftar Voucher</h3>
                        <table id="tbl-members" class="table dt-responsive nowrap" style="width: 100%">
                            <thead class="thead-light">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Voucher</th>
                                    <th>Poin Diperlukan</th>
                                    <th>Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($vouchers as $voucher)
                                <tr>
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{$voucher->nama_voucher}}</td>
                                    <td>{{$voucher->poin_need}}</td>
                                    <td>{{$voucher->keterangan}}</td>
                                    <td>
                                        @if ($voucher->aktif != 0)
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input aktif-check" checked
                                                value="{{$voucher->id_voucher}}">
                                            <label class="form-check-label">Aktif</label>
                                        </div>
                                        @else
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input aktif-check"
                                                value="{{$voucher->id_voucher}}">
                                            <label class="form-check-label">Aktif</label>
                                        </div>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</div>

<!-- Modal -->
<div class="modal fade" id="tambahVoucher" tabindex="-1" role="dialog" aria-labelledby="tambahVoucherLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tambahVoucherLabel">Tambah Voucher</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{url('admin/voucher/tambah')}}" method="post">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label for="potongan">Potongan</label>
                        <input type="number" class="form-control" id="potongan" name="potongan"
                            placeholder="Besar potongan harga">
                    </div>
                    <div class="form-group">
                        <label for="poin">Poin diperlukan</label>
                        <input type="number" class="form-control" id="poin" name="poin" placeholder="Poin diperlukan">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="{{asset('vendor/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('vendor/datatables-bs4/js/dataTables.bootstrap4.min.js')}}"></script>
<script src="{{asset('vendor/datatables-responsive/js/dataTables.responsive.min.js')}}"></script>
<script src="{{asset('vendor/datatables-responsive/js/responsive.bootstrap4.min.js')}}"></script>
<script src="{{asset('js/voucher-ajax.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $('#tbl-members').DataTable();
    });
</script>
@endsection