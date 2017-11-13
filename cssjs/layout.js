/****************************************************************
Drag Classes - Copyright by Simon Käser
endlessx.com || admin@endlessx.com
this code could be used freely, as long as this message is
intact!
****************************************************************/

var check=( document &&
            document.getElementById  &&
            document.getElementsByTagName );

//if( check )
//{

        drag_elm=false;
        temp=document.onselectstart;

        document.getElementsByClassName=function( cls )
        {
                arr=new Array();
                n=document.getElementsByTagName( 'div' ).length;
                for( x = 0 ; x < n ; x++ )
                {
                        ncls=document.getElementsByTagName( 'div' )[ x ].className;
                        nclsa=ncls.split( ' ' );
                        for( y in nclsa )
                        {
                                ncls=nclsa[ y ];
                                if( ncls == cls )
                                arr[ arr.length ]=document.getElementsByTagName( 'div' )[ x ];
                        }
                }
                return arr;
        }

        function checkover( elm, mx, my )
        {
                if( elm.parentNode.className == 'container' )
                {
                        mx-=elm.parentNode.offsetLeft;
                        my-=elm.parentNode.offsetTop;
                }
                if( mx > elm.offsetLeft  &&
                    mx < elm.offsetLeft + elm.offsetWidth &&
                    my > elm.offsetTop &&
                    my < elm.offsetTop + elm.offsetHeight )
                        return true;
                else
                        return false;
        }

        document.onmousedown=function( e )
        {
                drag_elm=false;
                mx=window.event ? event.x : e.pageX;
                my=window.event ? event.y : e.pageY;
                drs=document.getElementsByClassName( 'drag' );
                n=drs.length;
                for( x = ( n - 1 ) ; x >= 0 ; x-- )
                {
                        elm=drs[ x ];
                        if( checkover( elm, mx, my ) )
                        {
                                document.onselectstart=function(){ return false; }
                                drag_elm=elm;
                                drag_elm.dx=mx - elm.offsetLeft;
                                drag_elm.dy=my - elm.offsetTop;
                                drag_elm.ex=false;
                                drag_elm.ey=false;
                                if( elm.parentNode.className == 'container' )
                                {
                                        drag_elm.ex=elm.parentNode.offsetWidth - elm.offsetWidth;
                                        drag_elm.ey=elm.parentNode.offsetHeight - elm.offsetHeight;
                                }
                                return drag_elm;
                        }
                }
        }

        document.onmouseup=function( e )
        {
                drag_elm=false;
                document.onselectstart=temp;
        }

        document.onmousemove=function( e )
        {
                mx=window.event ? event.x : e.pageX;
                my=window.event ? event.y : e.pageY;
                if( drag_elm )
                {
                        x=mx - drag_elm.dx;
                        y=my - drag_elm.dy;
                        if( drag_elm.ex )
                        {
                                if( mx - drag_elm.dx > drag_elm.ex )
                                        x=drag_elm.ex;
                                if( mx - drag_elm.dx < 0 )
                                        x=0;
                                if( my - drag_elm.dy > drag_elm.ey )
                                        y=drag_elm.ey;
                                if( my - drag_elm.dy < 0 )
                                        y=0;
                        }
                        drag_elm.style.left=x + "px";
                        drag_elm.style.top=y + "px";
                }
        }
//}

     var getAbsoluteCoords = function (e) {
        var width = e.offsetWidth;
        var height = e.offsetHeight;
        var left = e.offsetLeft;
        var top = e.offsetTop;
        while (e=e.offsetParent) {
          left += e.offsetLeft;
          top  += e.offsetTop;
        };
        var right = left+width;
        var bottom = top+height;
        return {
          'width': width,
          'height': height,
          'left': left,
          'top': top,
          'right': right,
          'bottom': bottom
        };
      };

      var getElementById = function (sId) {
        return document.getElementById(String(sId));
      };

      /* Kernel code, drag div change the coords */
      /* by never-online, http://www.never-online.net */

      var wrapId = "dragDiv";
	  var wrap = getElementById(wrapId);
      var isChangeLayout;
      wrap.onmouseover = function () {
        	isChangeLayout=getElementById('layout').checked?true:false;
        	wrap.style.cursor = isChangeLayout?"move":"se-resize";
        	if (window.ActiveXObject)
          		wrap.onselectstart = function () { event.returnValue = false; }

        	document.onmousedown = function (evt) {
          		/* save the original coordinates */
          		evt = window.event||evt; var a=getAbsoluteCoords(wrap);
          		wrap.cx=evt.clientX-(isChangeLayout?a.left:a.width);
          		wrap.cy=evt.clientY-(isChangeLayout?a.top:a.height);

          		document.onmousemove = function (evt) {
            		/* change the coords when mouse is moveing */
            		evt = window.event||evt;
					try {
              			if (isChangeLayout) {
                			wrap.style.left = (evt.clientX-wrap.cx)+"px";
                			wrap.style.top = (evt.clientY-wrap.cy)+"px";
              			} else {
                			wrap.style.width = (evt.clientX-wrap.cx)+"px";
                			wrap.style.height = (evt.clientY-wrap.cy)+"px";
              			}
            			} catch (ex) {};
          		};

          		document.onmouseup = function () {
            		/* drag end release the event */
            		document.onmousemove = null;
            		document.onmouseup = null;
            		wrap.style.cursor="default";
          		};
        	};
      }