<?php
class CrudSiswaController extends Controller{
    public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		
		);
    }
    public function actionAdmin()
	{
		$model=new CrudSiswa('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['CrudSiswa']))
			$model->attributes=$_GET['CrudSiswa'];

		$this->render('admin',array(
			'model'=>$model,
		));
    }
    protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='siswa-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
    public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index','view'),
				'users'=>array('admin'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('tambahcrudsiswa','edit'),
				'users'=>array('admin'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}
    public function actionIndex()
    {
        $data = CrudSiswa::model()->findAll();
        $l = CrudSiswa::model()->findAllByAttributes(array(
            'jenis_kelamin'=> 'L'
        ));
        $p = CrudSiswa::model()->findAllByAttributes(array(
            'jenis_kelamin'=> 'P'
        ));
        $laki = count($l);
        $perempuan = count($p);
        $this->render('crudsiswa', array('data' => $data,'laki'=>$laki,'perempuan'=> $perempuan));
    }
    public function actionTambahCrudSiswa()
    {
        $modelCrudSiswa = new CrudSiswa;
        if(isset($_POST['CrudSiswa'])){
            $modelCrudSiswa->nama_siswa = $_POST['CrudSiswa']['nama_siswa'];
            $modelCrudSiswa->alamat = $_POST['CrudSiswa']['alamat'];
            $modelCrudSiswa->jenis_kelamin = $_POST['CrudSiswa']['jenis_kelamin'];
            $modelCrudSiswa->tgl_lahir = $_POST['CrudSiswa']['tgl_lahir'];
            if($modelCrudSiswa->save()){
                $this->redirect(array('/crudsiswa/index'));
            }
        }
        $this->render('tambahcrudsiswa', array('model' =>$modelCrudSiswa));
    }
    public function actionEdit($id)
    {   
        $modelCrudSiswa = CrudSiswa::model()->findByPk($id);
        if(isset($_POST['CrudSiswa'])){
            $modelCrudSiswa->nama_siswa = $_POST['CrudSiswa']['nama_siswa'];
            $modelCrudSiswa->alamat = $_POST['CrudSiswa']['alamat'];
            $modelCrudSiswa->jenis_kelamin = $_POST['CrudSiswa']['jenis_kelamin'];
            $modelCrudSiswa->tgl_lahir = $_POST['CrudSiswa']['tgl_lahir'];
            if($modelCrudSiswa->save()){
                $this->redirect(array('/crudsiswa/index'));
            }
        }
        $this->render('editcrudsiswa', array('model' =>$modelCrudSiswa));
    }
    public function actionDelete($id)
    {
        if(CrudSiswa::model()->deleteByPk($id)){
            $this->redirect(array('/crudsiswa/index'));
        } else {
            throw new CHttpException(404, 'Data gagal dihapus');
        }
    }
    
}
?>