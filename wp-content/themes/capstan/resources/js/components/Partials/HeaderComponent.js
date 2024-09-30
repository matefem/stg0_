import { StaticComponent } from 'libs';
import { gsap } from 'gsap';

export class HeaderComponent extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.handleScroll 		= this.handleScroll.bind(this);
		this.handleResize 		= this.handleResize.bind(this);
		this.handleMedia 		= this.handleMedia.bind(this);
		this.handleBurgerClick 	= this.handleBurgerClick.bind(this);

		this.nav			= this.element.querySelector('.nav');
		this.burger			= this.element.querySelector('.burger');
		this.subs 			= Array.from(this.element.querySelectorAll('.has-sub'));
		this.li 			= Array.from(this.element.querySelectorAll('.nav>ul>li'));
		this.layer 			= this.element.querySelector('.layer');
		this.overlay		= this.element.querySelector('.overlay');
		this.line			= this.element.querySelector('.line');
		this.logo			= this.element.querySelector('.logo');
		this.joinus			= this.element.querySelector('.join-us');

		this.page 			= document.body.querySelector('.content>.page')

		this.appear 		= [this.logo, ...this.li, this.joinus];

		this.subs = this.subs.map(elem => {
			return {
				elem,
				ul: elem.querySelector('ul'),
				li: elem.querySelectorAll('li'),
				i: elem.querySelectorAll('i'),
				tl: null,
				enter: null,
				leave: null,
				toogle: null
			}
		})
		this.subs.forEach(sub => {
			sub.leave = 	this.handleSubLeave.bind(this, sub)
			sub.enter = 	this.handleSubEnter.bind(this, sub)
			sub.toogle = 	this.toogleSubOpen.bind(this, sub)
		});

		this.scrollY 		= 0;
		this.lastScrollY 	= 0;
		this.pageIsWhite	= false;
    }

	open(){
		const t = gsap.timeline();

		t.fromTo(this.element, {opacity: 0}, {opacity: 1, duration: 0.4}, 0.2);
		t.fromTo(this.element, {y: -100}, {y: 0, duration:0.6, ease: 'power2.out'}, 0.2);

		if(this.viewport.medias.desktop) {
			t.fromTo(this.appear, {opacity: 0}, {opacity: 1, duration: 0.4, stagger: 0.05}, 0.5);
			t.fromTo(this.appear, {y: -50}, {y: 0, duration:0.6, ease: 'power2.out', stagger: 0.05}, 0.5);
		}

		t.set(this.element, {clearProps: 'all'}, 2)
		t.set(this.appear, {clearProps: 'all'}, 2)
		t.set(this.element, {opacity: 1}, 2);
	}

	close(){

	}

	goToTarget() {
		this.closeSlideshow();

		gsap.to(window, {
			duration: 1,
			ease: 'power.out',
			scrollTo: {
				y: this.slides[Math.round(this.target)].elem,
				offsetY: 100
			}
		});
	}

	attach(){
		window.addEventListener('scroll', this.handleScroll);

		this.burger.addEventListener('click', this.handleBurgerClick)

		this.viewport.on('resize', this.handleResize);
		this.viewport.on('media', this.handleMedia);

		this.handleMedia();
		this.handleScroll();

		gsap.fromTo(this.line, {scale: 0}, {scale: 1, ease:'linear', scrollTrigger: {
			trigger: document.body,
			start: 'top top',
			end: 'bottom bottom',
			scrub: 0.8,
			type: 'permanent'
		}});

		this.handleRouterChange();

		this.bootstrap.loader.on('changed', this.handleRouterChange)

		this.open()
	}

	detach(){
		window.removeEventListener('scroll', this.handleScroll);

		this.subs.forEach(sub => {
			sub.elem.removeEventListener('mouseenter', this.handleSubEnter.bind(this, sub));
			sub.elem.removeEventListener('mouseleave', this.handleSubLeave.bind(this, sub));

			sub.elem.removeEventListener('touchend', this.toogleSubOpen.bind(this, sub));
		});

		this.burger.addEventListener('click', this.handleBurgerClick)

		this.viewport.off('resize', this.handleResize);
		this.viewport.off('media', this.handleMedia);
	}

	toogleSubOpen(sub) {
		this.subs.forEach(s => {
			if(s != sub && s.elem.classList.contains('open')) this.handleSubLeave(s)
		});

		this.li.forEach(l => {
			if(l != sub.elem) l.style.opacity = 0.5;
		});

		if(sub.elem.classList.contains('open')) {
			this.handleSubLeave(sub, true);
		} else {
			this.handleSubEnter(sub, true);
		}
	}

	handleRouterChange = (e) => {
		document.body.classList.remove('menu-open');
		document.body.classList.remove('sub-open');

		const first = document.querySelector('.module');

		this.pageIsWhite = true
		if(first && first.classList.contains('module-01')) this.pageIsWhite = false

		if(this.pageIsWhite) document.body.classList.add('white')
		else document.body.classList.remove('white');
	}

	handleSubEnter(sub) {
		sub.elem.classList.add('open');
		document.body.classList.add('sub-open');

		sub.ul.style.height = '';
		const h = sub.ul.getBoundingClientRect().height;

		if(sub.tl) sub.tl.clear();
		sub.tl = gsap.timeline();
		sub.tl.fromTo(sub.li, {opacity: 0}, {opacity: 1, duration: 0.4, stagger: 0.05});

		if(this.viewport.medias.desktop) {
			const layerH = this.nav.getBoundingClientRect().height;
			this.layer.style.transform = 'scale('+((h + layerH) / layerH)+')';
		} else {
			sub.tl.fromTo(sub.ul, {height: 0}, {height: h, duration: 0.3, ease: 'power.out'}, 0)
			sub.tl.fromTo(sub.i, {rotate: 0}, {rotate: 90, duration: 0.4, ease: 'power2.out'}, 0)
		}
	}

	handleSubLeave(sub) {
		document.body.classList.remove('sub-open');

		if(sub.tl) sub.tl.clear();
		sub.tl = gsap.timeline();

		if(this.viewport.medias.desktop) {
			sub.elem.classList.remove('open');

			sub.tl.to(sub.li, {opacity: 0, duration: 0.4});
		} else {
			sub.tl.to(sub.ul, {height: 0, duration: 0.3, ease: 'power.in'}, 0)
			sub.tl.to(sub.i, {rotate: 0, duration: 0.4, ease: 'power2.out'}, 0)

			sub.tl.add(() => {
				sub.elem.classList.remove('open');
			})
		}

		this.li.forEach(l => {
			if(l != sub.elem) l.style.opacity = 1;
		});
		this.layer.style.transform = '';
	}

	handleBurgerClick() {
		document.body.classList.toggle('menu-open')
	}

	handleResize() {

	}

	handleMedia () {
		document.body.classList.remove('menu-open');
		document.body.classList.remove('sub-open');

		this.subs.forEach(sub => {
			sub.elem.removeEventListener('mouseenter', sub.enter);
			sub.elem.removeEventListener('mouseleave', sub.leave);

			sub.elem.removeEventListener('click', sub.toogle);

			if(sub.elem.classList.contains('open')) this.handleSubLeave(sub)
		});

		if(this.viewport.medias.desktop) {
			this.subs.forEach(sub => {
				sub.elem.addEventListener('mouseenter', sub.enter);
				sub.elem.addEventListener('mouseleave', sub.leave);
			});
		} else {
			this.subs.forEach(sub => {
				sub.elem.addEventListener('click', sub.toogle);
			});
		}
	}

	handleScroll(e) {
		this.lastScrollY = this.scrollY;
		this.scrollY = window.scrollY;

		if(document.body.classList.contains('sub-open') ||
			document.body.classList.contains('menu-open')) return;

		if(this.scrollY<0) {
			this.scrollY = 0;
		}

		if(this.scrollY - this.lastScrollY > 0) {
			if(!document.body.classList.contains('scrolled'))
				document.body.classList.add('scrolled')
		} else {
			if(document.body.classList.contains('scrolled'))
				document.body.classList.remove('scrolled')
			if(this.viewport.medias.desktop && !this.pageIsWhite) {
				if(this.scrollY > 10) {
					if(!document.body.classList.contains('white'))
						document.body.classList.add('white')
				} else if(document.body.classList.contains('white')) {
					document.body.classList.remove('white')
				}
			}
		}
	}

	async refreshAccountStatus() {
		const result = await ajaxFetcher.call("capstant_account_status", new FormData());
		if (result["success"]) {
			const txt = document.createElement("textarea");
			txt.innerHTML = result["result"];
			this.element.querySelector(".account-wrapper").innerHTML = txt.value;
		}
	}
}