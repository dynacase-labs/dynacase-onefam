
/**
 * @author Anakeen
 */


var DRAGGING=false;

//----- COL ----------
var ROW=-1;

function startResizeH(event) {
  if (! event) event=window.event;
  if (! DRAGGING) {
    var draggable =document.getElementById('draggable');
    draggable.className='viewhscroll';
    var cacheif=document.getElementById('cacheiframes');
    var iframes=document.getElementById('divframes');
    document.onmousemove = moveResizeH ;  
    document.onmouseup = endResizeH ;      
    stopPropagation(event);
    DRAGGING=true;
    moveResizeH(event);
    cacheif.style.top=iframes.style.top;
    cacheif.style.width='100%';
    cacheif.style.left=iframes.style.left;
    cacheif.style.height=iframes.style.height;
    cacheif.style.display='';
  }
  return false;  
}

function moveResizeH(event) {
  if (DRAGGING) {
    var draggable = document.getElementById('draggable');
    var delta = 2;
    if (! event) event=window.event;
    GetXY(event);
    delta = 2;
    draggable.style.left=Xpos-delta; 
    stopPropagation(event);
    return false;
  }
}

function endResizeH(event) {  
  if (! event) event=window.event;
    var draggable = document.getElementById('draggable');
    draggable.className='resizeH';
    document.onmousemove= "";
    document.onmouseup="" ;
    GetXY(event);
    ROW=Xpos-2;
    COL=-1;
    redisplaywsdiv(event);
    DRAGGING=false;
    unglobalcursor();
    var cacheif=document.getElementById('cacheiframes');
      cacheif.style.display='none';
}

//----- ROW ----------
var COL=-1;

function startResizeV(event) {
  if (! event) event=window.event;
  if (! DRAGGING) {
    var draggable=document.getElementById('draggable');
    draggable.className='viewvscroll';
    var cacheif=document.getElementById('cacheiframes');
    var iframes=document.getElementById('divframes');
    document.onmousemove=moveResizeV ;  
    document.onmouseup=endResizeV ;      
    stopPropagation(event);
    DRAGGING=true;
    moveResizeV(event);
    cacheif.style.top=iframes.style.top;
    cacheif.style.width='100%';
    cacheif.style.left=iframes.style.left;
    cacheif.style.height=iframes.style.height;
    cacheif.style.display='';
    
  }
  return false;  
}

function moveResizeV(event) {
  if (DRAGGING) {
    var draggable=document.getElementById('draggable');
    var delta=2;
    if (! event) event=window.event;
    GetXY(event);
    delta=2;
    draggable.style.top=Ypos-delta;
    stopPropagation(event);
    return false;
  }
}

function endResizeV(event) {  
  if (! event) event=window.event;
  var draggable=document.getElementById('draggable');
  draggable.className='resizeV';
  document.onmousemove= "";
  document.onmouseup="" ;
  GetXY(event);
  COL=Ypos-2;
  ROW=-1;
  redisplaywsdiv(event);
  DRAGGING=false;
  unglobalcursor();
    var cacheif=document.getElementById('cacheiframes');
      cacheif.style.display='none';
}

// Utility function to add an event listener
function addEvent(o,e,f){
	if (o.addEventListener){ o.addEventListener(e,f,true); return true; }
	else if (o.attachEvent){ return o.attachEvent("on"+e,f); }
	else { return false; }
}

//----- Resize ----------
function redisplaywsdiv(event) {
  var listicon=document.getElementById('listicon');
  var divframes=document.getElementById('divframes');
  var flist=document.getElementById('flist');
  var finfo=document.getElementById('finfo');
  var draggable=document.getElementById('draggable');
  var ww=getFrameWidth();
  var wh=getFrameHeight();
  var dx=0;
  var ch=0;//current height
  var w2,w3; // width of flist finfo
  var w4 = window.parent.outerWidth;

  listicon.style.top='0px';
  listicon.style.left=dx+'px';
  listicon.style.height='100%'; 

  divframes.style.top='0px';
  divframes.style.left=dx+'px';
  divframes.style.height='100%';
  divframes.style.width='100%';  

  dx+=40;
  flist.style.top='0px';
  flist.style.left=dx+'px';

  // Vertical style
  if (COL && (COL>0))
  {
    if (COL<250) COL=250;
    ch=parseInt(COL);
    if (COL>(w4-250)) COL=w4-250;
    ch=parseInt(COL);
    flist.style.width=(ww-40)+'px';
    flist.style.height=ch+'px';
    draggable.style.top=parseInt(ch)+'px';
    dx=ch+5;
    finfo.style.top=dx+'px';
    finfo.style.left='40px';
    finfo.style.right='0px';
    finfo.style.bottom='0px'; 
  }
  
  // Basic style
  if (ROW && (ROW>0)) 
  {
    if (ROW<440) ROW=440;
    wcol2=parseInt(ROW)-parseInt(40);
    if (ROW>w4-250) ROW=w4-250;
    wcol2=parseInt(ROW)-parseInt(40);
    flist.style.width=wcol2+'px';
    flist.style.height=wh+'px'; 
    draggable.style.left=parseInt(wcol2)+parseInt(40);
    dx=ROW+5;
    finfo.style.top='0px';
    finfo.style.left=dx+'px';
    finfo.style.right='0px';
    finfo.style.height='100%'; 
  } 
  window.MultiDocument.onResize();
}


addEvent(window,"resize",redisplaywsdiv);
