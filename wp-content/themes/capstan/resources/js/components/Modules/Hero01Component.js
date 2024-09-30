import { StaticComponent } from 'libs';

export class Hero01Component extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.handleMove 	= this.handleMove.bind(this);
		this.handleScroll 	= this.handleScroll.bind(this);
		this.handleResize 	= this.handleResize.bind(this);
		this.handleEnter 	= this.handleEnter.bind(this);
		this.handleLeave 	= this.handleLeave.bind(this);
		this.handleUp 		= this.handleUp.bind(this);
		this.handleDown 	= this.handleDown.bind(this);

		this.pointer 		= this.element.querySelector('.hold');

		this.scrollY 	 	= 0;
		this.mouse 		 	= [0, 0];
		this.pointerBBox 	= this.pointer.getBoundingClientRect();
    }
	
	open(){

	}
	close(){

	}

	attach(){
		this.handleResize()

		this.element.addEventListener('mousemove', 	this.handleMove);
		this.element.addEventListener('mouseenter', this.handleEnter);
		this.element.addEventListener('mouseleave', this.handleLeave);
		this.element.addEventListener('mousedown',	this.handleDown);
		window.addEventListener('mouseup', 	this.handleUp);

		window.addEventListener('scroll', this.handleScroll);
		this.viewport.on('resize', this.handleResize)
	}
	detach(){
		this.element.removeEventListener('mousemove', 	this.handleMove);
		this.element.removeEventListener('mouseenter', 	this.handleEnter);
		this.element.removeEventListener('mouseleave', 	this.handleLeave);
		this.element.removeEventListener('mousedown', 	this.handleDown);
		window.removeEventListener('mouseup', 	this.handleUp);

		window.removeEventListener('scroll', this.handleScroll);
		this.viewport.off('resize', this.handleResize)
	}

	handleResize() {
		this.pointerBBox = this.pointer.getBoundingClientRect();
	}

	handleScroll(e) {
		this.scrollY = window.scrollY || document.documentElement.scrollTop || 0;
		if(this.scrollY < this.viewport.height) this.transformCursor();
	}

	handleMove(e) {
		this.mouse = [e.clientX, e.clientY];
		this.transformCursor();
	}

	handleEnter() {
		this.pointer.classList.add('hover')
	}

	handleLeave() {
		this.pointer.classList.remove('hover')
	}

	handleDown() {
		this.pointer.classList.add('click')
	}

	handleUp() {
		this.pointer.classList.remove('click')
	}

	transformCursor() {
		this.pointer.style.transform = 'translate3d('+(this.mouse[0] - this.pointerBBox.width * 0.5)+'px, '+(this.mouse[1] + this.scrollY - this.pointerBBox.height * 0.5)+'px, 0)'
	}
}