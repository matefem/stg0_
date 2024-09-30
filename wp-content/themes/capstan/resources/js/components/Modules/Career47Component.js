import { StaticComponent } from 'libs';
import { gsap } from 'gsap';
import { ScrollTrigger } from 'gsap/ScrollTrigger';

export class Career47Component extends StaticComponent{
	constructor(e, b){
		super(e, b);

		this.content 	= this.element.querySelector('.content');
		this.menu 		= this.element.querySelector('.menu');
		this.chapters	= Array.from(this.menu.querySelectorAll('[data-ref]')).map((btn, i) => {
			const target = this.element.querySelector('[data-id="'+(btn.getAttribute('data-ref'))+'"]')

			return {
				btn,
				target,
				click: this.scrollTo.bind(this, target),
				i
			}
		})
    }

	attach(){
		this.viewport.on('media', this.handleMedia);
		this.handleMedia();

		this.t = [];

		this.chapters.forEach(chapter => {
			chapter.btn.addEventListener('click', chapter.click, {passive: false});

			this.t.push(ScrollTrigger.create({
				trigger: chapter.target,
				start: 'top center',
				end: 'bottom center',
				onEnter: () => {
					chapter.btn.parentNode.classList.add('current')
				},
				onEnterBack: () => {
					chapter.btn.parentNode.classList.add('current')
				},
				onLeave: () => {
					chapter.btn.parentNode.classList.remove('current')
				},
				onLeaveBack: () => {
					chapter.btn.parentNode.classList.remove('current')
				}
			}))
		});
	}

	detach(){
		this.viewport.off('media', this.handleMedia);

		if(this.t.length) {
			this.t.forEach(t => {
				t.disable();
				t = null
			});
		}
	}

	handleMedia = () => {
		if(this.st) this.st.kill()

		if(this.viewport.medias.desktop) {
			this.st = ScrollTrigger.create({
				trigger: this.content,
				start: 'top +=100px',
				end: 'bottom center',
				pin: this.menu,
				pinSpacing: false
			});
		}
 	}

	scrollTo(target) {
		gsap.to(window, {
			duration: 1,
			ease: 'power2.inOut',
			scrollTo: {
				y: target,
				offsetY: 120
			}
		});
	}
}