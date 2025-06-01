@extends('layouts.cms')

@section('content')
{{-- hanya SweetAlert, tidak butuh tampilan lain --}}
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    Swal.fire({
        title: 'Ingin menambahkan chapter untuk eBook ini?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Ya',
        cancelButtonText: 'Tidak',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
    }).then(result => {
        if (result.isConfirmed) {
            window.location = "{{ route('cms.ebooks.chapters.create', $ebook->id) }}";
        } else {
            window.location = "{{ route('cms.ebooks.index') }}";
        }
    });
});
</script>
@endpush
