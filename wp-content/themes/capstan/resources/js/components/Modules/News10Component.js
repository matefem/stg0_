import { StaticComponent } from 'libs';

export class News10Component extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.handleMove 	= this.handleMove.bind(this);
		this.handleResize 	= this.handleResize.bind(this);
		this.handleUp 		= this.handleUp.bind(this);
		this.handleDown 	= this.handleDown.bind(this);

		this.gallery		= this.element.querySelector('.gallery');
		this.scrollbar		= this.element.querySelector('.gallery-scrollbar .inner');
		this.slides 		= Array.from(this.element.querySelectorAll('.item')).map(elem => {
			return {
				elem,
				img: elem.querySelector('.image'),
				w: 1
			}
		});

		this.mouse 		 	= 0;
		this.start 		 	= 0;
		this.max			= 0;
		this.current		= 0;
    }
	
	open(){

	}
	close(){

	}

	attach(){
		this.handleResize()

		this.element.addEventListener('mousedown',	this.handleDown);
		this.element.addEventListener('touchstart',	this.handleDown, {passive: true});
		this.viewport.on('resize', this.handleResize);
	}
	detach(){
		this.element.removeEventListener('mousedown', 	this.handleDown);
		this.element.removeEventListener('touchstart', 	this.handleDown);
		this.viewport.off('resize', this.handleResize)
	}

	handleResize() {
		this.max = 0

		this.slides.forEach(slide => {
			slide.w = slide.elem.getBoundingClientRect().width
			this.max += slide.w + 20;
		});

		this.max = this.max - this.viewport.width;

		this.handleMove();
	}

	handleMove(e) {
		this.mouse = this.getTouchX(e);

		this.current = Math.max(Math.min(this.mouse - this.start, 0), -this.max)
		this.gallery.style.transform = 'translate3d('+this.current+'px, 0, 0)';
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