<?php

/**
 * This is the model class for table "user_profile".
 *
 * The followings are the available columns in table 'user_profile':
 * @property integer $id
 * @property integer $user_id
 * @property string $nisn
 * @property string $j_kelamin
 * @property string $no_seri_ijazah
 * @property string $no_seri_skhun
 * @property string $no_un
 * @property string $nik
 * @property string $tempat_lahir
 * @property string $tgl_lahir
 * @property string $agama
 * @property string $berkebutuhan_khusus
 * @property string $alamat_tinggal
 * @property string $alamat_dusun
 * @property string $alamat_rt
 * @property string $alamat_rw
 * @property string $alamat_kelurahan
 * @property string $alamat_kodepos
 * @property string $alamat_kecamatan
 * @property string $alamat_kota
 * @property string $alamat_provinsi
 * @property string $alat_transportasi
 * @property string $jenis_tinggal
 * @property string $no_telpon
 * @property string $email
 * @property string $penerima_kps
 * @property string $no_kps
 * @property string $ayah_nama
 * @property string $ayah_thn_lahir
 * @property string $ayah_berkebutuhan_khusus
 * @property string $ayah_pekerjaan
 * @property string $ayah_pendidikan
 * @property string $ayah_penghasilan
 * @property string $ibu_nama
 * @property string $ibu_thn_lahir
 * @property string $ibu_berkebutuhan_khusus
 * @property string $ibu_pekerjaan
 * @property string $ibu_pendidikan
 * @property string $ibu_penghasilan
 * @property string $wali_nama
 * @property string $wali_thn_lahir
 * @property string $wali_berkebutuhan_khusus
 * @property string $wali_pekerjaan
 * @property string $wali_pendidikan
 * @property string $wali_penghasilan
 * @property integer $tinggi_badan
 * @property integer $berat_badan
 * @property double $jarak_tempat_tgl_ke_sekolah
 * @property integer $jarak_tempat_tgl_ke_sekolah_lebih
 * @property integer $waktu_tempuh_ke_sekolah
 * @property integer $waktu_tempuh_ke_sekolah_lebih
 * @property integer $jumlah_saudara_kandung
 * @property string $prestasi_01_jenis
 * @property string $prestasi_01_tingkat
 * @property string $prestasi_01_nama
 * @property string $prestasi_01_tahun
 * @property string $prestasi_01_penyelenggara
 * @property string $prestasi_02_jenis
 * @property string $prestasi_02_tingkat
 * @property string $prestasi_02_nama
 * @property string $prestasi_02_tahun
 * @property string $prestasi_02_penyelenggara
 * @property string $prestasi_03_jenis
 * @property string $prestasi_03_tingkat
 * @property string $prestasi_03_nama
 * @property string $prestasi_03_tahun
 * @property string $prestasi_03_penyelenggara
 * @property string $prestasi_04_jenis
 * @property string $prestasi_04_tingkat
 * @property string $prestasi_04_nama
 * @property string $prestasi_04_tahun
 * @property string $prestasi_04_penyelenggara
 * @property string $beasiswa_01_jenis
 * @property string $beasiswa_01_sumber
 * @property string $beasiswa_01_thn_mulai
 * @property string $beasiswa_01_thn_selesai
 * @property string $beasiswa_02_jenis
 * @property string $beasiswa_02_sumber
 * @property string $beasiswa_02_thn_mulai
 * @property string $beasiswa_02_thn_selesai
 * @property string $beasiswa_03_jenis
 * @property string $beasiswa_03_sumber
 * @property string $beasiswa_03_thn_mulai
 * @property string $beasiswa_03_thn_selesai
 * @property string $beasiswa_04_jenis
 * @property string $beasiswa_04_sumber
 * @property string $beasiswa_04_thn_mulai
 * @property string $beasiswa_04_thn_selesai
 * @property string $peminatan
 * @property string $lintas_minat_01
 * @property string $lintas_minat_02
 * @property string $ekskul_01
 * @property string $ekskul_02
 * @property string $status_keluarga
 * @property string $anak_ke
 * @property string $sekolah_asal
 * @property string $kelas_diterima
 * @property string $tanggal_diterima
 * @property string $alamat_ortu
 * @property string $no_telp_ortu
 * @property string $alamat_wali
 * @property string $no_telp_wali
 * @property string $pekerjaan_wali
 */
class UserProfile extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'user_profile';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('no_telp_ortu', 'required'),
			
			array('user_id, tinggi_badan, berat_badan, jarak_tempat_tgl_ke_sekolah_lebih, waktu_tempuh_ke_sekolah, waktu_tempuh_ke_sekolah_lebih, jumlah_saudara_kandung', 'numerical', 'integerOnly'=>true),
			array('ayah_penghasilan, ibu_penghasilan, wali_penghasilan', 'length', 'max'=>20),
			array('jarak_tempat_tgl_ke_sekolah', 'numerical'),
			
			array('alamat_ayah_tinggal,alamat_ayah_dusun,alamat_ayah_rt,alamat_ayah_rw,alamat_ayah_kelurahan,alamat_ayah_kodepos,alamat_ayah_kecamatan,alamat_ayah_kota,alamat_ayah_provinsi,alamat_ibu_tinggal,alamat_ibu_dusun,alamat_ibu_rt,alamat_ibu_rw,alamat_ibu_kelurahan,alamat_ibu_kodepos,alamat_ibu_kecamatan,alamat_ibu_kota,alamat_ibu_provinsi,alamat_wali_tinggal,alamat_wali_dusun,alamat_wali_rt,alamat_wali_rw,alamat_wali_kelurahan,alamat_wali_kodepos,alamat_wali_kecamatan,alamat_wali_kota,alamat_wali_provinsi,penerima_kis, no_kis, penerima_kks, no_kks, penerima_kip, no_kip, nisn, j_kelamin, no_seri_ijazah, no_seri_skhun, no_un, nik, tempat_lahir, agama, berkebutuhan_khusus, alamat_tinggal, alamat_dusun, alamat_rt, alamat_rw, alamat_kelurahan, alamat_kodepos, alamat_kecamatan, alamat_kota, alamat_provinsi, alat_transportasi, jenis_tinggal, no_telpon, email, penerima_kps, no_kps, ayah_nama, ayah_thn_lahir, ayah_berkebutuhan_khusus, ayah_pekerjaan, ayah_pendidikan, ibu_nama, ibu_thn_lahir, ibu_berkebutuhan_khusus, ibu_pekerjaan, ibu_pendidikan, wali_nama, wali_thn_lahir, wali_berkebutuhan_khusus, wali_pekerjaan, wali_pendidikan, prestasi_01_jenis, prestasi_01_tingkat, prestasi_01_nama, prestasi_01_tahun, prestasi_01_penyelenggara, prestasi_02_jenis, prestasi_02_tingkat, prestasi_02_nama, prestasi_02_tahun, prestasi_02_penyelenggara, prestasi_03_jenis, prestasi_03_tingkat, prestasi_03_nama, prestasi_03_tahun, prestasi_03_penyelenggara, prestasi_04_jenis, prestasi_04_tingkat, prestasi_04_nama, prestasi_04_tahun, prestasi_04_penyelenggara, beasiswa_01_jenis, beasiswa_01_sumber, beasiswa_01_thn_mulai, beasiswa_01_thn_selesai, beasiswa_02_jenis, beasiswa_02_sumber, beasiswa_02_thn_mulai, beasiswa_02_thn_selesai, beasiswa_03_jenis, beasiswa_03_sumber, beasiswa_03_thn_mulai, beasiswa_03_thn_selesai, beasiswa_04_jenis, beasiswa_04_sumber, beasiswa_04_thn_mulai, beasiswa_04_thn_selesai, peminatan, lintas_minat_01, lintas_minat_02, ekskul_01, ekskul_02,status_keluarga,anak_ke,sekolah_asal,kelas_diterima,tanggal_diterima,alamat_ortu,no_telp_ortu,alamat_wali,no_telp_wali,pekerjaan_wali', 'length', 'max'=>255),
			
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, user_id', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'user' => array(self::BELONGS_TO, 'Users', 'user_id'),			
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User ID',
			'nisn' => 'NISN',
			'j_kelamin' => 'Jenis Kelamin',
			'no_seri_ijazah' => 'No Seri Ijazah',
			'no_seri_skhun' => 'No Seri SKHUN',
			'no_un' => 'No Ujian Nasional',
			'nik' => 'NIK',
			'tempat_lahir' => 'Tempat Lahir',
			'tgl_lahir' => 'Tanggal Lahir (Thn-Bln-Tgl)',
			'agama' => 'Agama',
			'berkebutuhan_khusus' => 'Berkebutuhan Khusus',
			'alamat_tinggal' => 'Alamat Tempat Tinggal',
			'alamat_dusun' => 'Dusun',
			'alamat_rt' => 'RT',
			'alamat_rw' => 'RW',
			'alamat_kelurahan' => 'Kelurahan/Desa',
			'alamat_kodepos' => 'Kode POS',
			'alamat_kecamatan' => 'Kecamatan',
			'alamat_kota' => 'Kabupaten/Kota',
			'alamat_provinsi' => 'Provinsi',
			'alamat_ayah_tinggal' => 'Alamat Tempat Tinggal',
			'alamat_ayah_dusun' => 'Dusun',
			'alamat_ayah_rt' => 'RT',
			'alamat_ayah_rw' => 'RW',
			'alamat_ayah_kelurahan' => 'Kelurahan/Desa',
			'alamat_ayah_kodepos' => 'Kode POS',
			'alamat_ayah_kecamatan' => 'Kecamatan',
			'alamat_ayah_kota' => 'Kabupaten/Kota',
			'alamat_ayah_provinsi' => 'Provinsi',
			'alamat_ibu_tinggal' => 'Alamat Tempat Tinggal',
			'alamat_ibu_dusun' => 'Dusun',
			'alamat_ibu_rt' => 'RT',
			'alamat_ibu_rw' => 'RW',
			'alamat_ibu_kelurahan' => 'Kelurahan/Desa',
			'alamat_ibu_kodepos' => 'Kode POS',
			'alamat_ibu_kecamatan' => 'Kecamatan',
			'alamat_ibu_kota' => 'Kabupaten/Kota',
			'alamat_ibu_provinsi' => 'Provinsi',
			'alamat_wali_tinggal' => 'Alamat Tempat Tinggal',
			'alamat_wali_dusun' => 'Dusun',
			'alamat_wali_rt' => 'RT',
			'alamat_wali_rw' => 'RW',
			'alamat_wali_kelurahan' => 'Kelurahan/Desa',
			'alamat_wali_kodepos' => 'Kode POS',
			'alamat_wali_kecamatan' => 'Kecamatan',
			'alamat_wali_kota' => 'Kabupaten/Kota',
			'alamat_wali_provinsi' => 'Provinsi',
			'alat_transportasi' => 'Alat Transportasi Ke Sekolah',
			'jenis_tinggal' => 'Jenis Tinggal',
			'no_telpon' => 'No Telpon/HP',
			'email' => 'Email Pribadi',
			'penerima_kps' => 'Apakah Sebagai Penerima KPS',
			'no_kps' => 'Jika Penerima KPS, Masukan Nomor KPS',
			'penerima_kip' => 'Apakah Sebagai Penerima KIP',
			'no_kip' => 'Jika Penerima KIP, Masukan Nomor KIP',
			'penerima_kks' => 'Apakah Sebagai Penerima KKS',
			'no_kks' => 'Jika Penerima KKS, Masukan Nomor KKS',
			'penerima_kis' => 'Apakah Sebagai Penerima KIS',
			'no_kis' => 'Jika Penerima KIS, Masukan Nomor KIS',
			'ayah_nama' => 'Nama Ayah',
			'ayah_thn_lahir' => 'Tahun Lahir',
			'ayah_berkebutuhan_khusus' => 'Berkebutuhan Khusus',
			'ayah_pekerjaan' => 'Pekerjaan',
			'ayah_pendidikan' => 'Pendidikan',
			'ayah_penghasilan' => 'Penghasilan',
			'ibu_nama' => 'Nama Ibu',
			'ibu_thn_lahir' => 'Tahun Lahir',
			'ibu_berkebutuhan_khusus' => 'Berkebutuhan Khusus',
			'ibu_pekerjaan' => 'Pekerjaan',
			'ibu_pendidikan' => 'Pendidikan',
			'ibu_penghasilan' => 'Penghasilan',
			'wali_nama' => 'Nama Wali',
			'wali_thn_lahir' => 'Tahun Lahir',
			'wali_berkebutuhan_khusus' => 'Berkebutuhan Khusus',
			'wali_pekerjaan' => 'Pekerjaan',
			'wali_pendidikan' => 'Pendidikan',
			'wali_penghasilan' => 'Penghasilan',
			'tinggi_badan' => 'Tinggi Badan (Cm)',
			'berat_badan' => 'Berat Badan (Kg)',
			'jarak_tempat_tgl_ke_sekolah' => 'Jarak Tempat Tinggal Ke Sekolah (m)',
			'jarak_tempat_tgl_ke_sekolah_lebih' => 'Jika Lebih dari 1 Km (Km)',
			'waktu_tempuh_ke_sekolah' => 'Waktu Tempuh Ke Sekolah (Menit)',
			'waktu_tempuh_ke_sekolah_lebih' => 'Jika Lebih dari 60 Menit (Jam)',			
			'jumlah_saudara_kandung' => 'Jumlah Saudara Kandung',
			'prestasi_01_jenis' => 'Jenis Prestasi Ke-1',
			'prestasi_01_tingkat' => 'Tingkat Prestasi Ke-1',
			'prestasi_01_nama' => 'Nama Prestasi Ke-1',
			'prestasi_01_tahun' => 'Tahun Prestasi Ke-1',
			'prestasi_01_penyelenggara' => 'Penyelenggara Prestasi Ke-1',
			'prestasi_02_jenis' => 'Jenis Prestasi Ke-2',
			'prestasi_02_tingkat' => 'Tingkat Prestasi Ke-2',
			'prestasi_02_nama' => 'Nama Prestasi Ke-2',
			'prestasi_02_tahun' => 'Tahun Prestasi Ke-2',			
			'prestasi_02_penyelenggara' => 'Penyelenggara Prestasi Ke-2',
			'prestasi_03_jenis' => 'Jenis Prestasi Ke-3',
			'prestasi_03_tingkat' => 'Tingkat Prestasi Ke-3',
			'prestasi_03_nama' => 'Nama Prestasi Ke-3',
			'prestasi_03_tahun' => 'Tahun Prestasi Ke-3',			
			'prestasi_03_penyelenggara' => 'Penyelenggara Prestasi Ke-3',
			'prestasi_04_jenis' => 'Jenis Prestasi Ke-4',
			'prestasi_04_tingkat' => 'Tingkat Prestasi Ke-4',
			'prestasi_04_nama' => 'Nama Prestasi Ke-4',
			'prestasi_04_tahun' => 'Tahun Prestasi Ke-4',			
			'prestasi_04_penyelenggara' => 'Penyelenggara Prestasi Ke-4',
			'beasiswa_01_jenis' => 'Jenis Beasiswa Ke-1',
			'beasiswa_01_sumber' => 'Sumber Beasiswa Ke-1',
			'beasiswa_01_thn_mulai' => 'Tahun Mulai Beasiswa Ke-1',
			'beasiswa_01_thn_selesai' => 'Tahun Selesai Beasiswa Ke-1',
			'beasiswa_02_jenis' => 'Jenis Beasiswa Ke-2',
			'beasiswa_02_sumber' => 'Sumber Beasiswa Ke-2',
			'beasiswa_02_thn_mulai' => 'Tahun Mulai Beasiswa Ke-2',
			'beasiswa_02_thn_selesai' => 'Tahun Selesai Beasiswa Ke-',
			'beasiswa_03_jenis' => 'Jenis Beasiswa Ke-3',
			'beasiswa_03_sumber' => 'Sumber Beasiswa Ke-3',
			'beasiswa_03_thn_mulai' => 'Tahun Mulai Beasiswa Ke-3',
			'beasiswa_03_thn_selesai' => 'Tahun Selesai Beasiswa Ke-3',
			'beasiswa_04_jenis' => 'Jenis Beasiswa Ke-4',
			'beasiswa_04_sumber' => 'Sumber Beasiswa Ke-4',
			'beasiswa_04_thn_mulai' => 'Tahun Mulai Beasiswa Ke-4',
			'beasiswa_04_thn_selesai' => 'Tahun Selesai Beasiswa Ke-4',
			'peminatan' => 'Peminatan',
			'lintas_minat_01' => 'Lintas Minat Pilihan Ke-01',
			'lintas_minat_02' => 'Lintas Minat Pilihan Ke-02',
			'ekskul_01' => 'Ekstrakurikuler Pilihan Ke-01',
			'ekskul_02' => 'Ekstrakurikuler Pilihan Ke-02',
			'status_keluarga' => 'Status Keluarga',
			'anak_ke' => 'Anak Ke',
			'sekolah_asal' => 'Sekolah Asal',
			'kelas_diterima' => 'Kelas Diterima',
			'tanggal_diterima' => 'Tanggal Diterima',
			'alamat_ortu' => 'Alamat Orang Tua',
			'no_telp_ortu' => 'No Telepon Orang Tua',
			'alamat_wali' => 'Alamat Wali',
			'no_telp_wali' => 'No Telepon Wali',
			'pekerjaan_wali' => 'Pekerjaan Wali'
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('nisn',$this->nisn,true);
		$criteria->compare('j_kelamin',$this->j_kelamin,true);
		$criteria->compare('no_seri_ijazah',$this->no_seri_ijazah,true);
		$criteria->compare('no_seri_skhun',$this->no_seri_skhun,true);
		$criteria->compare('no_un',$this->no_un,true);
		$criteria->compare('nik',$this->nik,true);
		$criteria->compare('tempat_lahir',$this->tempat_lahir,true);
		$criteria->compare('tgl_lahir',$this->tgl_lahir,true);
		$criteria->compare('agama',$this->agama,true);
		$criteria->compare('berkebutuhan_khusus',$this->berkebutuhan_khusus,true);
		$criteria->compare('alamat_tinggal',$this->alamat_tinggal,true);
		$criteria->compare('alamat_dusun',$this->alamat_dusun,true);
		$criteria->compare('alamat_rt',$this->alamat_rt,true);
		$criteria->compare('alamat_rw',$this->alamat_rw,true);
		$criteria->compare('alamat_kelurahan',$this->alamat_kelurahan,true);
		$criteria->compare('alamat_kodepos',$this->alamat_kodepos,true);
		$criteria->compare('alamat_kecamatan',$this->alamat_kecamatan,true);
		$criteria->compare('alamat_kota',$this->alamat_kota,true);
		$criteria->compare('alamat_provinsi',$this->alamat_provinsi,true);
		$criteria->compare('alat_transportasi',$this->alat_transportasi,true);
		$criteria->compare('jenis_tinggal',$this->jenis_tinggal,true);
		$criteria->compare('no_telpon',$this->no_telpon,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('penerima_kps',$this->penerima_kps,true);
		$criteria->compare('no_kps',$this->no_kps,true);
		$criteria->compare('penerima_kip',$this->penerima_kip,true);
		$criteria->compare('no_kip',$this->no_kip,true);
		$criteria->compare('penerima_kks',$this->penerima_kks,true);
		$criteria->compare('no_kks',$this->no_kks,true);
		$criteria->compare('penerima_kis',$this->penerima_kis,true);
		$criteria->compare('no_kis',$this->no_kis,true);
		$criteria->compare('ayah_nama',$this->ayah_nama,true);
		$criteria->compare('ayah_thn_lahir',$this->ayah_thn_lahir,true);
		$criteria->compare('ayah_berkebutuhan_khusus',$this->ayah_berkebutuhan_khusus,true);
		$criteria->compare('ayah_pekerjaan',$this->ayah_pekerjaan,true);
		$criteria->compare('ayah_pendidikan',$this->ayah_pendidikan,true);
		$criteria->compare('ayah_penghasilan',$this->ayah_penghasilan,true);
		$criteria->compare('ibu_nama',$this->ibu_nama,true);
		$criteria->compare('ibu_thn_lahir',$this->ibu_thn_lahir,true);
		$criteria->compare('ibu_berkebutuhan_khusus',$this->ibu_berkebutuhan_khusus,true);
		$criteria->compare('ibu_pekerjaan',$this->ibu_pekerjaan,true);
		$criteria->compare('ibu_pendidikan',$this->ibu_pendidikan,true);
		$criteria->compare('ibu_penghasilan',$this->ibu_penghasilan,true);
		$criteria->compare('wali_nama',$this->wali_nama,true);
		$criteria->compare('wali_thn_lahir',$this->wali_thn_lahir,true);
		$criteria->compare('wali_berkebutuhan_khusus',$this->wali_berkebutuhan_khusus,true);
		$criteria->compare('wali_pekerjaan',$this->wali_pekerjaan,true);
		$criteria->compare('wali_pendidikan',$this->wali_pendidikan,true);
		$criteria->compare('wali_penghasilan',$this->wali_penghasilan,true);
		$criteria->compare('tinggi_badan',$this->tinggi_badan);
		$criteria->compare('berat_badan',$this->berat_badan);
		$criteria->compare('jarak_tempat_tgl_ke_sekolah',$this->jarak_tempat_tgl_ke_sekolah);
		$criteria->compare('jarak_tempat_tgl_ke_sekolah_lebih',$this->jarak_tempat_tgl_ke_sekolah_lebih);
		$criteria->compare('waktu_tempuh_ke_sekolah',$this->waktu_tempuh_ke_sekolah);
		$criteria->compare('waktu_tempuh_ke_sekolah_lebih',$this->waktu_tempuh_ke_sekolah_lebih);
		$criteria->compare('jumlah_saudara_kandung',$this->jumlah_saudara_kandung);
		$criteria->compare('prestasi_01_jenis',$this->prestasi_01_jenis,true);
		$criteria->compare('prestasi_01_tingkat',$this->prestasi_01_tingkat,true);
		$criteria->compare('prestasi_01_nama',$this->prestasi_01_nama,true);
		$criteria->compare('prestasi_01_tahun',$this->prestasi_01_tahun,true);
		$criteria->compare('prestasi_01_penyelenggara',$this->prestasi_01_penyelenggara,true);
		$criteria->compare('prestasi_02_jenis',$this->prestasi_02_jenis,true);
		$criteria->compare('prestasi_02_tingkat',$this->prestasi_02_tingkat,true);
		$criteria->compare('prestasi_02_nama',$this->prestasi_02_nama,true);
		$criteria->compare('prestasi_02_tahun',$this->prestasi_02_tahun,true);
		$criteria->compare('prestasi_02_penyelenggara',$this->prestasi_02_penyelenggara,true);
		$criteria->compare('prestasi_03_jenis',$this->prestasi_03_jenis,true);
		$criteria->compare('prestasi_03_tingkat',$this->prestasi_03_tingkat,true);
		$criteria->compare('prestasi_03_nama',$this->prestasi_03_nama,true);
		$criteria->compare('prestasi_03_tahun',$this->prestasi_03_tahun,true);
		$criteria->compare('prestasi_03_penyelenggara',$this->prestasi_03_penyelenggara,true);
		$criteria->compare('prestasi_04_jenis',$this->prestasi_04_jenis,true);
		$criteria->compare('prestasi_04_tingkat',$this->prestasi_04_tingkat,true);
		$criteria->compare('prestasi_04_nama',$this->prestasi_04_nama,true);
		$criteria->compare('prestasi_04_tahun',$this->prestasi_04_tahun,true);
		$criteria->compare('prestasi_04_penyelenggara',$this->prestasi_04_penyelenggara,true);
		$criteria->compare('beasiswa_01_jenis',$this->beasiswa_01_jenis,true);
		$criteria->compare('beasiswa_01_sumber',$this->beasiswa_01_sumber,true);
		$criteria->compare('beasiswa_01_thn_mulai',$this->beasiswa_01_thn_mulai,true);
		$criteria->compare('beasiswa_01_thn_selesai',$this->beasiswa_01_thn_selesai,true);
		$criteria->compare('beasiswa_02_jenis',$this->beasiswa_02_jenis,true);
		$criteria->compare('beasiswa_02_sumber',$this->beasiswa_02_sumber,true);
		$criteria->compare('beasiswa_02_thn_mulai',$this->beasiswa_02_thn_mulai,true);
		$criteria->compare('beasiswa_02_thn_selesai',$this->beasiswa_02_thn_selesai,true);
		$criteria->compare('beasiswa_03_jenis',$this->beasiswa_03_jenis,true);
		$criteria->compare('beasiswa_03_sumber',$this->beasiswa_03_sumber,true);
		$criteria->compare('beasiswa_03_thn_mulai',$this->beasiswa_03_thn_mulai,true);
		$criteria->compare('beasiswa_03_thn_selesai',$this->beasiswa_03_thn_selesai,true);
		$criteria->compare('beasiswa_04_jenis',$this->beasiswa_04_jenis,true);
		$criteria->compare('beasiswa_04_sumber',$this->beasiswa_04_sumber,true);
		$criteria->compare('beasiswa_04_thn_mulai',$this->beasiswa_04_thn_mulai,true);
		$criteria->compare('beasiswa_04_thn_selesai',$this->beasiswa_04_thn_selesai,true);
		$criteria->compare('peminatan',$this->peminatan,true);
		$criteria->compare('lintas_minat_01',$this->lintas_minat_01,true);
		$criteria->compare('lintas_minat_02',$this->lintas_minat_02,true);
		$criteria->compare('ekskul_01',$this->ekskul_01,true);
		$criteria->compare('ekskul_02',$this->ekskul_02,true);
		$critria->compare('status_keluarga',$this->status_keluarga,true);
		$critria->compare('anak_ke',$this->anak_ke,true);
		$critria->compare('sekolah_asal',$this->sekolah_asal,true);
		$critria->compare('kelas_diterima',$this->kelas_diterima,true);
		$critria->compare('tanggal_diterima',$this->tanggal_diterima,true);
		$critria->compare('alamat_ortu',$this->alamat_ortu,true);
		$critria->compare('no_telp_ortu',$this->no_telp_ortu,true);
		$critria->compare('alamat_wali',$this->alamat_wali,true);
		$critria->compare('no_telp_wali',$this->no_telp_wali,true);
		$critria->compare('pekerjaan_wali',$this->pekerjaan_wali,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return UserProfile the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
