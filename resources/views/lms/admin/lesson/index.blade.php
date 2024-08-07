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
                            <div class="flex-grow-1 bd-highlight">
                                <div class="form-row">
                                    <div class="form-group">
                                        <div class="d-flex justify-content-between">
                                            <select name="course_id" id="course_id" class="form-control mr-2">
                                                <option value="" selected>Pilih Kursus</option>
                                                @foreach ($courses as $course)
                                                <option value="{{ $course->slug }}" {{ request()->segment(4) ==
                                                    $course->slug || session('course_slug') == $course->slug ?
                                                    'selected' : '' }}>
                                                    {{ $course->course_name }}
                                                </option>
                                                @endforeach
                                            </select>
                                            <select name="module_id" id="module_id" class="form-control">
                                                <option value="">Pilih Module</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @can('lms-create')
                            <a href="#" class="btn btn-primary mb-5" id="admin_lesson_btn">Tambah Pelajaran</a>
                            @endcan
                        </div>

                        <table class="table table-striped table-hover" width="100%" id="courses-table">
                            <thead>
                                <tr>
                                    <th width="5%">Bab Pelajaran</th>
                                    <th>Nama Modul</th>
                                    <th>Nama Pelajaran</th>
                                    <th>Tipe Konten</th>
                                    @can('lms-edit', 'lms-delete')
                                    <th width="15%">Aksi</th>
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
        var currentUrl = window.location.href.split('/');
        var slug = "{{ request()->segment(4) ?: session('course_slug') }}";
        var moduleId = "{{ request()->segment(5) ?: session('module_id') }}";

        console.log(slug, moduleId);

        var ajaxConfig = {
            url: '{{ route('lms.admin.lesson.getalllessons') }}' + '/' + slug + '/' + moduleId,
        };

        $('#courses-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: ajaxConfig,
            columns: [
                { data: 'lesson_chapter', name: 'lesson_chapter', className: 'text-center' },
                { data: 'module.module_name', name: 'module.module_name' },
                { data: 'lesson_name', name: 'lesson_name' },
                { data: 'content_type', name: 'content_type', className: 'text-center'},
                @can('lms-edit', 'lms-delete')
                { data: 'action', name: 'action', orderable: false, searchable: false }
                @endcan
            ],
            responsive: true
        });
        }
    );
</script>

<script>
    @can('lms-list')
    document.getElementById('course_id').addEventListener('change', function () {
        var slug = this.value || '{{ request()->segment(4) ?: session('course_slug') }}';
        loadModules(slug);
    });

    function loadModules(slug) {
        if (!slug) {
            slug = '{{ request()->segment(4) ?: session('course_slug') }}';
        }

        if (slug) {
            fetch(`{{ route('lms.admin.module.getmodule') }}?slug=${slug}`)
                .then(response => response.json())
                .then(data => {
                    var moduleSelect = document.getElementById('module_id');
                    moduleSelect.innerHTML = '<option value="" selected>Pilih Module</option>'; // Reset options
                    data.forEach(function (module) {
                        var option = document.createElement('option');
                        option.value = module.module_id;
                        option.text = module.module_name;
                        moduleSelect.appendChild(option);
                    });

                    // Preselect the module if module_id is in the URL or session
                    const urlParams = window.location.pathname.split('/');
                    const moduleId = urlParams[urlParams.length - 1] || '{{ session('module_id') }}';
                    if (moduleId && !isNaN(moduleId)) { // Ensure moduleId is a number
                        moduleSelect.value = moduleId;
                    } else {
                        moduleSelect.value = ''; // Ensure "Pilih Module" is selected
                    }
                })
                .catch(error => console.error('Error:', error));
        } else {
            document.getElementById('module_id').innerHTML = '<option value="" selected>Pilih Module</option>'; // Reset options
        }
    }
    @endcan

    // Listen for changes on the module select
    $('#module_id').on('change', function () {
        var courseSlug = $('#course_id').val();
        var moduleId = $(this).val();

        if (courseSlug && moduleId) {
            var url = '{{ url("/lms/admin/lesson") }}/' + courseSlug + '/' + moduleId;
            window.location.href = url;
        }
    });

    // Update 'Tambah Module' button link with slug
    var courseSelect = document.getElementById('course_id');
    var addModuleBtn = document.getElementById('admin_lesson_btn');

    const initialSlug = '{{ request()->segment(4) ?: session('course_slug') }}';
    const initialModuleId = '{{ request()->segment(5) ?: session('module_id') }}';

    // Set initial 'Tambah Module' button link based on selected course
    window.onload = function () {
        if (initialSlug) {
            courseSelect.value = initialSlug;
            addModuleBtn.href = "{{ route('lms.admin.lesson.create') }}" + "/" + initialSlug + "/" + initialModuleId;
            loadModules(initialSlug);
        } else {
            addModuleBtn.href = "{{ route('lms.admin.lesson.create') }}";
        }

        // Ensure "Pilih Kursus" is selected if no course is selected
        if (!courseSelect.value) {
            courseSelect.value = "";
        }

        // Preselect the module if module_id is in the URL or session after loading modules
        if (initialModuleId && !isNaN(initialModuleId)) {
            const intervalId = setInterval(() => {
                const moduleSelect = document.getElementById('module_id');
                if (moduleSelect.options.length > 1) { // Ensure options are loaded
                    moduleSelect.value = initialModuleId;
                    clearInterval(intervalId); // Stop the interval once set
                }
            }, 100); // Check every 100ms
        }
    };
</script>

@endpush

@push('js')
@include('lms.partials.alert')
@can('lms-delete')
@include('lms.partials.modal_delete')
<script>
    $(document).on('click', '.delete', function() {
        var courseId = $(this).data('id');
        var courseName = $(this).data('name');

        $('#text-item-delete').text(courseName);
        $('#form-delete').attr('action', '{{ route("lms.admin.lesson.destroy", ":id") }}'.replace(':id', courseId));
        $('#deletemodal').modal('show');
    });
</script>
@endcan
@endpush