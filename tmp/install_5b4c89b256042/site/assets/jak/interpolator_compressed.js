/*
Licencováno pod MIT Licencí

© 2008 Seznam.cz, a.s.

Tímto se uděluje bezúplatná nevýhradní licence k oprávnění užívat Software,
časově i místně neomezená, v souladu s příslušnými ustanoveními autorského zákona.

Nabyvatel/uživatel, který obdržel kopii tohoto softwaru a další přidružené 
soubory (dále jen „software“) je oprávněn k nakládání se softwarem bez 
jakýchkoli omezení, včetně bez omezení práva software užívat, pořizovat si 
z něj kopie, měnit, sloučit, šířit, poskytovat zcela nebo zčásti třetí osobě 
(podlicence) či prodávat jeho kopie, za následujících podmínek:

- výše uvedené licenční ujednání musí být uvedeno na všech kopiích nebo 
podstatných součástech Softwaru.

- software je poskytován tak jak stojí a leží, tzn. autor neodpovídá 
za jeho vady, jakož i možné následky, ledaže věc nemá vlastnost, o níž autor 
prohlásí, že ji má, nebo kterou si nabyvatel/uživatel výslovně vymínil.



Licenced under the MIT License

Copyright (c) 2008 Seznam.cz, a.s.

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/{SZN.Interpolator=SZN.ClassMaker.makeClass({"NAME":"Interpolator","VERSION":"1.0","CLASS":"class"});SZN.Interpolator.LINEAR=1;SZN.Interpolator.QUADRATIC=2;SZN.Interpolator.SQRT=3;SZN.Interpolator.SIN=4;SZN.Interpolator.ASIN=5;SZN.Interpolator.prototype.$constructor=function(startVal,endVal,interval,callback,options){this.startVal=startVal;this.endVal=endVal;this.interval=interval;this.callback=callback;this.options={"interpolation":SZN.Interpolator.LINEAR,"frequency":20,"endCallback":false};this.running=false;this._tick=SZN.bind(this,this._tick);for(var p in options){this.options[p]=options[p];}};SZN.Interpolator.prototype._call=function(frac){var result=this._interpolate(frac);var delta=this.endVal-this.startVal;this.callback(this.startVal+delta*result);};SZN.Interpolator.prototype._interpolate=function(val){if(typeof (this.options.interpolation)=="function"){return this.options.interpolation(val);}switch(this.options.interpolation){case SZN.Interpolator.QUADRATIC:return val*val;case SZN.Interpolator.SQRT:return Math.sqrt(val);case SZN.Interpolator.SIN:return (Math.sin(Math.PI*(val-0.5))+1)/2;case SZN.Interpolator.ASIN:return (Math.asin(2*(val-0.5))+Math.PI/2)/Math.PI;default:return val;}};SZN.Interpolator.prototype.start=function(){if(this.running){return;}this.running=true;this.startTime=(new Date()).getTime();this._call(0);this.handle=setInterval(this._tick,this.options.frequency);};SZN.Interpolator.prototype.stop=function(){if(!this.running){return;}this.running=false;clearInterval(this.handle);};SZN.Interpolator.prototype._tick=function(){var now=(new Date()).getTime();var elapsed=now-this.startTime;if(elapsed>=this.interval){this.stop();this._call(1);if(this.options.endCallback){this.options.endCallback();}}else{this._call(elapsed/this.interval);}};SZN.CSSInterpolator=SZN.ClassMaker.makeClass({"NAME":"CSSInterpolator","VERSION":"1.0","CLASS":"class"});SZN.CSSInterpolator.prototype.$constructor=function(elm,interval,options){this.elm=elm;this.properties=[];this.colors=[];this._tick=SZN.bind(this,this._tick);this.interpolator=new SZN.Interpolator(0,1,interval,this._tick,options);};SZN.CSSInterpolator.prototype.addProperty=function(property,startVal,endVal,suffix){var o={"property":property,"startVal":startVal,"endVal":endVal,"suffix":suffix||""};this.properties.push(o);};SZN.CSSInterpolator.prototype.addColorProperty=function(property,startVal,endVal){var o={"startVal":SZN.Parser.color(startVal),"endVal":SZN.Parser.color(endVal),"property":property};this.colors.push(o);};SZN.CSSInterpolator.prototype.start=function(){this.interpolator.start();};SZN.CSSInterpolator.prototype.stop=function(){this.interpolator.stop();};SZN.CSSInterpolator.prototype._tick=function(frac){for(var i=0;i<this.properties.length;i++){var prop=this.properties[i];var val=prop.startVal+frac*(prop.endVal-prop.startVal);val+=prop.suffix;this.elm.style[prop.property]=val;}var names=["r","g","b"];for(var i=0;i<this.colors.length;i++){var c=this.colors[i];var out=[0,0,0];for(var j=0;j<names.length;j++){var name=names[j];out[j]=c.startVal[name]+Math.round(frac*(c.endVal[name]-c.startVal[name]));}var result="rgb("+out.join(",")+")";this.elm.style[c.property]=result;}};}
