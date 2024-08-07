@extends('lms.layout.main')

@section('content')
@include('partials.section_header')
<section class="section">
    <div class="section-body">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body table-responsive">
                        <div class="d-flex align-content-center">
                            <div class="flex-grow-1">
                                <div class="mr-3">
                                    <input type="text" class="form-control" value="{{ $quiz->quiz_name }}" readonly>
                                </div>
                            </div>
                            @can('lms-edit')

                            <button class="btn btn-warning mb-5" id="modal-import-btn">Import dari Excel</button>

                            <a href="{{ route('lms.admin.question.edit', $quiz->quiz_id) }}"
                                class="btn btn-success mb-5 ml-2" id="add_quiz_btn">Edit Pertanyaan</a>
                            @endcan
                            @can('lms-create')
                            <a href="{{ route('lms.admin.question.create', $quiz->quiz_id) }}"
                                class="btn btn-primary mb-5 ml-2" id="add_quiz_btn">Tambah Pertanyaan</a>
                            @endcan
                        </div>

                        <table class="table table-striped table-hover" width="100%" id="courses-table">
                            <thead>
                                <tr>
                                    <th width="2px">#</th>
                                    <th>Pertanyaan</th>
                                    <th>Tipe Pertanyaan</th>
                                    @can('lms-edit', 'lms-delete')
                                    <th width="5%" class="text-center">Aksi</th>
                                    @endcan
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(document).ready(function() {
        var slug = "{{ request()->segment(4) }}";

        var ajaxConfig = {
            url: '{{ route('lms.admin.question.getallquestion') }}' + '/' + slug,
        };

        $('#courses-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: ajaxConfig,
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'question_text', name: 'question_text' },
                { data: 'question_type', name: 'question_type' },
                @can('lms-edit', 'lms-delete')
                { data: 'action', name: 'action', orderable: false, searchable: false, className: 'text-center' }
                @endcan
            ],
            responsive: true
        });
    });
</script>
@endpush

@push('js')
@include('lms.partials.alert')
@can('lms-delete')
@include('lms.partials.modal_delete')

<div class="modal fade" data-backdrop="static" id="modal-import-modal" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Import Pertanyaan Kuis</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{ route('lms.admin.question.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">

                    <p>Template Excel Import Kuis : <a
                            href="{{ asset('files/template_bank_soal_lms_bangkom_pretest.xlsx') }}">Download</a>
                    </p>

                    <div>
                        <input type="file" name="file" id="file" class="dropify" data-max-file-size="5M" required>
                        <input type="hidden" name="quiz_id" id="quiz_id" value="{{ $quiz->quiz_id }}" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Import Pertanyaan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).on('click', '.delete', function() {
        var courseId = $(this).data('id');
        var courseName = $(this).data('name');

        $('#text-item-delete').text(courseName);
        $('#form-delete').attr('action', '{{ route("lms.admin.question.destroy", ":id") }}'.replace(':id', courseId));
        $('#deletemodal').modal('show');
    });
</script>
@endcan

<script src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>

<script>
    $(document).ready(function() {
        $('.dropify').dropify();
    });

    $(document).on('click', '#modal-import-btn', function() {
        $('#modal-import-modal').modal('show');
    });
</script>
@endpush

@push('css')
<link rel="stylesheet" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">
@endpush