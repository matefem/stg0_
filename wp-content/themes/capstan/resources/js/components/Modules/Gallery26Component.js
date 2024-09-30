import { StaticComponent } from 'libs';

export class Gallery26Component extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.handleMove 	= this.handleMove.bind(this);
		this.handleResize 	= this.handleResize.bind(this);
		this.handleUp 		= this.handleUp.bind(this);
		this.handleDown 	= this.handleDown.bind(this);

		this.gallery		= this.element.querySelector('.container');
		this.slides 		= Array.from(this.element.querySelectorAll('.item')).map(elem => {
			return {
				elem,
				img: elem.querySelector('.image img'),
				w: 1
			}
		});

		this.mouse 	 = 0;
		this.start 	 = 0;
		this.max	 = 0;
		this.current = 0;
    }
	
	open(){

	}
	close(){

	}

	attach(){
		if(this.slides.length == 0) return 
		this.handleResize()

		this.element.addEventListener('mousedown',	this.handleDown);
		this.element.addEventListener('touchstart',	this.handleDown, {passive: true});
		this.viewport.on('resize', this.handleResize);
	}
	detach(){
		if(this.slides.length == 0) return
		this.element.removeEventListener('mousedown', 	this.handleDown);
		this.element.removeEventListener('touchstart', 	this.handleDown);
		this.viewport.off('resize', this.handleResize)
	}

	handleResize() {
		this.max = 0

		this.slides.forEach(slide => {
			slide.w = slide.elem.getBoundingClientRect().width
			this.max += slide.w + 30;
		});

		this.max = this.max + 60 - this.viewport.width;

		this.handleMove();
	}

	handleMove(e) {
		this.mouse = this.getTouchX(e);
		this.current = Math.max(Math.min((this.mouse - this.start) * (this.viewport.medias.desktop ? 1 : 2.5), 0), -this.max)
		this.gallery.style.transform = 'translate3d('+this.current+'px, 0, 0)';

		this.slides.forEach(slide => {
			slide.img.style.transform = 'translate3d('+(this.current / this.max * 20 + 10)+'%, 0, 0) scale(1.2)'
		});

	}

	handleDown(e) {
		this.start = this.getTouchX(e) - this.current;

		this.element.addEventListener('mousemove', 	this.handleMove);
		this.element.addEventListener('touchmove', 	this.handleMove, {passive: true});
		window.addEventListener('mouseup', 		this.handleUp);
		window.addEventListener('touchend', 	this.handleUp, {passive: true});
	}

	handleUp(e) {
		this.element.removeEventListener('mousemove', 	this.handleMove);
		this.element.removeEventListener('touchmove', 	this.handleMove);
		window.removeEventListener('mouseup', 	this.handleUp);
		window.removeEventListener('touchend', 	this.handleUp);
	}

    getTouchX(e) {
    	if(!e) return 0
		if(e.clientX) return e.clientX
		else if(e.changedTouches && e.changedTouches.length)
			return e.changedTouches[0].clientX
		return 0
    }
}