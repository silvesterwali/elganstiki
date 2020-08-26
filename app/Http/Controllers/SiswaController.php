<?php

namespace App\Http\Controllers;

use App\Charts\NilaiSiswa;
use App\ClassCourse;
use App\ClassExtracurricular;
use App\ClassRoom;
use App\ClassRoomStudentCourse;
use App\ClassStudent;
use App\ListOfAttendees;
use App\Student;
use Auth;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        $id            = Auth::user()->student->id;
        $class_student = ClassStudent::where('student_id', $id)->get('class_room_id');
        $kelas         = ClassRoom::whereIn('id', $class_student)->orderBy('id', 'desc')->get();
        return view('dashboard.kelas.siswa.index', compact('kelas'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *i
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($siswa_kelas_akses)
    {

        $siswa_id = Auth::user()->student->id;
        $kelas    = ClassRoom::where('id', $siswa_kelas_akses)->first();
        $cs       = ClassStudent::where('class_room_id', $kelas->id)->first();
        $nilai    = ClassRoomStudentCourse::where('class_student_id', $cs->id)
            ->orderBy('semester', 'asc')
            ->get();
        $mapel = ClassCourse::where('class_room_id', $kelas->id)->get();
        $absen = ListOfAttendees::where('class_student_id', $cs->id)->orderBy('semester', 'asc')->get();
        $extra = ClassExtracurricular::where('class_student_id', $cs->id)
            ->whereNotNull('kegiatanextrakurikuler')
            ->whereNotNull('keterangan')
            ->get();
        $labels   = [];
        $dataset1 = [];
        $dataset2 = [];
        // <td>{{ $key+1 }}</td>
        // <td>{{ $nilai->class_course->course->teacher->nip}}</td>
        // <td>{{ $nilai->class_course->course->teacher->nama }}</td>
        // <td>{{ $nilai->semester }}</td>
        // <td>{{ $nilai->class_course->course->mapel }}</td>
        // <td>{{ $nilai->kkmpengetahuan }}</td>
        // <td>{{ $nilai->kkmketerampilan }}</td>
        // <td>{{ $nilai->nilaitugas }}</td>
        // <td>{{ $nilai->ulanganharian }}</td>
        // <td>{{ $nilai->uts }}</td>
        // <td>{{ $nilai->uas }}</td>
        // <td>{{ $nilai->keterangan }}</td>

        foreach ($nilai as $nm) {
            if (!in_array($nm->class_course->course->kdmapel, $labels)) {
                array_push($labels, $nm->class_course->course->kdmapel);
            }
            $nilai_rata = 0;

            $kkmpengetahuan  = $nm->kkmpengetahuan !== null ? $nm->kkmpengetahuan : 0;
            $kkmketerampilan = $nm->kkmketerampilan !== null ? $nm->kkmketerampilan : 0;
            $nilaitugas      = $nm->nilaitugas !== null ? $nm->nilaitugas : 0;
            $nilaitugas2     = $nm->nilaitugas_dua !== null ? $nm->nilaitugas_dua : 0;
            $nilaitugas3     = $nm->nilaitugas_tiga !== null ? $nm->nilaitugas_tiga : 0;
            $lats            = ($nilaitugas + $nilaitugas2 + $nilaitugas3) / 3;

            $ulanganharian        = $nm->ulanganharian !== null ? $nm->ulanganharian : 0;
            $ulanganharian_dua    = $nm->ulanganharian_dua !== null ? $nm->ulanganharian_dua : 0;
            $ulanganharian_tiga   = $nm->ulanganharian_tiga !== null ? $nm->ulanganharian_tiga : 0;
            $totalUlanganHarian   = ($ulanganharian + $ulanganharian_dua + $ulanganharian_tiga) / 3;
            $uts                  = $nm->uts !== null ? $nm->uts : 0;
            $uas                  = $nm->uas !== null ? $nm->uas : 0;
            $totalTugasDanUlangan = ($lats + $totalUlanganHarian) / 2;
            $jumlah_nilai         = ($totalTugasDanUlangan + $uts + $uas) / 3;
            $nilai_rata           = 0;

            if ($jumlah_nilai > 0) {
                $lastValue  = ($jumlah_nilai + $kkmketerampilan) / 2;
                $nilai_rata = round($lastValue, 2);
            }

            if ($nm->semester == 1) {
                array_push($dataset1, $nilai_rata);
            } else {
                array_push($dataset2, $nilai_rata);
            }

        }

        $borderColors1 = [
            "rgba(255, 99, 132, 1.0)",
            "rgba(22,160,133, 1.0)",
            "rgba(255, 205, 86, 1.0)",
            "rgba(51,105,232, 1.0)",
            "rgba(244,67,54, 1.0)",
            "rgba(34,198,246, 1.0)",
            "rgba(153, 102, 255, 1.0)",
            "rgba(255, 159, 64, 1.0)",
            "rgba(233,30,99, 1.0)",
            "rgba(205,220,57, 1.0)",
        ];
        $fillColors1 = [
            "rgba(255, 99, 132, 0.2)",
            "rgba(22,160,133, 0.2)",
            "rgba(255, 205, 86, 0.2)",
            "rgba(51,105,232, 0.2)",
            "rgba(244,67,54, 0.2)",
            "rgba(34,198,246, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(255, 159, 64, 0.2)",
            "rgba(233,30,99, 0.2)",
            "rgba(205,220,57, 0.2)",

        ];
        $borderColors2 = [
            "rgba(205,220,57, 1.0)",
            "rgba(255, 99, 132, 1.0)",
            "rgba(22,160,133, 1.0)",
            "rgba(255, 205, 86, 1.0)",
            "rgba(51,105,232, 1.0)",
            "rgba(244,67,54, 1.0)",
            "rgba(34,198,246, 1.0)",
            "rgba(153, 102, 255, 1.0)",
            "rgba(255, 159, 64, 1.0)",
            "rgba(233,30,99, 1.0)",

        ];
        $fillColors2 = [
            "rgba(205,220,57, 0.2)",
            "rgba(255, 99, 132, 0.2)",
            "rgba(22,160,133, 0.2)",
            "rgba(255, 205, 86, 0.2)",
            "rgba(51,105,232, 0.2)",
            "rgba(244,67,54, 0.2)",
            "rgba(34,198,246, 0.2)",
            "rgba(153, 102, 255, 0.2)",
            "rgba(255, 159, 64, 0.2)",
            "rgba(233,30,99, 0.2)",

        ];
        $nilaiSiswaCharts = new NilaiSiswa;
        $nilaiSiswaCharts->labels($labels);
        $nilaiSiswaCharts->dataset('Nilai rata rata semester 1', 'bar', $dataset1)
            ->color($borderColors1[0])
            ->backgroundcolor($fillColors1[0]);
        $nilaiSiswaCharts->dataset('Nilai rata rata semester 2', 'bar', $dataset2)
            ->color($borderColors2[0])
            ->backgroundcolor($fillColors2[0]);
        return view('dashboard.kelas.siswa.show', compact('kelas', 'nilai', 'mapel', 'absen', 'extra', 'nilaiSiswaCharts'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
