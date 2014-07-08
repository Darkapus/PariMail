<?php
namespace AngularComponent\Core;

class Application extends Object{
	
    protected $controllers = array();
    protected $routes = null;
    public function addController(Controller $controller){
        array_push($this->controllers, $controller);
        return $this;
    }
    public function getControllers(){
        return $this->controllers;
    }
    /*public function preRender(){
        echo '<script>';
        foreach($this->getControllers() as $controller){
        	if(!is_null($controller->getRoute())){
        		$o = new \stdClass();
        		$o->templateUrl= $controller->getRoute();
          		$o->controller= $controller.'';
          		$o->controllerAs= $controller.'';
				$this->routes = $o;          		
        	}
        }
    }*/
    public function preRender(){
        
    }
    
    protected $startSymbol = "[[";
    public function setStartSymbol($start){
        $this->startSymbol = $start;
        return $this;
    }
    public function getStartSymbol(){
        return $this->startSymbol;
    }
    protected $endSymbol = "]]";
    public function setEndSymbol($end){
        $this->endSymbol = $end;
        return $this;
    }
    public function getEndSymbol(){
        return $this->endSymbol;
    }
    
    public function render(){
        echo 'var '.$this.' = angular.module("'.$this.'", [\'ui.bootstrap\', \'angular-loading-bar\']);'.RC;
        echo $this.'.config(function($interpolateProvider) {
          $interpolateProvider.startSymbol("'.$this->getStartSymbol().'");
          $interpolateProvider.endSymbol("'.$this->getEndSymbol().'");
        });'.RC;
        
        echo $this.'.directive(\'ngDraggable\', [\'$document\', function($document) {
            return function(scope, element, attr) {
              var startX = 0, startY = 0, x = 0, y = 0;
        
              element.on(\'mousedown\', function(event) {
              
                // Prevent default dragging of selected content
                event.preventDefault();
                startX = event.pageX - x;
                startY = event.pageY - y;
                $document.on(\'mousemove\', mousemove);
                $document.on(\'mouseup\', mouseup);
              });
        
              function mousemove(event) {
                y = event.pageY - startY;
                x = event.pageX - startX;
                element.parent().css({
                  top: y + \'px\',
                  left:  x + \'px\'
                });
              }
        
              function mouseup() {
                $document.off(\'mousemove\', mousemove);
                $document.off(\'mouseup\', mouseup);
              }
            };
          }]);';
        
        echo $this.'.directive("drag", ["$rootScope", function($rootScope) {
  
          function dragStart(evt, element, dragStyle) {
            element.addClass(dragStyle);
            //evt.dataTransfer.setData("id", evt.target.id);
            //evt.dataTransfer.effectAllowed = \'move\';
          };
          function dragEnd(evt, element, dragStyle) {
            element.removeClass(dragStyle);
          };
          
          return {
            restrict: \'A\',
            link: function(scope, element, attrs)  {
              attrs.$set(\'draggable\', \'true\');
              scope.dragData = scope[attrs["drag"]];
              scope.dragStyle = attrs["dragstyle"];
              element.bind(\'dragstart\', function(evt) {
               	$rootScope.draggedElement = scope.dragData;
                dragStart(evt, element, scope.dragStyle);
              });
              element.bind(\'dragend\', function(evt) {
                dragEnd(evt, element, scope.dragStyle);
              });
            }
          }
        }]);';
        echo $this.'.directive("window",[function(){
            return {
                restrict: "E",
                template: "<div></div>"
            }
        }]);
        
        ';
        echo $this.'.directive("drop", [\'$rootScope\', function($rootScope) {
  
          function dragEnter(evt, element, dropStyle) {
            evt.preventDefault();
            element.addClass(dropStyle);
          };
          function dragLeave(evt, element, dropStyle) {
            element.removeClass(dropStyle);
          };
          function dragOver(evt) {
            evt.preventDefault();
          };
          function drop(evt, element, dropStyle) {
            evt.preventDefault();
            element.removeClass(dropStyle);
          };
          
          return {
            restrict: \'A\',
            link: function(scope, element, attrs)  {
              scope.dropData = scope[attrs["drop"]];
              scope.dropStyle = attrs["dropstyle"];
              element.bind(\'dragenter\', function(evt) {
                dragEnter(evt, element, scope.dropStyle);
              });
              element.bind(\'dragleave\', function(evt) {
                dragLeave(evt, element, scope.dropStyle);
              });
              element.bind(\'dragover\', dragOver);
              element.bind(\'drop\', function(evt) {
                drop(evt, element, scope.dropStyle);
                $rootScope.$broadcast(\'dropEvent\', $rootScope.draggedElement, scope.dropData);
              });
            }
          }
        }]);';
        
        
        echo $this.'.directive(\'ngEnter\', function () {
            return function (scope, element, attrs) {
                element.bind("keydown keypress", function (event) {
                    if(event.which === 13) {
                        scope.$apply(function (){
                            scope.$eval(attrs.ngEnter);
                        });
        
                        event.preventDefault();
                    }
                });
            };
        });';
        
        echo $this.'.directive(\'resizer\', function($document) {

            return function($scope, $element, $attrs) {
        
                $element.on(\'mousedown\', function(event) {
                    event.preventDefault();
        
                    $document.on(\'mousemove\', mousemove);
                    $document.on(\'mouseup\', mouseup);
                });
        
                function mousemove(event) {
        
                    if ($attrs.resizer == \'vertical\') {
                        // Handle vertical resizer
                        var x = event.pageX;
        
                        if ($attrs.resizerMax && x > $attrs.resizerMax) {
                            x = parseInt($attrs.resizerMax);
                        }
        
                        $element.css({
                            left: x + \'px\'
                        });
        
                        $($attrs.resizerLeft).css({
                            width: x + \'px\'
                        });
                        $($attrs.resizerRight).css({
                            left: (x + parseInt($attrs.resizerWidth)) + \'px\'
                        });
        
                    } else {
                        // Handle horizontal resizer
                        var y = window.innerHeight - event.pageY;
        
                        $element.css({
                            bottom: y + \'px\'
                        });
        
                        $($attrs.resizerTop).css({
                            bottom: (y + parseInt($attrs.resizerHeight)) + \'px\'
                        });
                        $($attrs.resizerBottom).css({
                            height: y + \'px\'
                        });
                    }
                }
        
                function mouseup() {
                    $document.unbind(\'mousemove\', mousemove);
                    $document.unbind(\'mouseup\', mouseup);
                }
            };
        });';
        
        echo $this.'.filter(\'slice\', function() {
          return function(arr, start, end) {
            return (arr || []).slice(start, end);
          };
        });';
        foreach($this->getControllers() as $controller){
            echo $this.'.controller("'.$controller.'"';
            $controller->preRender();
            $controller->render();
            $controller->postRender();
            echo ');'.RC;
        }
        
        foreach($this->getChilds() as $child){
            $child->preRender();
            $child->render();
            $child->postRender();
        }
        /*
        if(count($this->routes)){
        	echo $this.'.config(["$routeProvider","$locationProvider", function($routeProvider, $locationProvider){';
        	echo '$routeProvider';
        	foreach($this->getControllers() as $controller){
        		echo ".when('".$this->getControllers()->getRoute()."', {
		          templateUrl: '".$this->getControllers()->getTemplate()."',
		          controller: '".$this."',
		          controllerAs: '".$this."'
		        })";
        	}
        	echo'$locationProvider.html5Mode(true);';
			echo '}]);';
        }*/
    }
    protected $childs = array();
    public function addChild($child){
        array_push($this->childs, $child);
        return $this;
    }
    public function getChilds(){
        return $this->childs;
    }
    public function postRender(){
        
    }
}