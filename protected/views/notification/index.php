<?php
/* @var $this NotificationController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Notifikasi',
);

$this->menu=array(
	array('label'=>'Create Notification', 'url'=>array('create')),
	array('label'=>'Manage Notification', 'url'=>array('admin')),
);
?>

<h1>Notifikasi</h1>

<?php 
// $this->widget('zii.widgets.CListView', array(
// 	'dataProvider'=>$dataProvider,
// 	'itemView'=>'_view',
// )); 

$asd=$dataProvider->getData();
echo "<div class='table-responsive'>";
echo "<table class='table table-hover'>";
echo "<th>ID</th>";
echo "<th>Content</th>";
echo "<th>Dari</th>";
echo "<th>Untuk</th>";
if (!Yii::app()->user->YiiTeacher and !Yii::app()->user->YiiStudent) {
echo "<th>Status</th>";
echo "<th>Aksi</th>";
}
foreach ($asd as $key) {

	$id = $key->id;
	$user_id = $key->user_id;
	$user_dp = $key->user->display_name;
	if (!empty($key->user_id_to)){
		$user_dp_to = $key->user_to->display_name;
		$user_id_to = $key->user_id_to;
	} elseif (!empty($key->class_id_to)) {
		$user_dp_to = $key->class_to->name;
		$user_id_to = $key->class_id_to;
	} else {
		$user_dp_to = 'Kosong';
		$user_id_to = 'Kosong';
	}
	$content = $key->content;
	$status = $key->status;
	$tipe = $key->tipe;
	$relation_id = $key->relation_id;
	$read_at = $key->read_at;



	if (empty($status)){
		$status = "Not Respon";
	}


	if ($tipe == 'submit-question' and !empty($relation_id)) {
		$link_url = Yii::app()->createUrl('StudentAssignment/view', array('id'=>$relation_id));
	} elseif ($tipe == 'mark-question' and !empty($relation_id)) {
		$link_url = Yii::app()->createUrl('StudentAssignment/view', array('id'=>$relation_id));
	} elseif ($tipe == 'assignment') {
		$link_url = Yii::app()->createUrl('assignment/view', array('id'=>$relation_id));
	} elseif ($tipe == 'add-chapter') {
		$link_url = Yii::app()->createUrl('chapters/view', array('id'=>$relation_id));
	} elseif ($tipe == 'submit-quiz') {
		$link_url = Yii::app()->createUrl('studentQuiz/view', array('id'=>$relation_id));
	} elseif ($tipe == 'quiz') {
		$link_url = Yii::app()->createUrl('quiz/view', array('id'=>$relation_id));
	} elseif ($tipe == 'correction') {
		$link_url = Yii::app()->createUrl('assignment/view', array('id'=>$relation_id));
	} elseif ($tipe == 'announcement') {
		$link_url = Yii::app()->createUrl('announcement/view', array('id'=>$relation_id));		
	} else {
		$link_url = null;
	}

	if (!empty($link_url)){
		$content = "<a href='$link_url'>$content</a>";
	}

	echo "<tr>";
	echo "<td>$id</td>";
	echo "<td>$content</td>";
	echo "<td><a href=".Yii::app()->request->baseUrl."/user/$user_id>$user_dp</a></td>";
	echo "<td>$user_dp_to</td>";
	if (!Yii::app()->user->YiiTeacher and !Yii::app()->user->YiiStudent) {
		echo "<td>$status</td>";
		echo "<td><a href=".Yii::app()->request->baseUrl."/notification/gene?user_id=$user_id&notif_id=$id>Respon</a></td>";
	}
	if (empty($read_at)){
		echo "<td>new</td>";
	}
	echo "</tr>";
	

}

echo "</table>";
echo "</div>";
$current_user = Yii::app()->user->id;
$prefix = Yii::app()->params['tablePrefix'];

$sql='UPDATE '.$prefix.'notification set read_at = now() where user_id_to = :id and read_at is null';
$command=Yii::app()->db->createCommand($sql);
$command->bindParam(":id", $current_user, PDO::PARAM_STR);
$command->execute();

$modelUser=User::model()->findByPk($current_user);
$class_student_id = $modelUser->class_id;

$current_user_str = ','.$current_user.',';

$sql="UPDATE ".$prefix."notification set read_id = CONCAT(:current_user_str, read_id), read_at = now() where class_id_to = :id and read_id not like '%,$current_user,%' ";
$command=Yii::app()->db->createCommand($sql);
$command->bindParam(":id", $class_student_id, PDO::PARAM_STR);
$command->bindParam(":current_user_str", $current_user_str, PDO::PARAM_STR);
$command->execute();





?>
