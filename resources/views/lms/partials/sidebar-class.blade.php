<div class="main-sidebar" tabindex="1" style="overflow: hidden; outline: none;">
    <aside id="sidebar-wrapper">
        <div class="card">
            <div class="card-body">
                <div class="title-sidebar">
                    Daftar Materi
                </div>
                <div id="accordion" class="accordion">
                    @foreach ($course->modules as $module)
                    <div class="accordion-header" role="button" data-toggle="collapse"
                        data-target="#panel-body-{{ $module->module_id }}" aria-expanded="false"
                        aria-controls="panel-body-{{ $module->module_id }}">
                        <h4>{{ $module->module_chapter }}. {{ $module->module_name }} ({{ $module->estimated_time }}
                            Menit)</h4>
                    </div>
                    <div class="accordion-body collapse" id="panel-body-{{ $module->module_id }}"
                        data-parent="#accordion">
                        <ul class="list-unstyled">
                            @php
                            $previousCompleted = true;
                            $previousModuleLastLessonCompleted = true;
                            $moduleIndex = $loop->index;

                            // Check if the last lesson of the previous module is completed
                            if ($moduleIndex > 0) {
                            $previousModule = $course->modules[$moduleIndex - 1];
                            $lastLessonOfPreviousModule = $previousModule->lessons->last();
                            $previousModuleLastLessonCompleted = $lessonStatus::isCompleted(Auth::user()->user_id,
                            $lastLessonOfPreviousModule->lesson_id);
                            }
                            @endphp
                            @foreach ($module->lessons as $lesson)
                            @inject('lessonStatus', 'App\Models\LMS\LessonStatus')
                            @php
                            $isCompleted = $lessonStatus::isCompleted(Auth::user()->user_id, $lesson->lesson_id);
                            @endphp
                            <li>
                                @if ($previousCompleted && $previousModuleLastLessonCompleted)
                                <a href="{{ route('lms.class.index', [$course->slug, $lesson->module_id, $lesson->lesson_id]) }}"
                                    class="lesson-link {{ Request::is('lms/class/*/'.$lesson->module_id.'/'.$lesson->lesson_id) ? 'active' : '' }}"
                                    data-module-id="{{ $lesson->module_id }}" data-lesson-id="{{ $lesson->lesson_id }}">
                                    @if ($isCompleted)
                                    <span class="text-secondary">
                                        <i class="fas fa-check"></i>
                                    </span>
                                    @else
                                    <span class="text-secondary">
                                        <i class="far fa-square"></i>
                                    </span>
                                    @endif
                                    {{ $lesson->lesson_name }}
                                </a>
                                @else
                                <a href="#" class="lesson-link locked" data-module-id="{{ $lesson->module_id }}"
                                    data-lesson-id="{{ $lesson->lesson_id }}">
                                    <span class="text-secondary">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    {{ $lesson->lesson_name }}
                                </a>
                                @endif
                                @php
                                $previousCompleted = $isCompleted;
                                @endphp
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </aside>
</div>

@push('js')
<script>
    $(document).ready(function() {
        var url = window.location.href;

        var lessonLinks = document.querySelectorAll(".lesson-link");
        lessonLinks.forEach(function(link) {
            if (link.href === url) {
                var accordionBody = link.closest(".accordion-body");
                accordionBody.classList.add("show");

                var accordionHeader = accordionBody.previousElementSibling;
                accordionHeader.setAttribute("aria-expanded", "true");

                link.classList.add("active");
            }
        });

        // Handle locked lessons with SweetAlert
        $('.lesson-link.locked').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'Pelajaran Terkunci',
                text: 'Maaf, Anda belum bisa membuka modul ini. Mohon pastikan semua modul sebelumnya (termasuk submission/quiz) sudah diselesaikan.',
                confirmButtonText: 'Tutup'
            });
        });

        @if (session()->has('error'))
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: '{{ session('error') }}',
            confirmButtonText: 'Tutup'
        });
        @endif
    });
</script>
@endpush

@push('css')
<style>
    .accordion-header {
        background-color: #007bff;
        color: #fff;
        padding: 1rem;
        cursor: pointer;
        border-radius: 5px;
        margin-bottom: 0.5rem;
    }

    .accordion-header:hover {
        background-color: #0056b3;
    }

    .accordion-body {
        background-color: #e9ecef;
        border: 1px solid #dee2e6;
        border-radius: 5px;
        padding: 1rem;
    }

    .lesson-link {
        display: block;
        padding: 0.5rem;
        color: #007bff;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .lesson-link:hover {
        background-color: #d1ecf1;
    }

    .lesson-link.active {
        font-weight: bold;
    }

    .lesson-link.locked {
        color: #6c757d;
        cursor: not-allowed;
    }

    .lesson-link .fas,
    .lesson-link .far {
        margin-right: 0.5rem;
    }
</style>
@endpush