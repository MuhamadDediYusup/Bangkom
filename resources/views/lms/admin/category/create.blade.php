@extends('lms.layout.main')

@section('content')
@include('partials.section_header')
@include('lms.partials.alert-any')

<section class="section">
    <div class="section-body">
        <form action="{{ route('lms.admin.category.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <label>Nama Ketegori</label>
                                <input type="text" id="category_name" name="category_name" class="form-control" required
                                    value="{{ old('category_name') }}">
                                @error('category_name')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label>Slug</label>
                                <input type="text" id="slug" name="slug" class="form-control" required
                                    value="{{ old('slug') }}">
                                @error('slug')
                                <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label">Color Tag</label>
                                <div class="row gutters-xs">
                                    <div class="col-auto">
                                        <label class="colorinput">
                                            <input name="color_tag" type="checkbox" value="bg-dark"
                                                class="colorinput-input" />
                                            <span class="colorinput-color bg-dark"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto">
                                        <label class="colorinput">
                                            <input name="color_tag" type="checkbox" value="bg-primary"
                                                class="colorinput-input" />
                                            <span class="colorinput-color bg-primary"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto">
                                        <label class="colorinput">
                                            <input name="color_tag" type="checkbox" value="bg-secondary"
                                                class="colorinput-input" />
                                            <span class="colorinput-color bg-secondary"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto">
                                        <label class="colorinput">
                                            <input name="color_tag" type="checkbox" value="bg-success"
                                                class="colorinput-input" />
                                            <span class="colorinput-color bg-success"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto">
                                        <label class="colorinput">
                                            <input name="color_tag" type="checkbox" value="bg-danger"
                                                class="colorinput-input" />
                                            <span class="colorinput-color bg-danger"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto">
                                        <label class="colorinput">
                                            <input name="color_tag" type="checkbox" value="bg-warning"
                                                class="colorinput-input" />
                                            <span class="colorinput-color bg-warning"></span>
                                        </label>
                                    </div>
                                    <div class="col-auto">
                                        <label class="colorinput">
                                            <input name="color_tag" type="checkbox" value="bg-info"
                                                class="colorinput-input" />
                                            <span class="colorinput-color bg-info"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <script>
                                const checkboxes = document.querySelectorAll('input[name="color_tag"]');
                                checkboxes.forEach((checkbox) => {
                                    checkbox.addEventListener('change', function() {
                                        checkboxes.forEach((cb) => {
                                            if (cb !== this) {
                                                cb.checked = false;
                                            }
                                        });
                                    });
                                });
                            </script>
                            <button type="submit" class="btn btn-primary">Simpan Ketegori</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</section>
@endsection

@push('js')
<script src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>

<script>
    $(document).ready(function() {
        $('.dropify').dropify();
    });
</script>

<script>
    document.getElementById('category_name').addEventListener('input', function() {
        var categoryName = this.value;
        var slug = categoryName.toLowerCase()
            .replace(/[^\w\s-]/g, '') // Hapus karakter yang tidak diinginkan
            .trim() // Hapus spasi di awal dan akhir
            .replace(/\s+/g, '-') // Ganti spasi dengan tanda hubung
            .replace(/-+/g, '-'); // Ganti tanda hubung berlebih dengan satu tanda hubung
        document.getElementById('slug').value = slug;
    });
</script>
@endpush

@push('css')
<link rel="stylesheet" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">
@endpush