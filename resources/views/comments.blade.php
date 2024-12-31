@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Komentar Laporan Emisi Karbon</h1>

    <!-- Bagian untuk menampilkan komentar -->
    <div class="comments-list mb-5">
        <h2 class="mb-3">Komentar</h2>
        @if($comments->isEmpty())
            <p>Belum ada komentar. Jadilah yang pertama untuk berkomentar!</p>
        @else
            @foreach($comments as $comment)
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title">{{ $comment->user->name }}</h5>
                        <p class="card-text">{{ $comment->content }}</p>
                        <p class="text-muted">{{ $comment->created_at->format('d M Y, H:i') }}</p>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    <!-- Bagian untuk menambahkan komentar baru -->
    <div class="add-comment">
        <h2 class="mb-3">Tambahkan Komentar</h2>
        <form action="{{ route('comments.store') }}" method="POST">
            @csrf
            <div class="form-group mb-3">
                <textarea name="content" class="form-control" rows="4" placeholder="Tulis komentar Anda di sini..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary">Kirim Komentar</button>
        </form>
    </div>
</div>
@endsection
