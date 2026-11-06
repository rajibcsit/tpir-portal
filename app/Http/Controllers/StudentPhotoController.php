<?php
namespace App\Http\Controllers;
use App\Models\Student;
use Illuminate\Support\Facades\Storage;
class StudentPhotoController extends Controller
{
    public function show(Student $student){
        $portalUser = request()->session()->get('portal_user', []);
        if (($portalUser['role'] ?? null) === 'student' && (int)($portalUser['student_id'] ?? 0) !== (int)$student->id) abort(403);
        abort_unless($student->profile_photo && Storage::disk('public')->exists($student->profile_photo),404);
        return response()->file(Storage::disk('public')->path($student->profile_photo),['Cache-Control'=>'private, max-age=3600']);
    }
}
