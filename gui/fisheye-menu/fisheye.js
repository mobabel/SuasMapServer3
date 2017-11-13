if (document.getElementById && document.getElementsByTagName) {
if (window.addEventListener) window.addEventListener('load', init, false);
else if (window.attachEvent) window.attachEvent('onload', init);
}

function init() {
		var startSize = 55; // starting icon size
		var endSize = 81; // ending icon size
		var animElements = document.getElementById("fisheye_menu").getElementsByTagName("img");
		var titleElements = document.getElementById("fisheye_menu").getElementsByTagName("span");
		for(var j=0; j<titleElements.length; j++) {
			titleElements[j].style.display = 'none';
		}
		for(var i=0; i<animElements.length; i++) {
			animElements[i].style.width = startSize+'px';
			animElements[i].style.height = startSize+'px';
			animElements[i].onmouseover = changeSize;
			animElements[i].onmouseout = restoreSize;
		}

		function changeSize() {
			var x = this.parentNode.getElementsByTagName("span");
			x[0].style.display = 'block';
			if (!this.currentWidth) this.currentWidth = startSize;
			resizeAnimation(this,this.currentWidth,endSize,15,10,0.333);
		}

		function restoreSize() {
			var x = this.parentNode.getElementsByTagName("span");
			x[0].style.display = 'none';
			if (!this.currentWidth) return;
			resizeAnimation(this,this.currentWidth,startSize,15,10,0.5);
		}
	}

function resizeAnimation(elem,startWidth,endWidth,steps,intervals,powr) {
	if (elem.widthChangeMemInt) window.clearInterval(elem.widthChangeMemInt);
	var actStep = 0;
	elem.widthChangeMemInt = window.setInterval(
		function() {
			elem.currentWidth = easeInOut(startWidth,endWidth,steps,actStep,powr);
			elem.style.width = elem.currentWidth+"px";
			elem.style.height = elem.currentWidth+"px";
			actStep++;
			if (actStep > steps) window.clearInterval(elem.widthChangeMemInt);
		}
		,intervals)
}

function easeInOut(minValue,maxValue,totalSteps,actualStep,powr) {
//Generic Animation Step Value Generator By www.hesido.com
	var delta = maxValue - minValue;
	var stepp = minValue+(Math.pow(((1 / totalSteps)*actualStep),powr)*delta);
	return Math.ceil(stepp)
}
