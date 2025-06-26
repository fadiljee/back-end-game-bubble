@extends('admin.layout')

@section('content')

<div class="content-wrapper">
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <!-- left column -->
        <div class="col-md-12">
          <!-- general form elements -->
          <div class="card card-primary">
            

            @if($errors->any())
            <ul style="color:red;">
              @foreach($errors->all() as $error)
              <li> {{ $error }}</li>
              @endforeach
            </ul>
            @endif

            

            <div class="card-header">
              <h3 class="card-title">Data Quiz</h3>
            </div>
  
            @if(session('success'))
            <p style="color:green"> {{ session('success') }}</p>
            @endif
  
            <div class="card-body">
              <table id="example1" class="table table-bordered table-striped">
                <a href="{{ route('formregister')}}" class="btn btn-success"><i class="nav-icon fas fa-user-plus"></i>Tambah</a>
                <thead>
                  <tr>
                    <th>Materi</th>
                    <th>Jumlah Soal</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                {{-- <tbody>
                  @foreach ($users as $user)
                  <tr>
                    <td>{{$user->nama}}</td>
                    <td>{{$user->nisn}}</td>
                    <td>
                      <a href="{{ route('useredit', $user->id) }}" class="btn btn-primary">Edit</a> |
                      <form action="{{ route('userdelete', $user->id) }}" method="post" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger"  onclick="return confirm('Yakin ingin menghapus data ini?');">Hapus</button>
                      </form>
                    </td>
                  </tr>
                  @endforeach
                </tbody> --}}
              </table>
            </div> <!-- /.card-body -->
        <div class="col-12">
          <div class="card">
          </div> <!-- /.card -->
        </div> <!-- /.col -->
      </div>
  </section>
</div>

@endsection