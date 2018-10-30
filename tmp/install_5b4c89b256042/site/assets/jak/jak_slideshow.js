 /*-----------------slideshow-----------------------*/
 SZN.SlideShow = SZN.ClassMaker.makeClass({
 	NAME: 'SZN.SlideShow',
 	VERSION: '1.0',
 	CLASS: 'class',
 	IMPLEMENT: [SZN.SigInterface]
 });

 SZN.SlideShow.prototype.$constructor = function(owner, name, settings) {
 	this.owner = owner;
 	this.options = {
 		duration : 2,
 		autoplay: false,
 		id : false,
 		className: 'image-browser-slideshow',
 		pauseId: false,
 		pauseClassName: 'image-browser-slideshow-pause',
 		playId: false,
 		playClassName: 'image-browser-slideshow-play'
 	};
 	/*prepsani vychoziho nastaveni z uziv. konfigurace*/
	for (var p in settings) { this.options[p] = settings[p]; }
 	/*interval pro animaci*/
 	this.interval = null;
 	/*zbindovani metody pro pouziti v intervalu*/
 	this._timeoutDone = SZN.bind(this, this._timeoutDone);
 	/*uschovna DOM elementu*/
 	this.dom = {};

 	this._addEvents();
 	this._render();
 	/*schovani jednoho z tlacitek*/
 	this.options.autoplay ? this._hidePlay() : this._hidePause();
 };

 SZN.SlideShow.prototype.$destructor = function(){
 	this.removeListener('close', '_stop', this.owner);
 	this.removeListener('transitionDone', '_next', this.owner);

 	for (var i in this) {
 		this[i] = null;
 	}
 };

 SZN.SlideShow.prototype._addEvents = function() {
 	if (this.options.autoplay) {
 		this.addListener('transitionDone', '_next', this.owner);
 	}
 	this.addListener('close', '_stop', this.owner);
 };

 SZN.SlideShow.prototype._render = function() {
 	var c = SZN.cEl('div', this.options.id, this.options.className);

 	var pause = SZN.cEl('a', this.options.pauseId, this.options.pauseClassName);
 	pause.href = '#';
 	c.appendChild(pause);

 	var play = SZN.cEl('a', this.options.playId, this.options.playClassName);
 	play.href = '#';
 	c.appendChild(play);

 	SZN.Events.addListener(pause, 'click', this, '_stopClick');
 	SZN.Events.addListener(play, 'click', this, '_playClick');

 	this.dom.pause = pause;
 	this.dom.play = play;

 	this.owner.dom.content.appendChild(c);
 };

 /**
  * metoda spustena pri kliku na tlacitko pause
  * @param {event} e
  * @param {HTMLelement} elm
  */
 SZN.SlideShow.prototype._stopClick = function(e, elm) {
 	SZN.Events.cancelDef(e);
 	this.removeListener('transitionDone', '_next', this.owner);
 	this._hidePause();
 	this._stop();
 };

 /**
  * metoda spustena pri kliku na tlacitko play
  * @param {event} e
  * @param {HTMLelement} elm
  */
 SZN.SlideShow.prototype._playClick = function(e, elm) {
 	SZN.Events.cancelDef(e);
 	this.addListener('transitionDone', '_next', this.owner);
 	this._hidePlay();
 	this._next();
 };

 /**
  * zastaveni slideshow
  */
 SZN.SlideShow.prototype._stop = function() {
 	clearTimeout(this.interval);
 };

 /**
  * spusteni slideshow
  */
 SZN.SlideShow.prototype._next = function() {
 	this.interval = setTimeout(this._timeoutDone, this.options.duration*1000);
 };

 /**
  * schovani tlacitka play, zobrazeni pause
  */
 SZN.SlideShow.prototype._hidePlay = function() {
 	this.dom.play.style.display = 'none';
 	this.dom.pause.style.display = '';
 };

 /**
  * schovani tlacitka pause, zobrazeni play
  */
 SZN.SlideShow.prototype._hidePause = function() {
 	this.dom.play.style.display = '';
 	this.dom.pause.style.display = 'none';
 };

 /**
  * metoda volana timeoutem, ktery je nastaven v metode _next
  * zde je vlastni posunuti na dalsi obrazek
  */
 SZN.SlideShow.prototype._timeoutDone = function() {
 	clearTimeout(this.interval);
 	this.owner.next();
 };

 /*-----------------------------*/
 


