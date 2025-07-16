<!-- First you need to extend the CB layout -->
@extends('crudbooster::admin_template')
@section('content')
<!-- Your custom  HTML goes here -->
<table id="table_dashboard" class='table table-hover table-striped table-bordered'>
  <thead>
      <tr>
        <th>Name</th>
        <th>Email</th>
        <th>Image</th>
        <th>Action</th>
       </tr>
  </thead>
  <tbody>
    @foreach($data as $item)
      <tr>
        <td>{{ $item['name'] }}</td>
        <td>{{ $item['email'] }}</td>
        <td><img src="{{ $item['imageUrl'] }}"></td>
        <td>
          <!-- To make sure we have read access, wee need to validate the privilege -->
          <button class="btn btn-success btn-sm" href='{{$item['uid']}}'><i class="fa fa-edit"></i> Edit</button>
           <button class="btn btn-danger btn-sm" href='{{$item['uid']}}'><i class="fa fa-trash"></i> Delete</button>
        </td>
       </tr>
    @endforeach
  </tbody>
</table>

<!-- ADD A PAGINATION -->

@endsection
