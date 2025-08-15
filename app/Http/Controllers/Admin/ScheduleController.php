<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\Section;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\TeacherSubjectSection;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class ScheduleController extends Controller
{
public function index(Request $request)
    {
        $teachers = Teacher::all()->map(function ($teacher) {
            return [
                'id' => $teacher->id,
                'first_name' => $teacher->first_name,
                'last_name' => $teacher->last_name,
                'name' => $teacher->name
            ];
        });

        $subjects = Subject::all()->map(function ($subject) {
            return [
                'id' => $subject->id,
                'subject_name' => $subject->subject_name
            ];
        });

        $sections = Section::all()->map(function ($section) {
            return [
                'id' => $section->id,
                'grade_level' => $section->grade_level,
                'section_name' => $section->section_name
            ];
        });

        $teacherSubjectSections = TeacherSubjectSection::with(['teacher', 'subject'])->get()->map(function ($tss) {
            return [
                'id' => $tss->id,
                'teacher_id' => $tss->teacher_id,
                'subject_id' => $tss->subject_id,
                'teacher' => $tss->teacher ? [
                    'id' => $tss->teacher->id,
                    'first_name' => $tss->teacher->first_name,
                    'last_name' => $tss->teacher->last_name,
                    'name' => $tss->teacher->name
                ] : null,
                'subject' => $tss->subject ? [
                    'id' => $tss->subject->id,
                    'subject_name' => $tss->subject->subject_name
                ] : null
            ];
        });

        $schedules = Schedule::with([
                'teacherSubjectSection.teacher',
                'teacherSubjectSection.subject',
                'section'
            ])
            ->when($request->section_id, function ($query) use ($request) {
                return $query->where('section_id', $request->section_id);
            })
            ->when($request->grade_level, function ($query) use ($request) {
                return $query->whereHas('section', function ($q) use ($request) {
                    $q->where('grade_level', $request->grade_level);
                });
            })
            ->when($request->day_of_week, function ($query) use ($request) {
                return $query->where('day_of_week', $request->day_of_week);
            })
            ->when($request->semester, function ($query) use ($request) {
                return $query->where('semester', $request->semester);
            })
            ->when($request->search, function ($query) use ($request) {
                return $query->where(function ($q) use ($request) {
                    $q->whereHas('teacherSubjectSection.subject', function ($q2) use ($request) {
                        $q2->where('subject_name', 'like', '%' . $request->search . '%')
                           ->orWhere('subject_code', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('teacherSubjectSection.teacher', function ($q2) use ($request) {
                        $q2->where('first_name', 'like', '%' . $request->search . '%')
                           ->orWhere('last_name', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('section', function ($q2) use ($request) {
                        $q2->where('section_name', 'like', '%' . $request->search . '%')
                           ->orWhere('room_number', 'like', '%' . $request->search . '%');
                    });
                });
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'teacher_id' => $schedule->teacherSubjectSection->teacher_id,
                    'subject_id' => $schedule->teacherSubjectSection->subject_id,
                    'section_id' => $schedule->section_id,
                    'day_of_week' => $schedule->day_of_week,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'section' => $schedule->section ? [
                        'id' => $schedule->section->id,
                        'grade_level' => $schedule->section->grade_level,
                        'section_name' => $schedule->section->section_name
                    ] : null
                ];
            });

        $daysOfWeek = [
            ['id' => 'Monday', 'name' => 'Monday'],
            ['id' => 'Tuesday', 'name' => 'Tuesday'],
            ['id' => 'Wednesday', 'name' => 'Wednesday'],
            ['id' => 'Thursday', 'name' => 'Thursday'],
            ['id' => 'Friday', 'name' => 'Friday'],
            ['id' => 'Saturday', 'name' => 'Saturday'],
            ['id' => 'Sunday', 'name' => 'Sunday']
        ];

        Log::info('Schedules data:', $schedules->toArray());

        return Inertia::render('Admin/Schedules/Index', [
            'schedules' => $schedules,
            'teachers' => $teachers,
            'subjects' => $subjects,
            'sections' => $sections,
            'teacherSubjectSections' => $teacherSubjectSections,
            'daysOfWeek' => $daysOfWeek
        ]);
    }

    public function create(Request $request)
    {
        $teachers = Teacher::all()->map(function ($teacher) {
            return [
                'id' => $teacher->id,
                'first_name' => $teacher->first_name,
                'last_name' => $teacher->last_name,
                'name' => $teacher->name
            ];
        });

        $subjects = Subject::all()->map(function ($subject) {
            return [
                'id' => $subject->id,
                'subject_name' => $subject->subject_name
            ];
        });

        $sections = Section::all()->map(function ($section) {
            return [
                'id' => $section->id,
                'grade_level' => $section->grade_level,
                'section_name' => $section->section_name
            ];
        });

        $teacherSubjectSections = TeacherSubjectSection::with(['teacher', 'subject'])->get()->map(function ($tss) {
            return [
                'id' => $tss->id,
                'teacher_id' => $tss->teacher_id,
                'subject_id' => $tss->subject_id,
                'teacher' => $tss->teacher ? [
                    'id' => $tss->teacher->id,
                    'first_name' => $tss->teacher->first_name,
                    'last_name' => $tss->teacher->last_name,
                    'name' => $tss->teacher->name
                ] : null,
                'subject' => $tss->subject ? [
                    'id' => $tss->subject->id,
                    'subject_name' => $tss->subject->subject_name
                ] : null
            ];
        });

        $schedules = Schedule::with([
                'teacherSubjectSection.teacher',
                'teacherSubjectSection.subject',
                'section'
            ])
            ->when($request->section_id, function ($query) use ($request) {
                return $query->where('section_id', $request->section_id);
            })
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'teacher_id' => $schedule->teacherSubjectSection->teacher_id,
                    'subject_id' => $schedule->teacherSubjectSection->subject_id,
                    'section_id' => $schedule->section_id,
                    'day_of_week' => $schedule->day_of_week,
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'section' => $schedule->section ? [
                        'id' => $schedule->section->id,
                        'grade_level' => $schedule->section->grade_level,
                        'section_name' => $schedule->section->section_name
                    ] : null
                ];
            });

        $daysOfWeek = [
            ['id' => 'Monday', 'name' => 'Monday'],
            ['id' => 'Tuesday', 'name' => 'Tuesday'],
            ['id' => 'Wednesday', 'name' => 'Wednesday'],
            ['id' => 'Thursday', 'name' => 'Thursday'],
            ['id' => 'Friday', 'name' => 'Friday'],
            ['id' => 'Saturday', 'name' => 'Saturday'],
            ['id' => 'Sunday', 'name' => 'Sunday']
        ];

        return Inertia::render('Admin/Schedules/Create', [
            'schedules' => $schedules,
            'teachers' => $teachers,
            'subjects' => $subjects,
            'sections' => $sections,
            'teacherSubjectSections' => $teacherSubjectSections,
            'daysOfWeek' => $daysOfWeek
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'section_id' => 'required|exists:sections,id',
            'day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $teacherSubjectSection = TeacherSubjectSection::firstOrCreate([
            'teacher_id' => $validated['teacher_id'],
            'subject_id' => $validated['subject_id']
        ]);

        $conflict = $this->checkScheduleConflict(
            $validated['teacher_id'],
            $validated['section_id'],
            $validated['day_of_week'],
            $validated['start_time'],
            $validated['end_time']
        );

        if ($conflict) {
            return response()->json([
                'errors' => ['schedule_conflict' => $conflict]
            ], 422);
        }

        $schedule = Schedule::create([
            'teacher_subject_section_id' => $teacherSubjectSection->id,
            'section_id' => $validated['section_id'],
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time']
        ]);

        return response()->json([
            'success' => 'Schedule created successfully.',
            'schedule' => [
                'id' => $schedule->id,
                'teacher_id' => $teacherSubjectSection->teacher_id,
                'subject_id' => $teacherSubjectSection->subject_id,
                'section_id' => $schedule->section_id,
                'day_of_week' => $schedule->day_of_week,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'section' => $schedule->section ? [
                    'id' => $schedule->section->id,
                    'grade_level' => $schedule->section->grade_level,
                    'section_name' => $schedule->section->section_name
                ] : null
            ]
        ]);
    }

    public function edit($id)
    {
        $schedule = Schedule::with([
                'teacherSubjectSection.teacher',
                'teacherSubjectSection.subject',
                'section'
            ])->findOrFail($id);

        $teachers = Teacher::all()->map(function ($teacher) {
            return [
                'id' => $teacher->id,
                'first_name' => $teacher->first_name,
                'last_name' => $teacher->last_name,
                'name' => $teacher->name
            ];
        });

        $subjects = Subject::all()->map(function ($subject) {
            return [
                'id' => $subject->id,
                'subject_name' => $subject->subject_name
            ];
        });

        $sections = Section::all()->map(function ($section) {
            return [
                'id' => $section->id,
                'grade_level' => $section->grade_level,
                'section_name' => $section->section_name
            ];
        });

        $teacherSubjectSections = TeacherSubjectSection::with(['teacher', 'subject'])->get()->map(function ($tss) {
            return [
                'id' => $tss->id,
                'teacher_id' => $tss->teacher_id,
                'subject_id' => $tss->subject_id,
                'teacher' => $tss->teacher ? [
                    'id' => $tss->teacher->id,
                    'first_name' => $tss->teacher->first_name,
                    'last_name' => $tss->teacher->last_name,
                    'name' => $tss->teacher->name
                ] : null,
                'subject' => $tss->subject ? [
                    'id' => $tss->subject->id,
                    'subject_name' => $tss->subject->subject_name
                ] : null
            ];
        });

        $daysOfWeek = [
            ['id' => 'Monday', 'name' => 'Monday'],
            ['id' => 'Tuesday', 'name' => 'Tuesday'],
            ['id' => 'Wednesday', 'name' => 'Wednesday'],
            ['id' => 'Thursday', 'name' => 'Thursday'],
            ['id' => 'Friday', 'name' => 'Friday'],
            ['id' => 'Saturday', 'name' => 'Saturday'],
            ['id' => 'Sunday', 'name' => 'Sunday']
        ];

        return Inertia::render('Admin/Schedules/Edit', [
            'schedule' => [
                'id' => $schedule->id,
                'teacher_id' => $schedule->teacherSubjectSection->teacher_id,
                'subject_id' => $schedule->teacherSubjectSection->subject_id,
                'section_id' => $schedule->section_id,
                'day_of_week' => $schedule->day_of_week,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'section' => $schedule->section ? [
                    'id' => $schedule->section->id,
                    'grade_level' => $schedule->section->grade_level,
                    'section_name' => $schedule->section->section_name
                ] : null
            ],
            'teachers' => $teachers,
            'subjects' => $subjects,
            'sections' => $sections,
            'teacherSubjectSections' => $teacherSubjectSections,
            'daysOfWeek' => $daysOfWeek
        ]);
    }

    public function update(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $validated = $request->validate([
            'teacher_id' => 'required|exists:teachers,id',
            'subject_id' => 'required|exists:subjects,id',
            'section_id' => 'required|exists:sections,id',
            'day_of_week' => 'required|string|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        $teacherSubjectSection = TeacherSubjectSection::firstOrCreate([
            'teacher_id' => $validated['teacher_id'],
            'subject_id' => $validated['subject_id']
        ]);

        $conflict = $this->checkScheduleConflict(
            $validated['teacher_id'],
            $validated['section_id'],
            $validated['day_of_week'],
            $validated['start_time'],
            $validated['end_time'],
            $id
        );

        if ($conflict) {
            return response()->json([
                'errors' => ['schedule_conflict' => $conflict]
            ], 422);
        }

        $schedule->update([
            'teacher_subject_section_id' => $teacherSubjectSection->id,
            'section_id' => $validated['section_id'],
            'day_of_week' => $validated['day_of_week'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time']
        ]);

        return response()->json([
            'success' => 'Schedule updated successfully.',
            'schedule' => [
                'id' => $schedule->id,
                'teacher_id' => $teacherSubjectSection->teacher_id,
                'subject_id' => $teacherSubjectSection->subject_id,
                'section_id' => $schedule->section_id,
                'day_of_week' => $schedule->day_of_week,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'section' => $schedule->section ? [
                    'id' => $schedule->section->id,
                    'grade_level' => $schedule->section->grade_level,
                    'section_name' => $schedule->section->section_name
                ] : null
            ]
        ]);
    }


    public function destroy($id)
    {
        $schedule = Schedule::findOrFail($id);
        $schedule->delete();

        return response()->json([
            'success' => 'Schedule deleted successfully.'
        ]);
    }

    private function checkScheduleConflict($teacherId, $sectionId, $dayOfWeek, $startTime, $endTime, $excludeId = null)
    {
        $teacherConflict = Schedule::whereHas('teacherSubjectSection', function ($query) use ($teacherId) {
                $query->where('teacher_id', $teacherId);
            })
            ->where('day_of_week', $dayOfWeek)
            ->when($excludeId, function ($query, $excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                          ->where('end_time', '>=', $endTime);
                    });
            })
            ->exists();

        if ($teacherConflict) {
            return 'Teacher has a schedule conflict at the selected time.';
        }

        $sectionConflict = Schedule::where('section_id', $sectionId)
            ->where('day_of_week', $dayOfWeek)
            ->when($excludeId, function ($query, $excludeId) {
                return $query->where('id', '!=', $excludeId);
            })
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                    ->orWhereBetween('end_time', [$startTime, $endTime])
                    ->orWhere(function ($q) use ($startTime, $endTime) {
                        $q->where('start_time', '<=', $startTime)
                          ->where('end_time', '>=', $endTime);
                    });
            })
            ->exists();

        if ($sectionConflict) {
            return 'Section has a schedule conflict at the selected time.';
        }

        return null;
    }

    public function getTeacherSubjects($teacherId)
    {
        $subjects = TeacherSubjectSection::where('teacher_id', $teacherId)
            ->with('subject')
            ->get()
            ->pluck('subject')
            ->map(function ($subject) {
                return [
                    'id' => $subject->id,
                    'subject_name' => $subject->subject_name
                ];
            });

        return response()->json($subjects);
    }
}