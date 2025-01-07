<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Employee\CreateEmployeeRequest;
use App\Http\Requests\Employee\UpdateEmployeeRequest;
use App\Http\Resources\Employee\EmployeeCollectionResource;
use App\Http\Resources\Employee\EmployeeResource;
use App\Models\Employee;
use App\Models\Profile;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page', 1);
        $sizeCol = $request->input('size', 6);
        $unitKerja = $request->input('unit_kerja', null);
        $query = Employee::with('work_unit')->orderBy('created_at', 'desc');

        if ($unitKerja) {
            $query->whereHas('work_unit', function ($q) use ($unitKerja) {
                $q->where('name', 'like', '%' . $unitKerja . '%');
            });
        }
        $employees = $query->paginate($sizeCol, ['*'], 'page', $page);
        return (new EmployeeCollectionResource($employees))->response()->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEmployeeRequest $request)
    {
        try {
            $data = $request->validated();
            $filePath = $request->file('photo')->store('employee');
            $imageUrl = url('storage/employee/' . basename($filePath));
            $data['photo'] = $imageUrl;
            $employee = Employee::create($data);
            $data['employee_nip'] = $employee->nip;
            Profile::create($data);
            return (new EmployeeResource($employee))->response()->setStatusCode(201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function update(UpdateEmployeeRequest $request, string $nip)
    {
        try {
            $employee = Employee::find($nip);
            if (!$employee) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Employee dengan ID ' . $nip . ' tidak ditemukan',
                ]);
            }
            $data = $request->validated();
            if ($request->hasFile('photo')) {
                if ($employee->photo) {
                    $oldFilePath = str_replace(url('storage') . '/', 'public/', $employee->photo);
                    Storage::delete($oldFilePath);
                }
                $filePath = $request->file('photo')->store('public/employee');
                $employee->photo = url(Storage::url($filePath));
            }
            $employee->update($data);
            Profile::updateOrCreate(
                ['employee_nip' => $employee->nip],
                $data
            );

            return (new EmployeeResource($employee))->response()->setStatusCode(200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $nip)
    {
        $employeeId = Employee::with('work_unit')->find($nip);
        if (!$employeeId) {
            return response()->json([
                'message' => 'Book dengan ID '. $nip.'tidak ditemukan',
            ], 404);
        }
        return (new EmployeeResource($employeeId))->response()->setStatusCode(201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $nip)
    {
        $employee = Employee::find($nip);
        if (!$employee) {
            return response()->json([
                'message' => 'Employee dengan ID ' . $nip . ' tidak ditemukan',
            ], 404);
        }
        if ($employee->photo) {
            $filePath = str_replace(url('storage'), 'public', $employee->photo);
            Storage::delete($filePath);
        }
        $employee->delete();
        return response()->json([
            'message' => 'Employee berhasil dihapus',
        ], 200);
    }

    public function generatePdf()
    {
        $employees = Employee::with(['profile', 'work_unit'])->orderBy('created_at', 'desc')->get();
        $html = view('pdf.employee', ['employees' => $employees])->render();
        $pdf = new Dompdf();
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', false);
        $pdf->setOptions($options);

        $pdf->loadHtml($html);
        $pdf->setPaper('A4', 'landscape');
        $pdf->render();

        return $pdf->stream('employee_report.pdf', ['Attachment' => 0]);
    }



    public function search(Request $request)
    {
        $page = $request->input('page', 1);
        $size = $request->input('size', 6);
        $searchQuery = $request->input('query');
        $employees = Employee::query();
        if ($searchQuery) {
            $employees = $employees->where(function (Builder $builder) use ($searchQuery) {
                $builder->where('nip', 'like', '%' . $searchQuery . '%')
                        ->orWhere('work_unit_id', 'like', '%' . $searchQuery . '%')
                        ->orWhere('name', 'like', '%' . $searchQuery . '%')
                        ->orWhere('birt_place', 'like', '%' . $searchQuery . '%')
                        ->orWhere('address', 'like', '%' . $searchQuery . '%')
                        ->orWhere('birt_date', 'like', '%' . $searchQuery . '%')
                        ->orWhere('gender', 'like', '%' . $searchQuery . '%')
                        ->orWhere('rank', 'like', '%' . $searchQuery . '%')
                        ->orWhere('eselon', 'like', '%' . $searchQuery . '%')
                        ->orWhere('position', 'like', '%' . $searchQuery . '%')
                        ->orWhere('duty_place', 'like', '%' . $searchQuery . '%')
                        ->orWhere('religion', 'like', '%' . $searchQuery . '%')
                        ->orWhere('phone_number', 'like', '%' . $searchQuery . '%')
                        ->orWhere('npwp', 'like', '%' . $searchQuery . '%');
            });
        }

        // Pagination
        $employees = $employees->paginate($size, ['*'], 'page', $page);

        return (new EmployeeCollectionResource($employees))->response()->setStatusCode(200);
    }
}
