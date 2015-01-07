<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 21.07.14
 * Time: 14:37
 * Comment: Yep, it's magic
 */

/**
 * @property string $title
 * @property string $text
 * @property string $material_class
 * @property integer $material_id
 */
class Search extends CActiveRecord {

    const CLASS_CLUB = 'club';
    const CLASS_SHOP = 'shop';
    const CLASS_PROGRAM = 'program';
    const CLASS_COACH = 'coach';
    const CLASS_EXERCISE = 'exercise';
    const CLASS_ARTICLE = 'article';
    const CLASS_BOOK = 'book';

    const TITLE_CLASS_CLUB = 'Фитнес-клубы';
    const TITLE_CLASS_SHOP = 'Магазины';
    const TITLE_CLASS_PROGRAM = 'Программы';
    const TITLE_CLASS_COACH = 'Тренера';
    const TITLE_CLASS_EXERCISE = 'Упражнения';
    const TITLE_CLASS_ARTICLE = 'Статьи';
    const TITLE_CLASS_BOOK = 'Книги';

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const PAGINATION_COUNT_ITEMS = 12;

    private $_material;

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function tableName() {
        return 'view_search';
    }

    public function getMaterial() {
        if ($this->_material === null){
            $this->_material = CActiveRecord::model($this->material_class)->findByPk($this->material_id);
        }

        return $this->_material;
    }
    public static function getClassList() {
        return array(
            self::CLASS_CLUB => self::TITLE_CLASS_CLUB,
            self::CLASS_SHOP => self::TITLE_CLASS_SHOP,
            self::CLASS_PROGRAM => self::TITLE_CLASS_PROGRAM,
            self::CLASS_COACH => self::TITLE_CLASS_COACH,
            self::CLASS_EXERCISE => self::TITLE_CLASS_EXERCISE,
            self::CLASS_ARTICLE => self::TITLE_CLASS_ARTICLE,
            self::CLASS_BOOK => self::TITLE_CLASS_BOOK,
        );
    }

    public function fetchSearchItems($query = null, $class = null) {
        $criteria = new CDbCriteria();
        $list = self::getClassList();
        $criteria->addSearchCondition('title', $query);
        $criteria->addSearchCondition('short_description', $query, true, 'OR');
        $criteria->addSearchCondition('description', $query, true, 'OR');
        $criteria->order = 'sort_field';
        $criteria->addCondition('status = :status');
        if ($class && isset($list[$class])) {
            $criteria->addCondition('material_class = :class');
            $criteria->params = array(':class' => $class) + $criteria->params;
        }
        $criteria->params = array(':status' => self::STATUS_ACTIVE) + $criteria->params;

        return new CActiveDataProvider('Search', array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => self::PAGINATION_COUNT_ITEMS,
                'pageVar' => 'page'
            )
        ));
    }
}