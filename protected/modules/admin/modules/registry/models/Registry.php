<?php
/**
 * @property int parent_category_id
 * @property int currentFolderId
 * @property RegistryValue value
 */
class Registry extends CActiveRecord {

	protected $child = array();

	private $currentFolderId;
	
	private $assetUrl = '';

	/**
	 * Returns the static model of the specified AR class.
	 *
	 * @param string $className active record class name.
	 *
	 * @return Registry the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'registry';
	}

	/**
	 * relations
	 * @return array
	 */
	public function relations()
	{
		return array(
			'value' => array(self::HAS_MANY,'RegistryValue',array('registry_id'=>'id'),'condition'=>'old = 0')
		);
	}

	/**
	 * get tree all folders
	 *
	 * @param null $id
	 * @return string
	 */
	public function getFolders($id = null) {
		$criteria = new CDbCriteria();
		$criteria->alias = 'r';
		$criteria->order = 'parent_category_id ASC';
		$criteria->condition = '`type` = "folder"';
		$data = $this->findAll($criteria);
		$tree = $this->bindTree($data);
		return $this->getHtmlTree($tree,$id);
	}

	/**
	 * @param $array
	 * @param int $id
	 * @return array
	 */
	private function bindTree($array, $id = 0)
	{
		$out = array();
		foreach($array as $value) {
			if($value->parent_category_id == $id) {
				$value->child = $this->bindTree($array,$value->id);
				$out[] = $value;
			}
		}
		return $out;
	}

	/**
	 * generate tree html
	 * @param $data
	 * @param $id
	 * @param string $url
	 * @param int $cnt
	 * @return string
	 */
	private function getHtmlTree($data,$id,$url = '/admin/registry/data/folder',$cnt = 1)
	{
		$newCnt = $cnt+1;
		$html = '';
		if(count($data)) {
			$html = '<ul class="list_a chat_user_list">';
			foreach($data as $val) {
				$newUrl = $url.'/'.$val->key;
				$html .= '
				<li class="'.(($id == $val->id)?'list_b':'').'">'.
						'<a href="'.$newUrl.'.html">'.$val->title.'</a>'.
						'<span class="pull-right">';
				if(Yii::app()->user->role >= Yii::app()->static->accessLevelEdit)
					$html .= '<a class="add_folder_a" data-parent-id="'.$val->id.'" href=""><i class="splashy-folder_classic_add"></i></a>&nbsp;<a class="delete_folder_a" data-parent-id="'.$val->id.'" href=""><i class="splashy-folder_classic_remove"></i></a>';
				$html .='</span>'.
						$this->getHtmlTree($val->child,$id,$newUrl,$newCnt).
				'</li>';
			}
			$html .= '</ul>';
		}
		return $html;
	}

	/**
	 * get folder id by url
	 * if @var $url === null - $url = current_url
	 *
	 * @param null $url
	 * @param $return
	 * @return mixed|null
	 * @throws CHttpException
	 */
	public function getCurrentFolderId($url = null, $return = false)
	{
		$keys = explode('/',preg_replace(array('/.*\/admin\/registry\/data\/folder\//','/\.html.*/','/\?.*/'),'',($url===null)?Yii::app()->getRequest()->getRequestUri():$url));
		$parentId = null;
		foreach($keys as $key) {
			$criteria = new CDbCriteria();
			if($parentId === null)
				$criteria->condition = 'parent_category_id IS NULL ';
			else
				$criteria->condition = 'parent_category_id = '.$parentId.' ';
			$criteria->condition .= 'AND `key` = :key_folder';
			$criteria->params = array(
				':key_folder'=>$key
			);
			$data = $this->find($criteria);
			if($data===null)
			{
				if($return)
					return null;
				else
					throw new CHttpException(404,'Page nod found');
			}
			$parentId = $data->id;
		}
		return $parentId;
	}

	/**
	 * get all elements by folder id
	 *
	 * @param $folderId
	 * @return CActiveRecord[]
	 */
	public function getElements($folderId)
	{
		return $this->findAll('parent_category_id = :parent AND type != "folder"', array(
			':parent' => $folderId
		));
	}

	public function getFullPathById($id)
	{
		$data = $this->findByPk($id);
		$path = (($data->parent_category_id !== null) ? $this->getFullPathById($data->parent_category_id) : '').(($data->type != 'folder')?'':'/'.$data->key);
		if($path == '')
			$path = '/';
		return $path;
	}

	public function getTypeList()
	{
		return array(
			'text' => 'Text',
			'textarea' => 'Textarea',
			'date' => 'Date',
			'email' => 'Email',
			'redactor' => 'Redactor',
			'image' => 'Image',
		);
	}

	public function getTypeListKeys()
	{
		return array(
			'text',
			'textarea',
			'date',
			'email',
			'redactor',
			'image',
		);
	}

	public function getParentIds($id)
	{
		$return = array();
		$data = $this->findByPk($id);
		if($data == null)
			return $return;
		$return[] = $data->id;
		if($data->parent_category_id !== null) {
			$other = $this->getParentIds($data->parent_category_id);
			foreach($other as $val)
				$return[] = $val;
		}
		return $return;
	}

	public function save($runValidation=true,$attributes=null,$create_migration=true)
	{
		$isNew = $this->isNewRecord;
		$result = parent::save($runValidation,$attributes);
		if($create_migration && $result)
		{
			$params = array(
				'id' => $this->id,
				'title' => $this->title,
				'key' => $this->key,
				'type' => $this->type,
				'create_date' => $this->create_date,
				'parent_category_id' => $this->parent_category_id,
				'is_new' => $isNew
			);
			Yii::app()->static->createUpdateOrAddMigration('Registry',$params);
		}
		return $result;
	}

	public function deleteByPk($pk,$condition='',$params=array(),$create_migration=true)
	{
		if($create_migration)
			Yii::app()->static->createDeleteMigration('Registry',$pk);
		parent::deleteByPk($pk,$condition,$params);
		$data = $this->findAll('parent_category_id=:id',array(':id'=>$pk));
		foreach($data as $val)
			$this->deleteByPk($val->id,'',array(),false);
	}

	public function getPath()
	{
		return preg_replace('/^\//','',$this->getFullPathById($this->id)).'/'.$this->key;
	}
}