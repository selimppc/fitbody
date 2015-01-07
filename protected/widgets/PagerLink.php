<?php



class PagerLink extends CLinkPager {

    const OFFSET_PAGES = 3;

    public $nextPageLabel = 'Вперед';
    public $prevPageLabel = 'Назад';
    public $previousPageCssClass = 'prev fl_l';
    public $nextPageCssClass = 'next fl_r';
    public $selectedPageCssClass = 'active';
    public $htmlOptions = array('class' => 'pagination clearfix', 'id' => 'paginator');
    public $header = '';


    public function init() {
        parent::init();
    }

    public function run() {
        $this->registerClientScript();

        if(($pageCount = $this->getPageCount()) <= 1)
            return array();

        list($beginPage, $endPage) = $this->getPageRange();

        // currentPage is calculated in getPageRange()
        $currentPage = $this->getCurrentPage(false);

        $buttons = $this->createPageButtons($pageCount, $currentPage, $beginPage, $endPage);

        if(empty($buttons))
            return;

        $html = '';
        $html .= $this->header;

        $html .= CHtml::tag('ul', $this->htmlOptions, implode("\n", $buttons));

        $html .= $this->footer;

        echo $html;
    }

    protected function createPageButtons($pageCount, $currentPage, $beginPage, $endPage) {

        $buttons = array();

        if ($currentPage > 0) {
            $buttons[] = '<li class="'.$this->previousPageCssClass.'" >'.CHtml::link($this->prevPageLabel, $this->createPageUrl($currentPage - 1), array()).'</li>';
        }

        if ($endPage > $currentPage) {
            $buttons[] = '<li class="'.$this->nextPageCssClass.'" >'.CHtml::link($this->nextPageLabel,$this->createPageUrl($currentPage + 1), array('class' => $this->nextPageCssClass)).'</li>';
        }

        if ($currentPage > self::OFFSET_PAGES) {
            // first page
            $buttons[]=$this->createPageButton(1, 0 , $this->firstPageCssClass, $currentPage<=0, false);
        }

        if (self::OFFSET_PAGES + 1 < $currentPage) {
            $buttons[] = $this->createDottedLine('...');
        }

        if (($currentPage - self::OFFSET_PAGES) < 0) {
            $from = 0;
        } else {
            $from = $currentPage - self::OFFSET_PAGES;
        }

        if ($currentPage + self::OFFSET_PAGES >  $endPage) {
            $to = $endPage;
        } else {
            $to = $currentPage + self::OFFSET_PAGES;
        }

        // internal pages
        for($i = $from; $i <= $to; ++$i) {
            $buttons[] = $this->createPageButton($i + 1, $i, $this->internalPageCssClass, false, $i == $currentPage);
        }

        if (self::OFFSET_PAGES + 1 < ($endPage - $currentPage)) {
            $buttons[] = $this->createDottedLine('...','dots');
        }

        if (($endPage - $currentPage) > self::OFFSET_PAGES) {
            // last page
            $buttons[]=$this->createPageButton($pageCount, $pageCount-1, $this->lastPageCssClass, $currentPage>=$pageCount-1,false);
        }

        return $buttons;
    }

    protected function createPageButton($label,$page,$class,$hidden,$selected) {
        if($hidden || $selected)
            $class.=' '.($hidden ? $this->hiddenPageCssClass : $this->selectedPageCssClass);
        return '<li class="'.($selected ? $this->selectedPageCssClass : "").'">'.CHtml::link($label,$this->createPageUrl($page) , array('class' => $class)).'</li>';
    }

    protected function createDottedLine($label, $class = '', $hidden = '') {

        if($hidden) {
            $class.=' '.($hidden ? $this->hiddenPageCssClass : '');
        }

        return '<li class="dots">' . CHtml::link($label,"javascript:void(0)", array()) . '</li>';
    }
}