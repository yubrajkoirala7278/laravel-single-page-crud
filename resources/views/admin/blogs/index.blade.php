@extends('admin.layouts.master')
@section('content')
<div class="container">
    {{-- CREATE/POST --}}
    @include('admin.blogs.create')
    {{-- end of CREATE/POST --}}
    <table class="table">
        <thead>
            <tr>
                <th scope="col">S.No</th>
                <th scope="col">Title</th>
                <th scope="col">Slug</th>
                <th scope="col">Description</th>
                <th scope="col">Image</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($blogs as $blog)
            <tr>
                <td>{{$blog->id}}</td>
                <td>{{$blog->title}}</td>
                <td>{{$blog->slug}}</td>
                <td>{{$blog->description}}</td>
                <td>
                    <img src="{{ asset('storage/images/blogs/'.$blog->image) }}" style="height: 30px" alt="{{$blog->image}}">
                </td>
                <td>
                    <div class="d-flex align-items-center" style="gap: 10px">
                    <form action="{{route('blogs.destroy',$blog)}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn  btn-danger  show-alert-delete-box " data-toggle="tooltip" title='Delete'>Delete</button>
                    </form>
                    <a href="{{route('blogs.show',$blog)}}" class="btn btn-success">View</a>
                    {{-- Update Blog --}}
                    <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#updateBlogs" onclick="updateForm(JSON.parse('{{ json_encode($blog) }}'))">
                        Update
                    </button>
                </td>
            </div>

            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- UPDATE/EDIT --}}
    @if (count($blogs)!=0)
        @include('admin.blogs.edit')
    @endif
    {{-- end of UPDATE/EDIT --}}
</div>
@endsection

@section('script')
<script>
    // to display current value in form for updation 
    function updateForm(blog) {
    document.getElementById('updateTitle').value = blog['title'];
    document.getElementById('updateSlug').value = blog['slug'];
    document.getElementById('updateDescription').value = blog['description'];
    document.getElementById('updateForm').action = "{{ route('blogs.update', ['blog' => ':id']) }}".replace(':id', blog['id']);
}


    // Empty input fields and error message when modal closed for CREATION and UPDATION 
    function clearForm(){
        document.getElementById('title').value = '';
        document.getElementById('slug').value = '';
        document.getElementById('description').value = '';
        document.querySelectorAll('.error-msg').forEach(function(element) {
             element.innerText = '';
        });
    }
    
    //Empty input fields and error message when modal closed for CREATION
    document.getElementById("addBlogs").addEventListener("hidden.bs.modal",()=> clearForm());

    // Empty input fields and error message when modal closed for UPDATION
    document.getElementById('updateBlogs').addEventListener("hidden.bs.modal", function(){
        clearForm();
    });
       
</script>

{{-- if error occurs while updation and creation then reopen modal --}}
@if($errors->any())
    @if(old('form_type') == 'update')
        <script>
            var myEditModal = new bootstrap.Modal(document.getElementById('updateBlogs'));
            myEditModal.show();
        </script>
    @else
        <script>
            var myCreateModal = new bootstrap.Modal(document.getElementById('addBlogs'));
            myCreateModal.show();
        </script>
    @endif
@endif
@endsection
