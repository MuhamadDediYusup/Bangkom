<?php

namespace App\Http\Livewire\Lms\Class;

use App\Models\LMS\Course;
use Livewire\Component;

class Index extends Component
{
    public $course_slug;
    public $id_module;
    public $id_lesson;
    public $module;
    public $lesson;

    public function mount($course_slug, $id_module, $id_lesson)
    {
        $this->course_slug = $course_slug;
        $this->id_module = $id_module;
        $this->id_lesson = $id_lesson;

        // Logika untuk mengambil data berdasarkan parameter
        $course = Course::with(['modules.lessons'])
            ->where('slug', $course_slug)
            ->firstOrFail();

        $this->module = $course->modules->firstWhere('module_id', $id_module);
        $this->lesson = $this->module->lessons->firstWhere('id_lesson', $id_lesson);
    }

    public function render()
    {
        return view('livewire.lms.class.index');
    }
}
