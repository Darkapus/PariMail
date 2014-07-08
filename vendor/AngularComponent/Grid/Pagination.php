<?php
namespace AngularComponent\Grid;
use AngularComponent\Core\Element;
//<pagination total-items="bigTotalItems" ng-model="bigCurrentPage" max-size="maxSize" class="pagination-sm" boundary-links="true" rotate="false" num-pages="numPages"></pagination>
class Pagination extends Element{
    public function __construct($total, $current=1, $nb_max=5, $id=null){
        $this->setDom('pagination');
        $this->addAttribute('total-items', $total);     // nombre total d'items
        $this->addAttribute('ng-model', $current);      // page courante
        $this->addAttribute('max-size', $nb_max);       // set le nombre total de chiffre presents
        //$this->addAttribute('num-pages', $nb_pages);    // set le nombre de pages
        $this->addClass('pagination-sm');
        $this->addAttribute('boundary-links', 'true');
        $this->addAttribute('rotate', 'false');
        parent::__construct($id);
    }
}