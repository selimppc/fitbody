<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/13/12
 * Time: 1:32 PM
 * To change this template use File | Settings | File Templates.
 */
class PagerRus extends Pager {

    public function run() {
        $this->maxButtonCount = 5;
        $this->nextPageLabel = 'Следующая';
        $this->lastPageLabel = 'Последняя';
        $this->prevPageLabel = 'Предыдущая';
        $this->firstPageLabel = 'Первая';
        $buttons=$this->createPageButtons();
//		Debug::print_die($buttons);
        if(empty($buttons))
            return;

        $this->draw($buttons);
    }
}
