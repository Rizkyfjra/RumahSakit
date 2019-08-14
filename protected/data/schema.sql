-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: 13 Agu 2019 pada 17.52
-- Versi Server: 10.1.13-MariaDB
-- PHP Version: 7.0.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `schema`
--

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi`
--

CREATE TABLE `absensi` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `id_lesson` int(11) DEFAULT NULL,
  `absen` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `created_at` datetime DEFAULT NULL,
  `pertemuan_ke` int(11) DEFAULT NULL,
  `ket` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `created_date` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  `sync_status` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi_harian`
--

CREATE TABLE `absensi_harian` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `absen` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `tgl` date DEFAULT NULL,
  `ket` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `created_date` datetime DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `absensi_solution_x_onehundred_c`
--

CREATE TABLE `absensi_solution_x_onehundred_c` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `datetime` datetime NOT NULL,
  `ver` int(11) DEFAULT NULL,
  `stat` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `activities`
--

CREATE TABLE `activities` (
  `id` int(11) NOT NULL,
  `activity_type` varchar(255) CHARACTER SET latin1 NOT NULL,
  `content` text CHARACTER SET latin1,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `sync_status` int(11) DEFAULT NULL,
  `stats_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `actual_table_name`
--

CREATE TABLE `actual_table_name` (
  `id` char(32) CHARACTER SET latin1 NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` longblob,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `announcements`
--

CREATE TABLE `announcements` (
  `author_id` int(11) NOT NULL,
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET latin1 NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `content` text CHARACTER SET latin1,
  `type` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `assignment`
--

CREATE TABLE `assignment` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `due_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `lesson_id` int(11) NOT NULL,
  `file` text,
  `assignment_type` int(11) DEFAULT NULL,
  `add_to_summary` int(11) DEFAULT NULL,
  `recipient` int(11) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `trash` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `berita_acara`
--

CREATE TABLE `berita_acara` (
  `id` int(11) NOT NULL,
  `edited` text NOT NULL,
  `reset` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `berita_acara`
--

INSERT INTO `berita_acara` (`id`, `edited`, `reset`) VALUES
(1, '<h4 style="text-align:center"><strong><span style="font-family:Times New Roman,Times,serif"><span style="font-size:16px"><u>BERITA ACARA</u></span></span></strong></h4>\r\n\r\n<p><span style="font-family:Times New Roman,Times,serif"><span style="font-size:16px">Pada hari ini, _______________tanggal _______________ bulan _______________ tahun _______ , telah diselenggarakan Penilaian Tengah Semester Ganjil Tahun Ajaran 2017-2018.<br />\r\nPada pukul ______________ sampai dengan pukul ___________ di Ruang _________<br />\r\nMata Pelajaran __________________________________ bagi kelas _______________<br />\r\nJumlah peserta :<br />\r\nSeharusnya : ________ Orang, yaitu nomor _____________ s.d. ______________<br />\r\nYang hadir : ________ Orang<br />\r\nYang tidak hadir : ________ Orang, yaitu nomor ( tulis nama dan nomornya )<br />\r\n&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;<br />\r\n&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip; Berita Acara ini dibuat dengan sesungguhnya.</span></span></p>\r\n\r\n<p style="text-align:right"><span style="font-family:Times New Roman,Times,serif"><span style="font-size:16px"><strong>Bandung, &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;2017.</strong></span></span></p>\r\n\r\n<table style="width:100%">\r\n	<tbody>\r\n		<tr>\r\n			<td colspan="2" style="text-align:center"><span style="font-family:Times New Roman,Times,serif"><span style="font-size:16px"><strong>Pembuat Berita Acara</strong></span></span></td>\r\n		</tr>\r\n		<tr>\r\n			<td>\r\n			<p><span style="font-family:Times New Roman,Times,serif"><span style="font-size:16px"><strong>Pengawas I</strong><br />\r\n			Tanda tangan : _______________________<br />\r\n			Nama &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : _______________________<br />\r\n			NIP. &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : _______________________</span></span></p>\r\n			</td>\r\n			<td>\r\n			<p><span style="font-family:Times New Roman,Times,serif"><span style="font-size:16px"><strong>Pengawas II</strong><br />\r\n			Tanda tangan : _______________________<br />\r\n			Nama &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : _______________________<br />\r\n			NIP. &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : _______________________</span></span></p>\r\n			</td>\r\n		</tr>\r\n		<tr>\r\n			<td>&nbsp;</td>\r\n			<td>&nbsp;</td>\r\n		</tr>\r\n	</tbody>\r\n</table>\r\n', '<h4 style="text-align:center"><strong><span style="font-family:Times New Roman,Times,serif"><span style="font-size:16px"><u>BERITA ACARA</u></span></span></strong></h4>\n\n<p><span style="font-family:Times New Roman,Times,serif"><span style="font-size:16px">Pada hari ini, _______________tanggal _______________ bulan _______________ tahun _______ , telah diselenggarakan Ujian Akhir Sekolah 2016/2017.<br />\nPada pukul ______________ sampai dengan pukul ___________ di Ruang _________<br />\nMata Pelajaran __________________________________ bagi kelas _______________<br />\nJumlah peserta :<br />\nSeharusnya : ________ Orang, yaitu nomor _____________ s.d. ______________<br />\nYang hadir : ________ Orang<br />\nYang tidak hadir : ________ Orang, yaitu nomor ( tulis nama dan nomornya )<br />\n&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;<br />\n&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip;&hellip; Berita Acara ini dibuat dengan sesungguhnya.</span></span></p>\n\n<p style="text-align:right"><span style="font-family:Times New Roman,Times,serif"><span style="font-size:16px"><strong>Bandung, &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;2017.</strong></span></span></p>\n\n<table style="width:100%">\n	<tbody>\n		<tr>\n			<td colspan="2" style="text-align:center"><span style="font-family:Times New Roman,Times,serif"><span style="font-size:16px"><strong>Pembuat Berita Acara</strong></span></span></td>\n		</tr>\n		<tr>\n			<td>\n			<p><span style="font-family:Times New Roman,Times,serif"><span style="font-size:16px"><strong>Pengawas I</strong><br />\n			Tanda tangan : _______________________<br />\n			Nama &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : _______________________<br />\n			NIP. &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : _______________________</span></span></p>\n			</td>\n			<td>\n			<p><span style="font-family:Times New Roman,Times,serif"><span style="font-size:16px"><strong>Pengawas II</strong><br />\n			Tanda tangan : _______________________<br />\n			Nama &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : _______________________<br />\n			NIP. &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; : _______________________</span></span></p>\n			</td>\n		</tr>\n		<tr>\n			<td>&nbsp;</td>\n			<td>&nbsp;</td>\n		</tr>\n	</tbody>\n</table>\n');

-- --------------------------------------------------------

--
-- Struktur dari tabel `chapters`
--

CREATE TABLE `chapters` (
  `id` int(11) NOT NULL,
  `id_lesson` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET latin1 NOT NULL,
  `description` text CHARACTER SET latin1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `content` text CHARACTER SET latin1,
  `chapter_type` int(11) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `trash` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `chapter_files`
--

CREATE TABLE `chapter_files` (
  `id` int(11) NOT NULL,
  `id_chapter` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `file` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `type` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `content` text,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `class`
--

CREATE TABLE `class` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `trash` int(11) DEFAULT NULL,
  `kelompok` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `class`
--

INSERT INTO `class` (`id`, `name`, `teacher_id`, `trash`, `kelompok`, `sync_status`) VALUES
(1, '1', NULL, NULL, NULL, 1),
(2, '2', NULL, NULL, NULL, 1),
(3, '3', NULL, NULL, NULL, 1),
(4, '4', NULL, NULL, NULL, 1),
(5, '5', NULL, NULL, NULL, 1),
(6, '6', NULL, NULL, NULL, 1),
(7, '7', NULL, NULL, NULL, 1),
(8, '8', NULL, NULL, NULL, 1),
(9, '9', NULL, NULL, NULL, 1),
(10, '10', NULL, NULL, NULL, 1),
(11, '11', NULL, NULL, NULL, 1),
(12, '12', NULL, NULL, NULL, 1);

-- --------------------------------------------------------

--
-- Struktur dari tabel `class_detail`
--

CREATE TABLE `class_detail` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `urutan` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `class_detail`
--

INSERT INTO `class_detail` (`id`, `name`, `class_id`, `urutan`, `teacher_id`, `sync_status`) VALUES
(1, 'ICU', 1, NULL, NULL, NULL),
(2, 'UGD', 1, NULL, NULL, NULL),
(3, 'Poli Gigi', 1, NULL, NULL, NULL),
(4, 'Poli Jantung', 1, NULL, NULL, NULL),
(5, 'Poli Anak', 1, NULL, NULL, NULL),
(6, 'Poli Mata', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `class_history`
--

CREATE TABLE `class_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `semester` varchar(255) CHARACTER SET latin1 NOT NULL,
  `year` varchar(255) CHARACTER SET latin1 NOT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `exam`
--

CREATE TABLE `exam` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `semester` int(11) NOT NULL,
  `year` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `trash` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `exam_room`
--

CREATE TABLE `exam_room` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `no_room` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `exam_schedule`
--

CREATE TABLE `exam_schedule` (
  `id` int(11) NOT NULL,
  `exam_id` int(11) NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `exam_schedule_class`
--

CREATE TABLE `exam_schedule_class` (
  `id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `class_id` varchar(255) NOT NULL,
  `lesson_id` varchar(255) NOT NULL,
  `lesson_time` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `exam_student_list`
--

CREATE TABLE `exam_student_list` (
  `id` int(11) NOT NULL,
  `room_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `final_mark`
--

CREATE TABLE `final_mark` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `tipe` varchar(255) CHARACTER SET latin1 NOT NULL,
  `semester` int(11) NOT NULL,
  `tahun_ajaran` varchar(255) CHARACTER SET latin1 NOT NULL,
  `nilai` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `nilai_desc` text CHARACTER SET latin1,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `lesson`
--

CREATE TABLE `lesson` (
  `id` int(11) NOT NULL,
  `name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `user_id` int(11) NOT NULL,
  `class_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `kelompok` int(11) DEFAULT NULL,
  `trash` int(11) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `list_id` int(11) DEFAULT NULL,
  `moving_class` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `lesson_kd`
--

CREATE TABLE `lesson_kd` (
  `id` int(11) NOT NULL,
  `title` varchar(50) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) NOT NULL,
  `trash` int(11) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `tahun_ajaran` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `lesson_list`
--

CREATE TABLE `lesson_list` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `group` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `lesson_list`
--

INSERT INTO `lesson_list` (`id`, `name`, `group`, `sync_status`) VALUES
(1, 'Pendidikan Agama dan Budi Pekerti', 1, 1),
(2, 'Pendidikan Pancasila dan Kewarganegaraan', 1, 1),
(3, 'Bahasa Indonesia', 1, 1),
(4, 'Matematika', 1, 1),
(5, 'Ilmu Pengetahuan Alam', 1, 1),
(6, 'Ilmu Pengetahuan Sosial', 1, 1),
(7, 'Sejarah Indonesia', 1, 1),
(8, 'Bahasa Inggris', 1, 1),
(9, 'Seni Budaya', 2, 1),
(10, 'Pendidikan Jasmani, Olah Raga, dan Kesehatan', 2, 1),
(11, 'Prakarya dan Kewirausahaan', 2, 1),
(12, 'Peminatan Matematika', 3, 1),
(13, 'Biologi', 3, 1),
(14, 'Fisika', 3, 1),
(15, 'Kimia', 3, 1),
(16, 'Geografi', 3, 1),
(17, 'Sejarah', 3, 1),
(18, 'Sosiologi', 3, 1),
(19, 'Ekonomi', 3, 1),
(20, 'Antropologi', 3, 1),
(21, 'Bahasa dan Sastra Indonesia', 3, 1),
(22, 'Bahasa dan Sastra Inggris', 3, 1),
(23, 'Bahasa Arab', 3, 1),
(24, 'Bahasa Mandarin', 3, 1),
(25, 'Bahasa Jepang', 3, 1),
(26, 'Bahasa Korea', 3, 1),
(27, 'Bahasa Jerman', 3, 1),
(28, 'Bahasa Perancis', 3, 1),
(29, 'Teknik Broadcasting', 3, 1),
(30, 'Teknik Komputer Dan Informatika', 3, 1),
(31, 'Teknik Telekomunikasi', 3, 1),
(32, 'Geologi Pertambangan', 3, 1),
(33, 'Geomatika', 3, 1),
(34, 'Teknik Grafika', 3, 1),
(35, 'Teknik Bangunan', 3, 1),
(36, 'Teknik Elektronika', 3, 1),
(37, 'Teknik Energi Terbarukan', 3, 1),
(38, 'Teknik Furnitur', 3, 1),
(39, 'Teknik Industri', 3, 1),
(40, 'Teknik Instrumentasi Industri', 3, 1),
(41, 'Teknik Ketenagalistrikan', 3, 1),
(42, 'Teknik Kimia', 3, 1),
(43, 'Teknik Mesin', 3, 1),
(44, 'Teknik Otomotif', 3, 1),
(45, 'Teknik Perkapalan', 3, 1),
(46, 'Teknik Perminyakan', 3, 1),
(47, 'Teknik Pesawat Udara', 3, 1),
(48, 'Teknik Plambing Dan Sanitasi', 3, 1),
(49, 'Teknologi Tekstil', 3, 1),
(50, 'Pekerjaan Sosial', 3, 1),
(51, 'Kefarmasian', 3, 1),
(52, 'Keperawatan', 3, 1),
(53, 'Agribisnis Pengolahan Hasil Pertanian dan Perikanan', 3, 1),
(54, 'Agribisnis Produksi Tanaman', 3, 1),
(55, 'Agribisnis Produksi Ternak', 3, 1),
(56, 'Kehutanan', 3, 1),
(57, 'Kesehatan Hewan', 3, 1),
(58, 'Mekanisasi Pertanian', 3, 1),
(59, 'Pelayaran', 3, 1),
(60, 'Teknologi Dan Produksi Perikanan Budidaya', 3, 1),
(61, 'Teknologi Penangkapan Ikan', 3, 1),
(62, 'Administrasi', 3, 1),
(63, 'Keuangan', 3, 1),
(64, 'Tata Niaga', 3, 1),
(65, 'Kepariwisataan', 3, 1),
(66, 'Tata Boga', 3, 1),
(67, 'Tata Busana', 3, 1),
(68, 'Tata Kecantikan', 3, 1),
(69, 'Desain Dan Produksi Kriya', 3, 1),
(70, 'Seni Rupa', 3, 1),
(71, 'Seni Karawitan', 3, 1),
(72, 'Seni Musik', 3, 1),
(73, 'Seni Pedalangan', 3, 1),
(74, 'Seni Tari', 3, 1),
(75, 'Seni Teater', 3, 1),
(76, 'Lintas Minat Biologi', 3, 1),
(77, 'Lintas Minat Fisika', 3, 1),
(78, 'Lintas Minat Kimia', 3, 1),
(79, 'Lintas Minat Geografi', 3, 1),
(80, 'Lintas Minat Sejarah', 3, 1),
(81, 'Lintas Minat Sosiologi', 3, 1),
(82, 'Lintas Minat Antropologi', 3, 1),
(83, 'Lintas Minat Ekonomi', 3, 1),
(84, 'Lintas Minat Bahasa Arab', 3, 1),
(85, 'Lintas Minat Bahasa Mandarin', 3, 1),
(86, 'Lintas Minat Bahasa Jepang', 3, 1),
(87, 'Lintas Minat Bahasa Korea', 3, 1),
(88, 'Lintas Minat Bahasa Jerman', 3, 1),
(89, 'Lintas Minat Bahasa Perancis', 3, 1),
(90, 'Lintas Minat Bahasa Inggris', 3, NULL),
(91, 'Bahasa Sunda', 2, NULL),
(92, 'Try Out', 4, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `lesson_mc`
--

CREATE TABLE `lesson_mc` (
  `id` int(11) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `detail_id` int(11) DEFAULT NULL,
  `presensi_hadir` int(11) DEFAULT NULL,
  `presensi_sakit` int(11) DEFAULT NULL,
  `presensi_izin` int(11) DEFAULT NULL,
  `presensi_alfa` int(11) DEFAULT NULL,
  `trash` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `lks`
--

CREATE TABLE `lks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET latin1 NOT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `assignments` text CHARACTER SET latin1,
  `chapters` text CHARACTER SET latin1,
  `quizes` text CHARACTER SET latin1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `teacher_id` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `content` varchar(255) CHARACTER SET latin1 NOT NULL,
  `user_id` int(11) NOT NULL,
  `user_id_to` int(11) DEFAULT NULL,
  `tipe` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `read_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `relation_id` int(11) DEFAULT NULL,
  `class_id_to` int(11) DEFAULT NULL,
  `read_id` varchar(255) CHARACTER SET latin1 DEFAULT ',',
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `oauth_token`
--

CREATE TABLE `oauth_token` (
  `id` int(11) NOT NULL,
  `id_user` int(11) DEFAULT NULL,
  `token` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `last_token` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `expired_date` timestamp NULL DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `offline_mark`
--

CREATE TABLE `offline_mark` (
  `id` int(11) NOT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `assignment_id` int(11) DEFAULT NULL,
  `student_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `file` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `mark_type` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `options`
--

CREATE TABLE `options` (
  `id` int(11) NOT NULL,
  `key_config` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `value` text CHARACTER SET latin1,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `options`
--

INSERT INTO `options` (`id`, `key_config`, `value`, `created_at`, `updated_at`, `created_by`, `updated_by`, `sync_status`) VALUES
(1, 'school_name', 'RIZKY FAJAR ANUGRAH', '2019-08-13 15:43:08', '2019-08-13 15:43:08', 1, NULL, NULL),
(2, 'npsn', '6742541724517451725471', '2019-08-13 15:43:09', '2019-08-13 15:43:09', 1, NULL, NULL),
(3, 'nss', '', '2019-08-13 15:43:09', '2019-08-13 15:43:09', 1, NULL, NULL),
(4, 'kepsek_id', '1', '2019-08-13 15:43:09', '2019-08-13 15:43:09', 1, NULL, NULL),
(5, 'school_address', 'Jalan Percobaan', '2019-08-13 15:43:09', '2019-08-13 15:43:09', 1, NULL, NULL),
(6, 'kelurahan', 'Bandung', '2019-08-13 15:43:09', '2019-08-13 15:43:09', 1, NULL, NULL),
(7, 'kecamatan', 'Bandung', '2019-08-13 15:43:09', '2019-08-13 15:43:09', 1, NULL, NULL),
(8, 'kota_kabupaten', 'Bandung', '2019-08-13 15:43:09', '2019-08-13 15:43:09', 1, NULL, NULL),
(9, 'provinsi', 'Jawa Barat', '2019-08-13 15:43:09', '2019-08-13 15:43:09', 1, NULL, NULL),
(10, 'website', '', '2019-08-13 15:43:10', '2019-08-13 15:43:10', 1, NULL, NULL),
(11, 'email', '', '2019-08-13 15:43:10', '2019-08-13 15:43:10', 1, NULL, NULL),
(12, 'kurikulum', '2013', '2019-08-13 15:43:10', '2019-08-13 15:43:10', 1, NULL, NULL),
(13, 'fitur_ulangan', '1', '2019-08-13 15:43:10', '2019-08-13 15:43:10', 1, NULL, NULL),
(14, 'fitur_tugas', '1', '2019-08-13 15:43:10', '2019-08-13 15:43:10', 1, NULL, NULL),
(15, 'fitur_materi', '1', '2019-08-13 15:43:10', '2019-08-13 15:43:10', 1, NULL, NULL),
(16, 'fitur_rekap', '1', '2019-08-13 15:43:10', '2019-08-13 15:43:10', 1, NULL, NULL),
(17, 'semester', '1', '2019-08-13 15:43:10', '2019-08-13 15:43:10', 1, NULL, NULL),
(18, 'tahun_ajaran', '2016', '2019-08-13 15:43:10', '2019-08-13 15:43:10', 1, NULL, NULL),
(19, 'server', '', '2019-08-13 15:43:10', '2019-08-13 15:43:10', 1, NULL, NULL),
(20, 'nilai_harian', '', '2019-08-13 15:43:10', '2019-08-13 15:43:10', 1, NULL, NULL),
(21, 'nilai_uts', '', '2019-08-13 15:43:10', '2019-08-13 15:43:10', 1, NULL, NULL),
(22, 'nilai_uas', '', '2019-08-13 15:43:10', '2019-08-13 15:43:10', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `quiz_id` text CHARACTER SET latin1,
  `lesson_id` int(11) DEFAULT NULL,
  `title` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `text` longtext,
  `choices` longtext,
  `key_answer` longtext,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `teacher_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `file` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `point` int(11) DEFAULT NULL,
  `choices_files` longtext CHARACTER SET latin1,
  `id_lama` int(11) DEFAULT NULL,
  `share_status` int(11) DEFAULT NULL,
  `share_teacher` longtext CHARACTER SET latin1,
  `trash` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL,
  `stats_status` int(11) DEFAULT NULL,
  `kd` int(11) DEFAULT NULL,
  `ki` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `quiz`
--

CREATE TABLE `quiz` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `lesson_id` int(11) NOT NULL,
  `chapter_id` int(11) DEFAULT NULL,
  `quiz_type` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `start_time` timestamp NULL DEFAULT NULL,
  `finish_time` timestamp NULL DEFAULT NULL,
  `end_time` int(11) DEFAULT NULL,
  `total_question` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `add_to_summary` int(11) DEFAULT NULL,
  `repeat_quiz` int(11) DEFAULT NULL,
  `question` longtext CHARACTER SET latin1,
  `semester` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL,
  `random` int(11) DEFAULT NULL,
  `show_nilai` int(11) DEFAULT NULL,
  `id_bersama` int(11) DEFAULT NULL,
  `trash` int(11) DEFAULT NULL,
  `pg_prosentase` int(11) DEFAULT NULL,
  `esai_prosentase` int(11) DEFAULT NULL,
  `passcode` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `random_opt` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL,
  `stats_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `rpp`
--

CREATE TABLE `rpp` (
  `id` int(11) NOT NULL,
  `title` varchar(255) CHARACTER SET latin1 NOT NULL,
  `lesson_id` int(11) DEFAULT NULL,
  `pertemuan` text CHARACTER SET latin1,
  `alokasi_waktu` int(11) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `standar_kompetensi` text CHARACTER SET latin1,
  `kompetensi_dasar` text CHARACTER SET latin1,
  `tujuan` text CHARACTER SET latin1,
  `indikator` text CHARACTER SET latin1,
  `materi_ajar` text CHARACTER SET latin1,
  `metode_pembelajaran` text CHARACTER SET latin1,
  `langkah_pembelajaran` text CHARACTER SET latin1,
  `alat_bahan_sumber` text CHARACTER SET latin1,
  `penilaian` text CHARACTER SET latin1,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `session`
--

CREATE TABLE `session` (
  `id` char(32) CHARACTER SET latin1 NOT NULL,
  `expire` int(11) DEFAULT NULL,
  `data` longblob,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `skill`
--

CREATE TABLE `skill` (
  `id` int(11) NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `lesson_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `trash` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `student_assignment`
--

CREATE TABLE `student_assignment` (
  `id` int(11) NOT NULL,
  `assignment_id` int(11) NOT NULL,
  `content` text CHARACTER SET latin1,
  `file` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `student_id` int(11) NOT NULL,
  `score` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `status` int(11) DEFAULT NULL,
  `trash` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `student_quiz`
--

CREATE TABLE `student_quiz` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `score` double DEFAULT NULL,
  `essay_score` int(11) DEFAULT NULL,
  `pg_score` int(11) DEFAULT NULL,
  `right_answer` int(11) DEFAULT NULL,
  `wrong_answer` int(11) DEFAULT NULL,
  `unanswered` int(11) DEFAULT NULL,
  `student_answer` longtext CHARACTER SET latin1,
  `attempt` int(11) DEFAULT NULL,
  `indikasi` int(11) DEFAULT NULL,
  `trash` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL,
  `stats_status` int(11) DEFAULT NULL,
  `kd` text,
  `nisn` varchar(100) DEFAULT NULL,
  `display_name` varchar(100) DEFAULT NULL,
  `class` varchar(100) DEFAULT NULL,
  `school_name` varchar(100) DEFAULT NULL,
  `semester` int(11) DEFAULT NULL,
  `year` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `student_quiz_temp`
--

CREATE TABLE `student_quiz_temp` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `score` int(11) DEFAULT NULL,
  `essay_score` int(11) DEFAULT NULL,
  `pg_score` int(11) DEFAULT NULL,
  `right_answer` int(11) DEFAULT NULL,
  `wrong_answer` int(11) DEFAULT NULL,
  `unanswered` int(11) DEFAULT NULL,
  `student_answer` longtext CHARACTER SET latin1,
  `attempt` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL,
  `indikasi` int(11) DEFAULT NULL,
  `trash` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `student_skill`
--

CREATE TABLE `student_skill` (
  `id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `score` int(11) DEFAULT NULL,
  `trash` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Struktur dari tabel `tata_tertib`
--

CREATE TABLE `tata_tertib` (
  `id` int(11) NOT NULL,
  `edited` text NOT NULL,
  `reset` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data untuk tabel `tata_tertib`
--

INSERT INTO `tata_tertib` (`id`, `edited`, `reset`) VALUES
(1, '<h2 style="text-align:center"><span style="font-family:Times New Roman,Times,serif">TATA TERTIB PESERTA </span></h2>\r\n\r\n<h2 style="text-align: center;"><span style="font-size:14px"><span style="font-family:Times New Roman,Times,serif">PENILAIAN TENGAH SEMESTER GANGIL</span></span></h2>\r\n\r\n<h2 style="text-align: center;"><span style="font-size:14px"><span style="font-family:Times New Roman,Times,serif">TAHUN AJARAN 2017-2018</span></span></h2>\r\n\r\n<h2 style="text-align:center"><strong>Peserta Ujian :</strong></h2>\r\n\r\n<ol>\r\n	<li>Memasuki ruangan setelah tanda masuk dibunyikan, yakni 15 (lima belas) menit sebelum ujian dimulai.</li>\r\n	<li>Memasuki ruang ujian dan menempati tempat duduk yang telah ditentukan.</li>\r\n	<li>Yang terlambat hadir hanya diperkenankan mengikuti ujian setelah mendapatkan izin dari Ketua Panitia UAS/PAT, tanpa diberikan perpanjangan waktu.</li>\r\n	<li>Dilarang membawa dan /atau menggunakan perangkat komunikasi elektronik dan optik, kamera, kalkulator, dan sejenisnya ke dalam ruang ujian.</li>\r\n	<li>Mengumpulkan tas, buku, dan catatan dalam bentuk apapun di bagian depan di dalam ruang kelas.</li>\r\n	<li>Mengisi daftar hadir dengan menggunakan pulpen yang disediakan oleh pengawas ruangan.</li>\r\n	<li>Masuk ke dalam (login) sistem menggunakan username dan password.</li>\r\n	<li>Mulai mengerjakan soal setelah ada tanda waktu mulai ujian.</li>\r\n	<li>Selama ujian berlangsung, hanya dapat meninggalkan ruangan dengan izin dan pengawasan dari pengawas ruang ujian.</li>\r\n	<li>.Selama ujian berlangsung , dilarang:\r\n	<ol>\r\n		<li>Menanyakan jawaban soal kepada siapa pun.</li>\r\n		<li>Bekerjasama dengan peserta lain.</li>\r\n		<li>Memberi atau menerima bantuan dalam menjawab soal.</li>\r\n		<li>Memperlihatkan pekerjaan sendiri kepada peserta lain atau melihat pekerjaan peserta lain.</li>\r\n		<li>Menggantikan atau digantikan oleh orang lain</li>\r\n	</ol>\r\n	</li>\r\n	<li>Yang telah selesai mengerjakan soal sebelum waktu ujian berakhir tidak diperbolehkan meninggalkan ruangan sebelum waktu ujian berakhir.</li>\r\n</ol>\r\n\r\n<p><br />\r\n&nbsp;</p>\r\n\r\n<h4 style="text-align:right"><strong>Panitia Kerja Ujian Akhir Sekolah 2016/2017</strong></h4>\r\n', '<h2 style="text-align: center;">TATA TERTIB PESERTA UJIAN AKHIR SEKOLAH 2016/2017</h2>\n\n<h4><strong>Peserta Ujian :</strong></h4>\n\n<ol>\n	<li>Memasuki ruangan setelah tanda masuk dibunyikan, yakni 15 (lima belas) menit sebelum ujian dimulai.</li>\n	<li>Memasuki ruang ujian dan menempati tempat duduk yang telah ditentukan.</li>\n	<li>Yang terlambat hadir hanya diperkenankan mengikuti ujian setelah mendapatkan izin dari Ketua Panitia UAS/PAT, tanpa diberikan perpanjangan waktu.</li>\n	<li>Dilarang membawa dan /atau menggunakan perangkat komunikasi elektronik dan optik, kamera, kalkulator, dan sejenisnya ke dalam ruang ujian.</li>\n	<li>Mengumpulkan tas, buku, dan catatan dalam bentuk apapun di bagian depan di dalam ruang kelas.</li>\n	<li>Mengisi daftar hadir dengan menggunakan pulpen yang disediakan oleh pengawas ruangan.</li>\n	<li>Masuk ke dalam (login) sistem menggunakan username dan password.</li>\n	<li>Mulai mengerjakan soal setelah ada tanda waktu mulai ujian.</li>\n	<li>Selama ujian berlangsung, hanya dapat meninggalkan ruangan dengan izin dan pengawasan dari pengawas ruang ujian.</li>\n	<li>.Selama ujian berlangsung , dilarang:\n	<ol>\n		<li>Menanyakan jawaban soal kepada siapa pun.</li>\n		<li>Bekerjasama dengan peserta lain.</li>\n		<li>Memberi atau menerima bantuan dalam menjawab soal.</li>\n		<li>Memperlihatkan pekerjaan sendiri kepada peserta lain atau melihat pekerjaan peserta lain.</li>\n		<li>Menggantikan atau digantikan oleh orang lain</li>\n	</ol>\n	</li>\n	<li>Yang telah selesai mengerjakan soal sebelum waktu ujian berakhir tidak diperbolehkan meninggalkan ruangan sebelum waktu ujian berakhir.</li>\n</ol>\n\n<p><br />\n&nbsp;</p>\n\n<h4 style="text-align: right;"><strong>Panitia Kerja Ujian Akhir Sekolah 2016/2017</strong></h4>\n');

-- --------------------------------------------------------

--
-- Struktur dari tabel `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) CHARACTER SET latin1 NOT NULL,
  `username` varchar(255) CHARACTER SET latin1 NOT NULL,
  `encrypted_password` varchar(255) CHARACTER SET latin1 NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `class_id` int(11) DEFAULT NULL,
  `reset_password` varchar(10) CHARACTER SET latin1 DEFAULT NULL,
  `display_name` varchar(255) CHARACTER SET latin1 NOT NULL,
  `image` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `trash` int(11) DEFAULT NULL,
  `nisn` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL,
  `stats_status` int(11) DEFAULT NULL,
  `id_absen_solution` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `encrypted_password`, `role_id`, `created_at`, `updated_at`, `class_id`, `reset_password`, `display_name`, `image`, `trash`, `nisn`, `sync_status`, `stats_status`, `id_absen_solution`) VALUES
(1, 'admin@gmail.com', 'admin', '$2a$08$IdVumYEtiS5/FYMFBeOzle19412VtrPJ2JFyPTx2FrR6LZE7cMWfG', 99, '2015-11-03 08:47:59', '2015-12-01 06:41:39', 0, '', 'Administrator S', '', NULL, '', 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_logs`
--

CREATE TABLE `user_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT '0000-00-00 00:00:00',
  `quiz_id` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL,
  `stats_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data untuk tabel `user_logs`
--

INSERT INTO `user_logs` (`id`, `user_id`, `type`, `created_at`, `quiz_id`, `status`, `sync_status`, `stats_status`) VALUES
(1, 1, 'login', '2019-08-13 15:41:49', NULL, NULL, NULL, NULL),
(2, 1, 'logout', '2019-08-13 15:42:04', NULL, NULL, NULL, NULL),
(3, 1, 'login', '2019-08-13 15:42:11', NULL, NULL, NULL, NULL),
(4, 1, 'login', '2019-08-13 15:42:37', NULL, NULL, NULL, NULL),
(5, 1, 'logout', '2019-08-13 15:44:19', NULL, NULL, NULL, NULL),
(6, 1, 'login', '2019-08-13 15:44:26', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_profile`
--

CREATE TABLE `user_profile` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nisn` varchar(255) DEFAULT NULL,
  `j_kelamin` varchar(255) NOT NULL,
  `no_seri_ijazah` varchar(255) NOT NULL,
  `no_seri_skhun` varchar(255) NOT NULL,
  `no_un` varchar(255) NOT NULL,
  `nik` varchar(255) NOT NULL,
  `tempat_lahir` varchar(255) NOT NULL,
  `tgl_lahir` date NOT NULL,
  `agama` varchar(255) NOT NULL,
  `berkebutuhan_khusus` varchar(255) DEFAULT NULL,
  `alamat_tinggal` varchar(255) NOT NULL,
  `alamat_dusun` varchar(255) DEFAULT NULL,
  `alamat_rt` varchar(255) NOT NULL,
  `alamat_rw` varchar(255) NOT NULL,
  `alamat_kelurahan` varchar(255) NOT NULL,
  `alamat_kodepos` varchar(255) DEFAULT NULL,
  `alamat_kecamatan` varchar(255) NOT NULL,
  `alamat_kota` varchar(255) NOT NULL,
  `alamat_provinsi` varchar(255) NOT NULL,
  `alat_transportasi` varchar(255) NOT NULL,
  `jenis_tinggal` varchar(255) NOT NULL,
  `no_telpon` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `penerima_kps` varchar(255) NOT NULL,
  `no_kps` varchar(255) DEFAULT NULL,
  `ayah_nama` varchar(255) NOT NULL,
  `ayah_thn_lahir` varchar(255) NOT NULL,
  `ayah_berkebutuhan_khusus` varchar(255) DEFAULT NULL,
  `ayah_pekerjaan` varchar(255) NOT NULL,
  `ayah_pendidikan` varchar(255) NOT NULL,
  `ayah_penghasilan` bigint(20) NOT NULL,
  `ibu_nama` varchar(255) NOT NULL,
  `ibu_thn_lahir` varchar(255) NOT NULL,
  `ibu_berkebutuhan_khusus` varchar(255) DEFAULT NULL,
  `ibu_pekerjaan` varchar(255) NOT NULL,
  `ibu_pendidikan` varchar(255) NOT NULL,
  `ibu_penghasilan` bigint(20) NOT NULL,
  `wali_nama` varchar(255) DEFAULT NULL,
  `wali_thn_lahir` varchar(255) DEFAULT NULL,
  `wali_berkebutuhan_khusus` varchar(255) DEFAULT NULL,
  `wali_pekerjaan` varchar(255) DEFAULT NULL,
  `wali_pendidikan` varchar(255) DEFAULT NULL,
  `wali_penghasilan` bigint(20) DEFAULT NULL,
  `tinggi_badan` int(11) NOT NULL,
  `berat_badan` int(11) NOT NULL,
  `jarak_tempat_tgl_ke_sekolah` double NOT NULL,
  `jarak_tempat_tgl_ke_sekolah_lebih` int(11) DEFAULT NULL,
  `waktu_tempuh_ke_sekolah` int(11) NOT NULL,
  `waktu_tempuh_ke_sekolah_lebih` int(11) DEFAULT NULL,
  `jumlah_saudara_kandung` int(11) NOT NULL,
  `prestasi_01_jenis` varchar(255) DEFAULT NULL,
  `prestasi_01_tingkat` varchar(255) DEFAULT NULL,
  `prestasi_01_nama` varchar(255) DEFAULT NULL,
  `prestasi_01_tahun` varchar(255) DEFAULT NULL,
  `prestasi_01_penyelenggara` varchar(255) DEFAULT NULL,
  `prestasi_02_jenis` varchar(255) DEFAULT NULL,
  `prestasi_02_tingkat` varchar(255) DEFAULT NULL,
  `prestasi_02_nama` varchar(255) DEFAULT NULL,
  `prestasi_02_tahun` varchar(255) DEFAULT NULL,
  `prestasi_02_penyelenggara` varchar(255) DEFAULT NULL,
  `prestasi_03_jenis` varchar(255) DEFAULT NULL,
  `prestasi_03_tingkat` varchar(255) DEFAULT NULL,
  `prestasi_03_nama` varchar(255) DEFAULT NULL,
  `prestasi_03_tahun` varchar(255) DEFAULT NULL,
  `prestasi_03_penyelenggara` varchar(255) DEFAULT NULL,
  `prestasi_04_jenis` varchar(255) DEFAULT NULL,
  `prestasi_04_tingkat` varchar(255) DEFAULT NULL,
  `prestasi_04_nama` varchar(255) DEFAULT NULL,
  `prestasi_04_tahun` varchar(255) DEFAULT NULL,
  `prestasi_04_penyelenggara` varchar(255) DEFAULT NULL,
  `beasiswa_01_jenis` varchar(255) DEFAULT NULL,
  `beasiswa_01_sumber` varchar(255) DEFAULT NULL,
  `beasiswa_01_thn_mulai` varchar(255) DEFAULT NULL,
  `beasiswa_01_thn_selesai` varchar(255) DEFAULT NULL,
  `beasiswa_02_jenis` varchar(255) DEFAULT NULL,
  `beasiswa_02_sumber` varchar(255) DEFAULT NULL,
  `beasiswa_02_thn_mulai` varchar(255) DEFAULT NULL,
  `beasiswa_02_thn_selesai` varchar(255) DEFAULT NULL,
  `beasiswa_03_jenis` varchar(255) DEFAULT NULL,
  `beasiswa_03_sumber` varchar(255) DEFAULT NULL,
  `beasiswa_03_thn_mulai` varchar(255) DEFAULT NULL,
  `beasiswa_03_thn_selesai` varchar(255) DEFAULT NULL,
  `beasiswa_04_jenis` varchar(255) DEFAULT NULL,
  `beasiswa_04_sumber` varchar(255) DEFAULT NULL,
  `beasiswa_04_thn_mulai` varchar(255) DEFAULT NULL,
  `beasiswa_04_thn_selesai` varchar(255) DEFAULT NULL,
  `peminatan` varchar(255) NOT NULL,
  `lintas_minat_01` varchar(255) NOT NULL,
  `lintas_minat_02` varchar(255) NOT NULL,
  `ekskul_01` varchar(255) DEFAULT NULL,
  `ekskul_02` varchar(255) DEFAULT NULL,
  `sync_status` int(11) DEFAULT NULL,
  `status_keluarga` varchar(255) DEFAULT NULL,
  `anak_ke` int(11) DEFAULT NULL,
  `sekolah_asal` varchar(255) DEFAULT NULL,
  `kelas_diterima` varchar(255) DEFAULT NULL,
  `tanggal_diterima` date DEFAULT NULL,
  `alamat_ortu` varchar(255) DEFAULT NULL,
  `no_telp_ortu` varchar(255) DEFAULT NULL,
  `alamat_wali` varchar(255) DEFAULT NULL,
  `no_telp_wali` varchar(255) DEFAULT NULL,
  `pekerjaan_wali` varchar(255) DEFAULT NULL,
  `penerima_kip` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `no_kip` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `penerima_kks` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `no_kks` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `penerima_kis` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `no_kis` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_ayah_tinggal` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_ayah_dusun` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_ayah_rt` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_ayah_rw` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_ayah_kelurahan` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_ayah_kodepos` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_ayah_kecamatan` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_ayah_kota` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_ayah_provinsi` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_ibu_tinggal` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_ibu_dusun` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_ibu_rt` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_ibu_rw` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_ibu_kelurahan` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_ibu_kodepos` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_ibu_kecamatan` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_ibu_kota` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_ibu_provinsi` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_wali_tinggal` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_wali_dusun` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_wali_rt` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_wali_rw` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_wali_kelurahan` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_wali_kodepos` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_wali_kecamatan` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_wali_kota` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `alamat_wali_provinsi` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `no_du` varchar(255) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Struktur dari tabel `user_profile_score`
--

CREATE TABLE `user_profile_score` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `smt_01_pai` int(11) NOT NULL,
  `smt_01_pkn` int(11) NOT NULL,
  `smt_01_bindo` int(11) NOT NULL,
  `smt_01_bingg` int(11) NOT NULL,
  `smt_01_mat` int(11) NOT NULL,
  `smt_01_ipa` int(11) NOT NULL,
  `smt_01_ips` int(11) NOT NULL,
  `smt_01_seni` int(11) NOT NULL,
  `smt_01_or` int(11) NOT NULL,
  `smt_01_tik` int(11) NOT NULL,
  `smt_02_pai` int(11) NOT NULL,
  `smt_02_pkn` int(11) NOT NULL,
  `smt_02_bindo` int(11) NOT NULL,
  `smt_02_bingg` int(11) NOT NULL,
  `smt_02_mat` int(11) NOT NULL,
  `smt_02_ipa` int(11) NOT NULL,
  `smt_02_ips` int(11) NOT NULL,
  `smt_02_seni` int(11) NOT NULL,
  `smt_02_or` int(11) NOT NULL,
  `smt_02_tik` int(11) NOT NULL,
  `smt_03_pai` int(11) NOT NULL,
  `smt_03_pkn` int(11) NOT NULL,
  `smt_03_bindo` int(11) NOT NULL,
  `smt_03_bingg` int(11) NOT NULL,
  `smt_03_mat` int(11) NOT NULL,
  `smt_03_ipa` int(11) NOT NULL,
  `smt_03_ips` int(11) NOT NULL,
  `smt_03_seni` int(11) NOT NULL,
  `smt_03_or` int(11) NOT NULL,
  `smt_03_tik` int(11) NOT NULL,
  `smt_04_pai` int(11) NOT NULL,
  `smt_04_pkn` int(11) NOT NULL,
  `smt_04_bindo` int(11) NOT NULL,
  `smt_04_bingg` int(11) NOT NULL,
  `smt_04_mat` int(11) NOT NULL,
  `smt_04_ipa` int(11) NOT NULL,
  `smt_04_ips` int(11) NOT NULL,
  `smt_04_seni` int(11) NOT NULL,
  `smt_04_or` int(11) NOT NULL,
  `smt_04_tik` int(11) NOT NULL,
  `smt_05_pai` int(11) NOT NULL,
  `smt_05_pkn` int(11) NOT NULL,
  `smt_05_bindo` int(11) NOT NULL,
  `smt_05_bingg` int(11) NOT NULL,
  `smt_05_mat` int(11) NOT NULL,
  `smt_05_ipa` int(11) NOT NULL,
  `smt_05_ips` int(11) NOT NULL,
  `smt_05_seni` int(11) NOT NULL,
  `smt_05_or` int(11) NOT NULL,
  `smt_05_tik` int(11) NOT NULL,
  `smt_06_pai` int(11) NOT NULL,
  `smt_06_pkn` int(11) NOT NULL,
  `smt_06_bindo` int(11) NOT NULL,
  `smt_06_bingg` int(11) NOT NULL,
  `smt_06_mat` int(11) NOT NULL,
  `smt_06_ipa` int(11) NOT NULL,
  `smt_06_ips` int(11) NOT NULL,
  `smt_06_seni` int(11) NOT NULL,
  `smt_06_or` int(11) NOT NULL,
  `smt_06_tik` int(11) NOT NULL,
  `sync_status` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `absensi`
--
ALTER TABLE `absensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_lesson` (`id_lesson`);

--
-- Indexes for table `absensi_harian`
--
ALTER TABLE `absensi_harian`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `absensi_solution_x_onehundred_c`
--
ALTER TABLE `absensi_solution_x_onehundred_c`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `activities`
--
ALTER TABLE `activities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `actual_table_name`
--
ALTER TABLE `actual_table_name`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `announcements`
--
ALTER TABLE `announcements`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `assignment`
--
ALTER TABLE `assignment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `berita_acara`
--
ALTER TABLE `berita_acara`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `chapters`
--
ALTER TABLE `chapters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_lesson` (`id_lesson`);

--
-- Indexes for table `chapter_files`
--
ALTER TABLE `chapter_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_chapter` (`id_chapter`);

--
-- Indexes for table `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_detail`
--
ALTER TABLE `class_detail`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `class_history`
--
ALTER TABLE `class_history`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam`
--
ALTER TABLE `exam`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_room`
--
ALTER TABLE `exam_room`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_schedule`
--
ALTER TABLE `exam_schedule`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_schedule_class`
--
ALTER TABLE `exam_schedule_class`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `exam_student_list`
--
ALTER TABLE `exam_student_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `final_mark`
--
ALTER TABLE `final_mark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lesson`
--
ALTER TABLE `lesson`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lesson_kd`
--
ALTER TABLE `lesson_kd`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lesson_list`
--
ALTER TABLE `lesson_list`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lesson_mc`
--
ALTER TABLE `lesson_mc`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `lks`
--
ALTER TABLE `lks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `oauth_token`
--
ALTER TABLE `oauth_token`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offline_mark`
--
ALTER TABLE `offline_mark`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `options`
--
ALTER TABLE `options`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `quiz`
--
ALTER TABLE `quiz`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `rpp`
--
ALTER TABLE `rpp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `session`
--
ALTER TABLE `session`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `skill`
--
ALTER TABLE `skill`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_assignment`
--
ALTER TABLE `student_assignment`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_quiz`
--
ALTER TABLE `student_quiz`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_quiz_temp`
--
ALTER TABLE `student_quiz_temp`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `student_skill`
--
ALTER TABLE `student_skill`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tata_tertib`
--
ALTER TABLE `tata_tertib`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_logs`
--
ALTER TABLE `user_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_profile`
--
ALTER TABLE `user_profile`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_profile_score`
--
ALTER TABLE `user_profile_score`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `absensi`
--
ALTER TABLE `absensi`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `absensi_harian`
--
ALTER TABLE `absensi_harian`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `absensi_solution_x_onehundred_c`
--
ALTER TABLE `absensi_solution_x_onehundred_c`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `activities`
--
ALTER TABLE `activities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `announcements`
--
ALTER TABLE `announcements`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `assignment`
--
ALTER TABLE `assignment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `berita_acara`
--
ALTER TABLE `berita_acara`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `chapters`
--
ALTER TABLE `chapters`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `chapter_files`
--
ALTER TABLE `chapter_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `class`
--
ALTER TABLE `class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `class_detail`
--
ALTER TABLE `class_detail`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `class_history`
--
ALTER TABLE `class_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `exam`
--
ALTER TABLE `exam`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `exam_room`
--
ALTER TABLE `exam_room`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `exam_schedule`
--
ALTER TABLE `exam_schedule`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `exam_schedule_class`
--
ALTER TABLE `exam_schedule_class`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `exam_student_list`
--
ALTER TABLE `exam_student_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `final_mark`
--
ALTER TABLE `final_mark`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lesson`
--
ALTER TABLE `lesson`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lesson_kd`
--
ALTER TABLE `lesson_kd`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lesson_list`
--
ALTER TABLE `lesson_list`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;
--
-- AUTO_INCREMENT for table `lesson_mc`
--
ALTER TABLE `lesson_mc`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `lks`
--
ALTER TABLE `lks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `oauth_token`
--
ALTER TABLE `oauth_token`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `offline_mark`
--
ALTER TABLE `offline_mark`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `options`
--
ALTER TABLE `options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;
--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `quiz`
--
ALTER TABLE `quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `rpp`
--
ALTER TABLE `rpp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `skill`
--
ALTER TABLE `skill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `student_assignment`
--
ALTER TABLE `student_assignment`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `student_quiz`
--
ALTER TABLE `student_quiz`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `student_quiz_temp`
--
ALTER TABLE `student_quiz_temp`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `student_skill`
--
ALTER TABLE `student_skill`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tata_tertib`
--
ALTER TABLE `tata_tertib`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user_logs`
--
ALTER TABLE `user_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `user_profile`
--
ALTER TABLE `user_profile`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user_profile_score`
--
ALTER TABLE `user_profile_score`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Ketidakleluasaan untuk tabel pelimpahan (Dumped Tables)
--

--
-- Ketidakleluasaan untuk tabel `absensi`
--
ALTER TABLE `absensi`
  ADD CONSTRAINT `absensi_ibfk_1` FOREIGN KEY (`id_lesson`) REFERENCES `lesson` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
