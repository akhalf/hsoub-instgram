@extends('layouts.app')

@section('content')
<div class="album py-5 bg-light">
    <div class="container">

        <div class="row">
            <!-- foreach post -->
            @forelse($posts as $post)
            <div class="col-md-4">
                <div class="card mb-4 box-shadow">
                    <!-- post image -->
                    <img class="card-img-top" src="{{ asset('images/'.$post->image_path) }}" alt="Card image cap" style="height: 250px">
                    <div class="card-body" style="height:  116px;">
                        <!-- post body -->
                        <p class="card-text" style="text-align: right;direction:  rtl;">{{ Str::limit($post->body, 80)  }}</p>
                        <br>
                    </div>
                    <div class="card-footer">
                        <div class="d-flex justify-content-between align-items-center">
                            <!-- post actions -->
                            <form action="{{ route('post.destroy', $post->id) }}" method="post">
                                <div class="btn-group">
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('post.show', $post->id) }}">عرض</a>
                                    <a class="btn btn-sm btn-outline-secondary" href="{{ route('post.edit', $post->id) }}">تعديل</a>
                                    @method('DELETE')
                                    @csrf
                                    <button class="btn btn-sm btn-outline-secondary">حذف</button>
                                </div>
                            </form>
                            <!-- post date -->
                            <small class="text-muted"></small>
                        </div>
                    </div>
                </div>
            </div>
            <!-- foreach post end -->
            @empty
                <a href="{{ route('post.create') }}">Add your first post</a>

            @endforelse

            @isset($posts)
                {{ $posts->links('pagination::bootstrap-4') }}
                @endisset
        </div>
        <!-- Paginations -->
    </div>
</div>
@endsection
