import { StaticComponent } from 'libs';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export class Gallery08Component extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.handleMove 	= this.handleMove.bind(this);
		this.handleResize 	= this.handleResize.bind(this);
		this.handleUp 		= this.handleUp.bind(this);
		this.handleDown 	= this.handleDown.bind(this);

		this.container		= this.element.querySelector('.container');
		this.dots			= this.element.querySelector('.dots');
		this.slides 		= Array.from(this.element.querySelectorAll('.item')).map((elem, i) => {

			return {
				elem,
				n: i,
				img: elem.querySelector('.image'),
				w: 1,
				dot: null,
				event: null,
				timer: null
			}
		});

		if(this.slides.length > 1)
			this.slides.forEach((slide, i) => {
				const dot = document.createElement('div')
				dot.classList.add('dot')
				if(i == 0) dot.classList.add('active')
				dot.innerHTML = '<span><svg viewport="0 0 20 20" preserveAspectRatio="xMidYMid meet"><circle cx="10" cy="10" r="9" class="circle-front"/><circle cx="10" cy="10" r="9" class="circle-back"/></svg></span>'
				this.dots.appendChild(dot)

				slide.event = this.handleDotClick.bind(this, slide)
				slide.dot = dot
				slide.timer = dot.querySelector('.circle-front')
			});

		this.mouse 		= 0;
		this.start 		= 0;
		this.max		= 0;
		this.current	= 0;
		this.id			= 0;
		this.last		= 0;
		this.nextTl		= null;
		this.scrollTl	= null;
		this.ticking	= false;
    }

	attach(){
		if(this.slides.length <= 1) return;

		this.handleResize()

		this.container.addEventListener('mousedown',	this.handleDown);
		this.container.addEventListener('touchstart',	this.handleDown, {passive: true});
		this.viewport.on('resize', this.handleResize);

		this.scrollTl = ScrollTrigger.create({
			trigger: this.element,
			onEnter:() => {
				this.requestTick();
			},
			onEnterBack:() => {
				this.requestTick();
			},
			onLeave:() => {
				this.stopTick();
			},
			onLeaveBack:() => {
				this.stopTick();
			},
		})

		this.slides.forEach(slide => {
			slide.dot.addEventListener('click', slide.event)
		});
	}
	detach(){
		if(this.slides.length <= 1) return;

		this.container.removeEventListener('mousedown', 	this.handleDown);
		this.container.removeEventListener('touchstart', 	this.handleDown);
		this.viewport.off('resize', this.handleResize);

		this.scrollTl.kill();

		this.slides.forEach(slide => {
			slide.dot.removeEventListener('click', slide.event)
		});
	}

	handleResize() {
		this.max = 0

		this.slides.forEach(slide => {
			slide.w = slide.elem.getBoundingClientRect().width
			this.max += slide.w + (this.viewport.medias.desktop?60:0);
		});

		this.max = this.max - this.viewport.width;

		this.handleMove();
	}

	handleMove(e) {
		this.mouse = this.getTouchX(e);

		this.moveTo((this.mouse - this.start))
	}

	moveTo(x) {
		this.current = Math.max(Math.min(x, 0), -this.max);
		this.container.style.transform = 'translate3d('+this.current+'px, 0, 0)';

		this.id = -Math.round(this.current / this.max * (this.slides.length - 1))
		
		if(this.last != this.id) {
			this.slides[this.last].dot.classList.remove('active')
			this.slides[this.id].dot.classList.add('active')

			this.last = this.id;
		}
	}

	snapTo(i) {
		i = (i % this.slides.length)
		this.moveTo(-i * this.max / (this.slides.length - 1))
	}

	handleDown(e) {
		this.stopTick();
		this.start = this.getTouchX(e) - this.current;

		this.container.classList.add('no-anim')

		this.container.addEventListener('mousemove', 	this.handleMove);
		this.container.addEventListener('touchmove', 	this.handleMove, {passive: true});
		window.addEventListener('mouseup', 		this.handleUp);
		window.addEventListener('touchend', 	this.handleUp, {passive: true});
	}

	handleUp(e) {
		this.requestTick();

		this.container.classList.remove('no-anim')
		
		this.container.removeEventListener('mousemove', 	this.handleMove);
		this.container.removeEventListener('touchmove', 	this.handleMove);
		window.removeEventListener('mouseup', 	this.handleUp);
		window.removeEventListener('touchend', 	this.handleUp);

		this.moveTo(Math.round(this.current / this.max * (this.slides.length - 1)) * this.max / (this.slides.length - 1))
	}

	tick = () => {
		if(!this.ticking) return;
		if(this.nextTl) this.nextTl.clear();

		this.nextTl = gsap.timeline()

		this.nextTl.fromTo(this.slides[this.id].timer, {strokeDashoffset: 56.5}, {strokeDashoffset: 0, duration: 5, ease: 'power.inOut'}, 0)
		this.nextTl.add(() => {
			if(!this.ticking) return;
			this.snapTo(this.id + 1);
			this.tick()
		}, 5);
	}

	requestTick() {
		this.ticking = true;
		this.tick()
	}

	stopTick() {
		if(this.nextTl) this.nextTl.clear();
		this.ticking = false;
	}

	handleDotClick(slide) {
		this.stopTick()
		this.moveTo(-slide.n * this.max / (this.slides.length - 1));
		setTimeout(() => {
			this.requestTick()
		}, 400)
	}

    getTouchX(e) {
    	if(!e) return 0
		if(e.clientX) return e.clientX
		else if(e.changedTouches && e.changedTouches.length)
			return e.changedTouches[0].clientX
		return 0
    }
}