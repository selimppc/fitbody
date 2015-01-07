<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 14.10.13
 * Time: 16:46
 * To change this template use File | Settings | File Templates.
 */
class Seo extends CActiveRecord {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'seo';
	}

	public function rules() {
		return array(
			array('title,uri,is_auto_generation','required'),
			array('title','length','min'=>3,'max'=>'225'),
			array('keywords','length','max'=>'225'),
			array('description','length','max'=>'1000'),
		);
	}

	/**
	 * get seo data for current requestUri
	 * if for current uri not exists record, create new record
	 *
	 * @return array
	 */
	public function getSeoData() {
		$uri = Yii::app()->request->requestUri;
		$data = $this->model()->find('uri LIKE :uri',array(':uri'=>$uri));
		if($data === null)
			$data = $this->addNew($uri);
		return array(
			'title'         => $data->title,
			'description'   => $data->description,
			'keywords'      => $data->keywords
		);
	}

	/**
	 * add new row
	 * @param $uri
	 * @return Seo
	 * @throws CException
	 */
	private function addNew($uri) {
		$indexData = $this->model()->find('uri LIKE :uri',array(':uri'=>'/'));
		if($indexData === null)
			throw new CException('No record for the index page in seo table');
		$new = new Seo;
		$new->is_auto_generation    = 1;
		$new->title                 = $indexData->title;
		$new->keywords              = $indexData->keywords;
		$new->description           = $indexData->description;
		$new->uri                   = $uri;
		$new->save();
		return $new;
	}
}