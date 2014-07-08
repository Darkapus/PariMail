<?php
namespace AngularComponent\Grid;

use \AngularComponent\Core\Element;
use \AngularComponent\Core\Behavior;
use \AngularComponent\Panel\Container;
class Grid extends Container{
    /**
     * 
     */
    protected $headers      = array();
    protected $storage      = null;
    protected $hasHeader    = true;
    protected $order        = null;
    protected $paging       = 10;
    
    public function hasHeader(){
        return $this->hasHeader;
    }
    /**
     * Storage = scope
     * donc obligatoire, il n'est donc pas necessaire de preciser ici le controller, il prendra automatiquement celui du storage
     */
    public function __construct(\AngularComponent\Core\Storage $storage, $title=null, $controller=null, $id=null){
        //$this->getController()->addChild(new Behavior('$scope={}'));
        parent::__construct($title, $id);
        $this->setStorage($storage);
        !is_null($controller) && $this->setController($controller);
        //$this->setController($controller = new \AngularComponent\Core\Controller($storage->getName()));
        $this->addClass("table-responsive");
        $this->getController()->addChild(new Behavior('$scope.'.$this->getStorage()->getName().'={}'));
        $this->getController()->addChild($this->getStorage());
        $this->getController()->addChild(new Behavior('$scope.'.$this->getStorage()->getName().'.sort={}'));
        $this->getController()->addChild(new Behavior('$scope.'.$this->getStorage()->getName().'.unselectAll = function(){for(id in $scope.'.$this->getStorage()->getName().'.data){ $scope.'.$this->getStorage()->getName().'.data[id].class=false; } return true;}'));
        $this->getController()->addChild(new Behavior('$scope.'.$this->getStorage()->getName().'.update = function(index){console.log($scope.data); $scope.'.$this->getStorage()->getName().'.data[index]=$scope.data; }'));
        $this->getController()->addChild(new Behavior('$scope.'.$this->getStorage()->getName().'.updateAndRefresh = function(index, data){ $scope.'.$this->getStorage()->getName().'.update(index, data); $scope.'.$this->getStorage()->getName().'.unselectAll(); }'));
        $this->getController()->addChild(new Behavior('$scope.'.$this->getStorage()->getName().'.header={}'));
        $this->getController()->addChild(new Behavior('$scope.'.$this->getStorage()->getName().'.start=1'));
        
        if(method_exists($this->getStorage(), 'getUrl')){
            $content = '$scope.'.$this->getStorage()->getName().'.reload = function(params){$http({url: "'.$this->getStorage()->getUrl().'", method: "'.$this->getStorage()->getMethod().'", data: params}).success(function(data) { angular.element(\'#'.$this.'\').scope().'.$this->getStorage()->getName().'.data = data;});}';
            $this->getController()->addChild(new Behavior($content));
        }
    }
    public function bReload($params){
        return new Behavior('getScope(\''.$this.'\').'.$this->getStorage()->getName().'.reload('.$params.')');
    }
    public function setOrder($order){
        $this->order = $order;
        return $this;
    }
    public function getOrder(){
        return $this->order;
    }
    public function getStorage(){
        return $this->storage;
    }
    /**
     * Storage Needed. It is the data strategy + data
     * @return Grid
     */
    public function setStorage(\AngularComponent\Core\Storage $storage){
        $this->storage = $storage;
        return $this;
    }
    
	
	
    /**
     * Add an object header for data binding
     * @return Grid
     * */
    public function addHeader(Header $header){
        array_push($this->headers, $header);
        return $this;
    }
    public function getHeaders(){
        return $this->headers;
    }
    /**
     * Add an header for data binding
     * @Return Header
     * */
    public function addColumn($label, $index, $width=null){
        $this->addHeader($header = new Header($this->getStorage(), $label, $index, $width));
        return $header;
    }
    
    
    public function preRender(){
        
        $all_style = '';
        $o = new \stdClass();
        
        foreach($this->getHeaders() as $header){
            $name       = $header->getIndex();
            $o->$name   = $header->getWidth();
            //$this->getController()->addChild(new Behavior('$scope.header.'.$header->getIndex().'='.$header->getWidth()));
            //if($all_style) $all_style .= '+';
            //$all_style .= '$scope.header.'.$header->getIndex();
        }
        $this->getController()->addChild(new Behavior('$scope.'.$this->getStorage()->getName().'.header='.json_encode($o)));
        
        //$this->getController()->addChild(new Behavior('$scope.'.$this.'='.$all_style));
        
        //$this->addAttribute('ng-style', '{width: '.$this.'}');
        
        parent::preRender();
	    echo '<table class="table table-hover unselectable" unselectable="on">'.RC;
	    if($this->hasHeader()){
	        echo '<thead>'.RC;
	        echo '<tr>'.RC;
	        foreach($this->getHeaders() as $header){
    	        echo '<th id="'.$header->getId().'" class="ac-table-header-cell" ng-style="{width: '.$this->getStorage()->getName().'.header.'.$header->getIndex().'}" ng-click="'.$this->getStorage()->getName().'.sort.column=\''.$header->getIndex().'\';'.$this->getStorage()->getName().'.sort.descending=!'.$this->getStorage()->getName().'.sort.descending;">'.RC;
    	        echo '<div ng-class="{\'ac-sort-up\':'.$this->getStorage()->getName().'.sort.column==\''.$header->getIndex().'\'&&'.$this->getStorage()->getName().'.sort.descending, \'ac-sort-down\':'.$this->getStorage()->getName().'.sort.column==\''.$header->getIndex().'\'&&!'.$this->getStorage()->getName().'.sort.descending}"></div>';
    	        echo ''.$header->getLabel().'';
    	        echo '</th>'.RC; 
	        }
	        echo '</tr>'.RC;
    	    echo '</thead>'.RC;
	    }
	    return $this;
	}
	protected $rowClick = '';
	public function onRowClick(\AngularComponent\Core\Behavior $behavior){
	    $this->rowClick = $behavior;
        return $this;
	}
	public function getRowClick(){
	    return $this->rowClick;
	}
	protected $dragStyle;
	public function getDragStyle(){
	    return $this->dragStyle;
	}
	public function setDragStyle($style){
	    $this->dragStyle = $style;
	    return $this;
	}
	protected $dropStyle;
	public function getDropStyle(){
	    return $this->dropStyle;
	}
	
	public function setDropStyle($style){
	    $this->dropStyle = $style;
	    return $this;
	}
	
    protected $selectable = true;
    public  function setSelectable($bool){
        $this->selectable = $bool;
        return $this;
    }
	public function getSelectable(){
	    return $this->selectable;
	}
	
	public function setPaging($paging){
	    $this->paging = $paging;
	    return $this;
	}
	public function getPaging(){
	    return $this->paging;
	}
	public function render(){
	    parent::render();
	    echo '<tbody class="ac-table-body">'.RC;
	    echo '<tr class="ac-table-body-row" ';
	    if($this->getDragStyle()){
	        echo 'drag="row" dragStyle="'.$this->getDragStyle().'" ';
	    }
	    if($this->getDropStyle()){
	        echo 'drop="row" dropStyle="'.$this->getDropStyle().'" ';
	    }
	    
	    echo 'ng-repeat="row in '.$this->getStorage()->getName().'.data ';
	    echo ' | orderBy:'.$this->getStorage()->getName().'.sort.column:'.$this->getStorage()->getName().'.sort.descending | slice: '.$this->getPaging().'*('.$this->getStorage()->getName().'.start-1):'.$this->getPaging().'*('.$this->getStorage()->getName().'.start)';
	    
	    echo '" ng-class="{';
	    if($this->getSelectable()){
	        echo '\'selected\': row.class,';
	    }
	    if($rowclass = $this->getRowClass()){
	        echo $rowclass;
	    }
	    echo'}" ng-click="'.$this->getStorage()->getName().'.unselectAll();row.class=!row.class;';
	    if($this->getRowClick()){
	        echo ''.$this->getRowClick()->getContent();
	    }
	    echo '">'.RC;
	    
	    //$this->getController()->addChild(new Behavior(''));
	    
	    foreach($this->getHeaders() as $header){
	        $header->preRender();
	        $header->render();
	        $header->postRender();
	    }
	    echo '</tr>'.RC;
	    echo "</tbody>".RC;
	    return $this;
	}
	public function postRender(){
	    echo '</table>'.RC;
	    
	    // Ajout de la pagination ici
	    $container = new Container();
	    $container->addStyle('text-align', 'center');
	    $container->addChild($paging = new Pagination($this->getStorage()->getName().'.data.length', $this->getStorage()->getName().'.start'));
	    
	    $container->preRender();
	    $container->render();
	    $container->postRender();
	    
	    return parent::postRender();
	}
	
	protected $rowClass = '';
	public function setRowClass($class){
	    $this->rowClass = $class;
	    return $this;
	}
	public function getRowClass(){
	    return $this->rowClass;
	}
}
