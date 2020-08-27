<?php

namespace App\Http\Controllers;

use App\ClassCourse;
use App\ClassExtracurricular;
use App\ClassRoom;
use App\ClassRoomStudentCourse;
use App\ClassStudent;
use App\ListOfAttendees;
use App\Student;
use Illuminate\Http\Request;
use PDF2;

class LaporanController extends Controller
{
    public function index(ClassRoom $KelasSiswa)
    {
        $grade = $KelasSiswa->grade;
        $idk   = $KelasSiswa->id;
        // memilih siswa/siswi yang telah terdapat dalam kelas
        // dengan grade yang sama dengan kelas yang akan ditambahkan siswa
        $siswa = ClassStudent::where('class_room_id', $idk)
            ->orderBy('student_id', 'asc')
            ->get();
        $course = ClassCourse::where('class_room_id', $idk)->get();
        $kelas  = $KelasSiswa;

        return view('dashboard.kelas.laporan.admin', compact('kelas', 'siswa', 'course'));
    }

    public function adminReport(Request $request)
    {

        $request->validate(
            [
                "semester"      => "required",
                "class_student" => "required",
            ]
        );

        $classStudent = ClassStudent::find($request->class_student);

        $idKelas  = $classStudent->class_room_id;
        $semester = $request->semester;
        $siswa_id = $classStudent->student_id;

        $student = Student::find($siswa_id);
        // dd($student);

        $kelas = ClassRoom::where('id', $idKelas)->first();
        $cs    = ClassStudent::where('class_room_id', $idKelas)->first();
        $nilai = ClassRoomStudentCourse::where('class_student_id', $cs->id)
            ->where('semester', $semester)
            ->orderBy('class_course_id', 'asc')
            ->get();

// $mapel = ClassCourse::where('class_room_id', $kelas->id)->get();
        $absen = ListOfAttendees::where('class_student_id', $cs->id)
            ->where('semester', $semester)
            ->first();

        $extra = ClassExtracurricular::where('class_student_id', $cs->id)
            ->whereNotNull('kegiatanextrakurikuler')
            ->whereNotNull('keterangan')
            ->get();

        $obNilai   = '';
        $namasiswa = $student->nama;
        $nis       = $student->nisn;
//perulangan nilai dan mata pelajaran
        PDF2::SetCreator(PDF_CREATOR);
        PDF2::SetAuthor(PDF_AUTHOR);
        PDF2::SetTitle('Rapor Siswa');
        PDF2::SetSubject('TCPDF Tutorial');
        PDF2::SetKeywords('SabanaCode');

// set default header data
        PDF2::SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
        PDF2::setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        PDF2::setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
        PDF2::SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
        // PDF2::SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
        // PDF2::SetHeaderMargin(PDF_MARGIN_HEADER);
        // PDF2::SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
        PDF2::SetAutoPageBreak(true, PDF_MARGIN_BOTTOM);

// set image scale factor
        PDF2::setImageScale(PDF_IMAGE_SCALE_RATIO);
//-------------------------------------------------------
        // set font

// tampung data siswa/i
        $IdentitasSiswa = Student::find($siswa_id);

        PDF2::AddPage();
        PDF2::SetFont('times', 'B', 12);
        PDF2::Cell(0, 2, 'RAPOR', 0, 1, 'C');
        PDF2::Cell(0, 2, 'SEKOLAH MENENGAH PERTAMA', 0, 1, 'C');
        PDF2::Cell(0, 2, '(SMP)', 0, 1, 'C');

        PDF2::Image('assets/img/logo.png', '55', '65', 40, 40, '', '', 'T', false, 300, '', false, false, 0, false, false, false);
        PDF2::Ln(60);
        PDF2::SetFont('times', 'B', 9);

        PDF2::Cell(0, 5, 'NAMA PESERTA DIDIK', 0, 1, 'C');
        PDF2::SetFont('times', 'B', 11);

        PDF2::Cell(0, 2, $IdentitasSiswa->nama, 0, 1, 'C');
        PDF2::Ln(5);
        PDF2::SetFont('times', 'B', 9);

        PDF2::Cell(0, 5, 'NIS', 0, 1, 'C');
        PDF2::SetFont('times', 'B', 11);

        PDF2::Cell(0, 2, $IdentitasSiswa->nisn, 0, 1, 'C');
        PDF2::Ln(30);
        PDF2::SetFont('times', 'B', 9);
        PDF2::Cell(0, 5, 'KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN', 0, 1, 'C');

// Start First Page Group
        PDF2::startPageGroup();

// add a page

// add second page

        PDF2::AddPage();
        PDF2::SetFont('times', 'B', 11);
        PDF2::Cell(0, 2, 'RAPOR', 0, 1, 'C');
        PDF2::SetFont('times', 'B', 10);
        PDF2::Cell(0, 2, 'SEKOLAH MENENGAH PERTAMAN', 0, 1, 'C');
        PDF2::Cell(0, 3, '(SMP)', 0, 1, 'C');
        PDF2::Ln(5); //  enter 5 baris
        // create columns content
        // create columns content
        PDF2::SetFont('times', '', 10);

        $tbl = '
        <table cellspacing="0" cellpadding="5" border="0.2">
            <tr>
                <th>Nama Sekolah</th>
                <td>SMP SANTO YOSEPH DENPASAR</td>
            </tr>
            <tr>
                <th>NIPSIN</th>
                <td>50103175</td>
            </tr>
            <tr>
                <th>NIPSIN/N55/NDS</th>
                <td>304220400010</td>
            </tr>
            <tr>
                <th>Alamat Sekolah</th>
                <td>Jln.P.B Sudirman - Dauh puri klod</td>
            </tr>
            <tr>
                <th>Desa/Kelurahan</th>
                <td>Panjer</td>
            </tr>
            <tr>
                <th>Kecamatan</th>
                <td>Denpasar Barat</td>
            </tr>
            <tr>
                <th>Kota/Kabupaten</th>
                <td>Denpasar</td>
            </tr>
            <tr>
                <th>Provinsi</th>
                <td>Bali</td>
            </tr>
            <tr>
                <th>Web</th>
                <td></td>
            </tr>
            <tr>
                <th>Email</th>
                <td></td>
            </tr>
        </table>
        ';

        PDF2::writeHTML($tbl, true, false, false, false, '');

        PDF2::lastPage();
//
        PDF2::startPageGroup();
// Start Second Page Group

        PDF2::AddPage();
        PDF2::SetFont('times', 'B', 10);
        PDF2::Cell(0, 2, 'IDENTITAS PESERTA DIDIK', 0, 1, 'C');
        PDF2::Ln(5); //  enter 5 baris
        // create columns content
        // create columns content
        PDF2::SetFont('times', '', 10);
        $jk           = $IdentitasSiswa->jk == 1 ? "Laki-Laki" : "Perempuan";
        $tblIdentitas = '
        <table cellspacing="0" cellpadding="2" border="0.2">
            <tr>
                <td width="5%">1</td>
                <th width="40%" >Nama Lengkap Peserta Didik</th>
                <td width="55%">' . $IdentitasSiswa->nama . '</td>
            </tr>
            <tr>
                <td width="5%">2</td>
                <th width="40%" >Nomor Induk/NISN</th>
                <td width="55%">' . $IdentitasSiswa->nisn . '</td>
            </tr>
            <tr>
                <td width="5%">3</td>
                <th width="40%" >Tempat,Tanggal Lahir</th>
                <td width="55%">' . $IdentitasSiswa->tempatlahir . ',' . date('d-m-Y', strtotime($IdentitasSiswa->tanggallahir)) . '</td>
            </tr>
            <tr>
                <td width="5%">4</td>
                <th width="40%" >Jenis Kelamin</th>
                <td width="55%">' . $jk . '</td>
            </tr>
            <tr>
                <td width="5%">5</td>
                <th width="40%" >Agama</th>
                <td width="55%">' . $IdentitasSiswa->agama . '</td>
            </tr>
            <tr>
                <td width="5%">6</td>
                <th width="40%" >Status Dalam Keluarga</th>
                <td width="55%"></td>
            </tr>
            <tr>
                <td width="5%">7</td>
                <th width="40%" >Anak ke</th>
                <td width="55%"></td>
            </tr>
            <tr>
                <td width="5%">8</td>
                <th width="40%" >ALamat Peserta Didik</th>
                <td width="55%">' . $IdentitasSiswa->alamatorangtuawali . '</td>
            </tr>
            <tr>
                <td width="5%">9</td>
                <th width="40%" >Nomor Telephone Rumah</th>
                <td width="55%"></td>
            </tr>
            <tr>
                <td width="5%">10</td>
                <th width="40%" >Sekolah Asah (SMP/MTs)</th>
                <td width="55%"></td>
            </tr>
            <tr>
                <td width="5%">11</td>
                <th width="40%" >Orang Tua</th>
                <td width="55%"></td>
            </tr>
            <tr>
                <td width="5%"></td>
                <th width="40%" >a.Nama Ayah</th>
                <td width="55%">' . $IdentitasSiswa->namaayah . '</td>
            </tr>
            <tr>
                <td width="5%"></td>
                <th width="40%" >b.Nama Ibu</th>
                <td width="55%">' . $IdentitasSiswa->namaibu . '</td>
            </tr>
            <tr>
                <td width="5%"></td>
                <th width="40%" >c.Alamat</th>
                <td width="55%">' . $IdentitasSiswa->alamatorangtuawali . '</td>
            </tr>
            <tr>
                <td width="5%"></td>
                <th width="40%" >d.Nomor Telephone HP</th>
                <td width="55%"></td>
            </tr>
            <tr>
                <td width="5%">12</td>
                <th width="40%" >Pekerjaan Orang Tua</th>
                <td width="55%"></td>
            </tr>
            <tr>
                <td width="5%"></td>
                <th width="40%" >a. Ayah</th>
                <td width="55%"></td>
            </tr>
            <tr>
                <td width="5%"></td>
                <th width="40%" >b.Ibu</th>
                <td width="55%"></td>
            </tr>
            <tr>
                <td width="5%">13</td>
                <th width="40%" >Wali Peserta Didik</th>
                <td width="55%"></td>
            </tr>
            <tr>
                <td width="5%"></td>
                <th width="40%" >a.Nama Wali</th>
                <td width="55%">' . $IdentitasSiswa->namawali . '</td>
            </tr>
            <tr>
                <td width="5%"></td>
                <th width="40%" >b.Nomor Telp Wali</th>
                <td width="55%"></td>
            </tr>
            <tr>
                <td width="5%"></td>
                <th width="40%" >c.Alamat</th>
                <td width="55%"></td>
            </tr>
            <tr>
                <td width="5%"></td>
                <th width="40%" >d.Pekerjaan</th>
                <td width="55%"></td>
            </tr>
        </table>';

        PDF2::writeHTML($tblIdentitas, true, false, false, false, '');
        PDF2::SetFont('times', '', 8);
        PDF2::Cell(0, 1, 'Denpasar,' . date('d F Y'), 0, 1, 'R');
        PDF2::Cell(0, 1, 'Kepala Sekolah', 0, 1, 'R');

        PDF2::Cell(0, 30, '(____________)', 0, 1, 'R');

        PDF2::lastPage();

// Start Second Page Group
        PDF2::startPageGroup();

// add some pages
        PDF2::AddPage();

        $tableHeader = '
            <table cellspacing="0" cellpadding="1" border="0">
                <tr>
                    <td>NIS/NISN</td>
                    <td>' . $IdentitasSiswa->nisn . '</td>
                    <td>Kelas</td>
                    <td>' . $kelas->kdkelas . '/' . $kelas->namakelas . '</td>
                </tr>
                <tr>
                    <td>Nama Lengkap</td>
                    <td>' . $IdentitasSiswa->nama . ' </td>
                    <td>Ta/Semester</td>
                    <td>' . $kelas->school_year->tahunajaran . '/' . $semester . '</td>
                </tr>
                <tr>
                    <td>Wali Kelas /Nip</td>
                    <td>' . $kelas->teacher->nama . '/' . $kelas->teacher->nip . '</td>
                    <td></td>
                    <td></td>
                </tr>
            </table>
            ';

        PDF2::writeHTML($tableHeader, true, false, false, false, '');
        PDF2::Ln(2);
        PDF2::Cell(0, 2, 'Nilai siswa/i', 0, 1, 'L');

        $tblNilai = '
            <table cellspacing="0" cellpadding="1" border="0.2">
                    <tr>
                        <td style="text-align:justify" align="center" rowspan="2"  width="5%" >No</td>
                        <td  style="text-align:justify"  align="center" rowspan="2"  width="30%" >Mata Pelajaran</td>
                        <td  width="15%"  rowspan="2"  align="center">KKM</td>
                        <td  width="25%" colspan="2" align="center">Pengetahuan</td>
                        <td  width="25%" colspan="2" align="center">Keterampilan</td>
                    </tr>
                    <tr>
                        <td  align="center">Nilai</td>
                        <td  align="center">Predikat</td>
                        <td  align="center">Nilai</td>
                        <td  align="center">Predikat</td>
                    </tr>

                    ';
        $jumlahMapel    = count($nilai);
        $jumlahRataRata = 0;
        foreach ($nilai as $key => $nilai_mapel) {
            $nomor           = $key + 1;
            $nama_mapel      = $nilai_mapel->class_course->course->mapel;
            $kkmpengetahuan  = $nilai_mapel->kkmpengetahuan !== null ? $nilai_mapel->kkmpengetahuan : 0;
            $kkmketerampilan = $nilai_mapel->kkmketerampilan !== null ? $nilai_mapel->kkmketerampilan : 0;
            $nilaitugas      = $nilai_mapel->nilaitugas !== null ? $nilai_mapel->nilaitugas : 0;
            $nilaitugas_dua  = $nilai_mapel->nilaitugas_dua !== null ? $nilai_mapel->nilaitugas_dua : 0;
            $nilaitugas_tiga = $nilai_mapel->nilaitugas_tiga !== null ? $nilai_mapel->nilaitugas_tiga : 0;

            $totalTugas = ($nilaitugas + $nilaitugas_dua + $nilaitugas_tiga) / 3;

            $nilaiulanganharian      = $nilai_mapel->ulanganharian !== null ? $nilai_mapel->ulanganharian : 0;
            $nilaiulanganharian_dua  = $nilai_mapel->ulanganharian_dua !== null ? $nilai_mapel->ulanganharian_dua : 0;
            $nilaiulanganharian_tiga = $nilai_mapel->ulanganharian_tiga !== null ? $nilai_mapel->ulanganharian_tiga : 0;

            $totalUlaranganHarian = ($nilaiulanganharian + $nilaiulanganharian_dua + $nilaiulanganharian_tiga) / 3;

            $nilaiuts             = $nilai_mapel->uts !== null ? $nilai_mapel->uts : 0;
            $nilaiuas             = $nilai_mapel->uas !== null ? $nilai_mapel->uas : 0;
            $totalTugasDanUlangan = ($totalTugas + $totalUlaranganHarian) / 2;
            $rata_rata            = ($totalTugasDanUlangan + $nilaiuts + $nilaiuas) / 3;
            $pembulatan           = round($rata_rata, 2);
            $statusCode           = 'E';
            if ($pembulatan < 50) {
                $statusCode = 'E';

            } else if ($pembulatan >= 50 && $pembulatan < 60) {
                $statusCode = 'D';
            } else if ($pembulatan >= 60 && $pembulatan < 70) {
                $statusCode = 'C';
            } else if ($pembulatan >= 70 && $pembulatan < 80) {
                $statusCode = 'B';
            } else if ($pembulatan >= 80 && $pembulatan < 90) {
                $statusCode = 'A';
            } else {
                $statusCode = 'A+';
            }

            $statusKeterampilan = 'E';
            if ($kkmketerampilan < 50) {
                $statusKeterampilan = 'E';
            } else if ($kkmketerampilan >= 50 && $kkmketerampilan < 60) {
                $statusKeterampilan = 'D';
            } else if ($kkmketerampilan >= 60 && $kkmketerampilan < 70) {
                $statusKeterampilan = 'C';
            } else if ($kkmketerampilan >= 70 && $kkmketerampilan < 80) {
                $statusKeterampilan = 'B';
            } else if ($kkmketerampilan >= 80 && $kkmketerampilan < 90) {
                $statusKeterampilan = 'A';
            } else {
                $statusKeterampilan = 'A+';

            }

            $jumlahRataRata += ($pembulatan + $kkmketerampilan) / 2;
            $tblNilai .= '<tr>
                            <td  align="center" width="5% ">' . $nomor . '</td>
                            <td  width="30%">' . $nama_mapel . '</td>
                            <td  align="center"  width="15%" >' . $kkmpengetahuan . '</td>
                            <td  align="center"   >' . $pembulatan . '</td>
                            <td  align="center"   >' . $statusCode . '</td>
                            <td  align="center"  >' . $kkmketerampilan . '</td>
                            <td  align="center"  >' . $statusKeterampilan . '</td>
                            </tr>';
        }
        $jumlahRataRataPermapel = $jumlahRataRata / $jumlahMapel;
        $nilaiKelas             = round($jumlahRataRataPermapel);
        $tblNilai .= '<tr>
                        <td  colspan="6" align="right"  >Rata-Rata Seluruh</td>
                        <td  align="center" >' . $nilaiKelas . '</td>
                    </tr></table>';

        PDF2::writeHTML($tblNilai, true, false, false, false, '');
        PDF2::Ln(1);
        PDF2::Cell(0, 2, 'Absensi Semester', 0, 1, 'L');

        $tableAbsensi = '
        <table cellspacing="0" cellpadding="1" border="0.2">
            <tr>
                <td align="center">Hadir</td>
                <td align="center">Apha</td>
                <td  align="center">sakit</td>
                <td  align="center">Ijin</td>
                <td >Keterangan</td>
            </tr>
            <tr>
                <td  align="center">' . $absen->hadir . '</td>
                <td  align="center">' . $absen->alpha . '</td>
                <td  align="center">' . $absen->ijin . '</td>
                <td  align="center">' . $absen->keterangan . '</td>
            </tr>
        </table>';

// untuk table extrakurikuler

        PDF2::writeHTML($tableAbsensi, true, false, false, false, '');

        PDF2::Ln(1);
        PDF2::Cell(0, 2, 'Kegiatan Extrakurikuler', 0, 1, 'L');
        $tableExtra = '
        <table cellspacing="0" cellpadding="1" border="0.2">
            <tr>
                <td  align="center" width="5%">No</td>
                <td  align="center" width="45%">Nama kegiatan</td>
                <td align="center" width="50%">Keterangan</td>
            </tr>';
        foreach ($extra as $key => $ex) {
            $n = $key + 1;
            $tableExtra .= '
            <tr>
                <td  align="center" width="5%">' . $n . '</td>
                <td  align="center">' . $ex->kegiatanextrakurikuler . '</td>
                <td>' . $ex->keterangan . '</td>
            </tr>
            ';
        }

        $tableExtra .= '</table>';

        PDF2::writeHTML($tableExtra, true, false, false, false, '');
        PDF2::SetFont('times', '', 8);

        $tB = '<table cellspacing="0" cellpadding="1" border="0">
            <tr>
                <td align="center">___________,  ,  , ' . date('Y') . '</td>
                 <td></td>
                <td align="center">___________,  ,  , ' . date('Y') . '</td>
            </tr>
            <tr>
                <td align="center">Orang Tua/Wali</td>
                <td></td>
                <td align="center">Wali Kelas</td>
            </tr>

             <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>

             <tr>
                <td></td>
                <td></td>
                <td></td>
            </tr>
             <tr>
                <td align="center">_____________</td>
                <td></td>
                <td align="center">______________</td>
            </tr>
            </table>';

        PDF2::writeHTML($tB, true, false, false, false, '');

        PDF2::lastPage();
        PDF2::Output('printMandiriRaport.pdf');

    }

    public function getNilaiSiswaBerdasarakanMapel($idKelasSiswa, $idkelasCourse, $semester)
    {
        // ClassRoomStudentCourse::with(['class_student' => function ($query) use ($kelas) {
        //     $query->where('class_room_id', $kelas);
        // }])->get();
        $nilai = ClassRoomStudentCourse::where('class_student_id', $idKelasSiswa)
            ->where('class_course_id', $idkelasCourse)
            ->where('semester', $semester)
            ->first();
        return $nilai;
    }
}
